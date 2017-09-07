<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\RouteTracker as RouteTrackerContract;
use Arcanedev\LaravelTracker\Models;
use Arcanedev\LaravelTracker\Support\BindingManager;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;

/**
 * Class     RouteTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouteTracker extends AbstractTracker implements RouteTrackerContract
{
    /* -----------------------------------------------------------------
     |  Getters and Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the model.
     *
     * @return \Arcanedev\LaravelTracker\Models\Route
     */
    protected function getModel()
    {
        return $this->makeModel(BindingManager::MODEL_ROUTE);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if the route is trackable.
     *
     * @param  \Illuminate\Routing\Route $route
     *
     * @return bool
     */
    public function isTrackable(Route $route)
    {
        return ! $this->isInIgnoredRouteNames($route) &&
               ! $this->isInIgnoredUris($route);
    }

    /**
     * Track the matched route.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  \Illuminate\Http\Request   $request
     *
     * @return int
     */
    public function track(Route $route, Request $request)
    {
        return $this->trackRoutePath(
            $route, $request, $this->trackRoute($route)
        );
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Track the current route.
     *
     * @param  \Illuminate\Routing\Route  $route
     *
     * @return int
     */
    private function trackRoute(Route $route)
    {
        return $this->getModel()->newQuery()->firstOrCreate([
            'name'   => $this->getRouteName($route),
            'action' => $route->getActionName(),
        ])->getKey();
    }

    /**
     * Check if the route is ignored by a route pattern.
     *
     * @param  \Illuminate\Routing\Route  $route
     *
     * @return bool
     */
    private function isInIgnoredRouteNames(Route $route)
    {
        return $this->checkPatterns(
            $route->getName(), $this->getConfig('routes.ignore.names', [])
        );
    }

    /**
     * Check if the route is ignored by a route pattern.
     *
     * @param  \Illuminate\Routing\Route  $route
     *
     * @return bool
     */
    private function isInIgnoredUris(Route $route)
    {
        return $this->checkPatterns(
            $route->uri(), $this->getConfig('routes.ignore.uris', [])
        );
    }

    /**
     * Check if the value match the given patterns.
     *
     * @param  string|null  $value
     * @param  array        $patterns
     *
     * @return bool
     */
    private function checkPatterns($value, array $patterns)
    {
        foreach ($patterns as $pattern) {
            if (Str::is($pattern, $value)) return true;
        }

        return false;
    }

    /**
     * Get the route name.
     *
     * @param  \Illuminate\Routing\Route  $route
     *
     * @return string
     */
    private function getRouteName(Route $route)
    {
        return is_null($name = $route->getName()) ? $route->uri() : $name;
    }

    /**
     * Track the route path.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  \Illuminate\Http\Request   $request
     * @param  int                        $routeId
     *
     * @return int
     */
    private function trackRoutePath(Route $route, Request $request, $routeId)
    {
        /** @var  \Arcanedev\LaravelTracker\Models\RoutePath  $model */
        $model = $this->makeModel(BindingManager::MODEL_ROUTE_PATH)->firstOrCreate([
            'route_id' => $routeId,
            'path'     => $request->path(),
        ]);

        if ($model->wasRecentlyCreated)
            $this->trackRoutePathParameters($route, $model);

        return $model->getKey();
    }

    /**
     * Track the route path parameters.
     *
     * @param  \Illuminate\Routing\Route                   $route
     * @param  \Arcanedev\LaravelTracker\Models\RoutePath  $routePath
     */
    private function trackRoutePathParameters(Route $route, Models\RoutePath $routePath)
    {
        $parameters = [];

        if ($route->hasParameters()) {
            foreach ($route->parameters() as $parameter => $value) {
                $parameters[] = $this->makeModel(BindingManager::MODEL_ROUTE_PATH_PARAMETER)->fill([
                    'parameter' => $parameter,
                    'value'     => $this->checkIfValueIsEloquentModel($value),
                ]);
            }
        }

        if (count($parameters) > 0)
            $routePath->parameters()->saveMany($parameters);
    }

    /**
     * Check if the value is an eloquent model.
     *
     * @param  mixed  $value
     *
     * @return mixed
     */
    private function checkIfValueIsEloquentModel($value)
    {
        if ($value instanceof EloquentModel) {
            foreach ($this->getConfig('routes.model-columns', ['id']) as $column) {
                if (
                    array_key_exists($column, $attributes = $value->getAttributes()) &&
                    ! is_null($attributes[$column])
                ) {
                    return $attributes[$column];
                }
            }
        }

        return $value;
    }
}
