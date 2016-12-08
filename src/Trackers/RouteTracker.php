<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\RouteTracker as RouteTrackerContract;
use Arcanedev\LaravelTracker\Models;
use Arcanedev\LaravelTracker\Models\AbstractModel;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class     RouteTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouteTracker extends AbstractTracker implements RouteTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters and Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the model.
     *
     * @return \Arcanedev\LaravelTracker\Models\Route
     */
    protected function getModel()
    {
        return $this->makeModel(AbstractModel::MODEL_ROUTE);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if the route is trackable.
     *
     * @param  \Illuminate\Routing\Route $route
     *
     * @return bool
     */
    public function isTrackable($route)
    {
        return ! $this->isInIgnoredRouteNames($route);
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

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
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
        return $this->getModel()
                    ->firstOrCreate([
                        'name'   => $this->getRouteName($route),
                        'action' => $route->getActionName(),
                    ])->id;
    }

    /**
     * Check if the route is ignored by a route pattern.
     *
     * @param  \Illuminate\Routing\Route  $route
     *
     * @return bool
     */
    private function isInIgnoredRouteNames($route)
    {
        if (
            ! is_null($name  = $route->getName()) &&
            count($names = $this->getConfig('routes.ignore.names', [])) > 0
        ) {
            foreach ($names as $pattern) {
                if (Str::is($pattern, $name)) return true;
            }
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
        if ($name = $route->getName())
            return $name;

        if ($name = Arr::get($route->getAction(), 'as'))
            return $name;

        return $route->getUri();
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
        $model = $this->makeModel(AbstractModel::MODEL_ROUTE_PATH)->firstOrCreate([
            'route_id' => $routeId,
            'path'     => $request->path(),
        ]);

        if ($model->wasRecentlyCreated)
            $this->trackRoutePathParameters($route, $model);

        return $model->id;
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

        if (count($params = $route->parameters()) > 0) {
            foreach ($params as $parameter => $value) {
                $parameters[] = $this->makeModel(AbstractModel::MODEL_ROUTE_PATH_PARAMETER)->fill([
                    'parameter' => $parameter,
                    'value'     => $this->checkIfValueIsEloquentModel($value),
                ]);
            }

            $routePath->parameters()->saveMany($parameters);
        }
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
        if ($value instanceof \Illuminate\Database\Eloquent\Model) {
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
