<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\RouteTracker as RouteTrackerContract;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Arcanedev\LaravelTracker\Models;

/**
 * Class     RouteTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RouteTracker implements RouteTrackerContract
{
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
        if ($this->isInIgnoredRouteNames($route)) return false;

        return true;
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
            $route,
            $request,
            $this->trackRoute($route)
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
        $model = Models\Route::firstOrCreate([
            'name'   => $this->getRouteName($route),
            'action' => $route->getActionName(),
        ]);

        return $model->id;
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
            count($names = config('laravel-tracker.routes.ignore.names', [])) > 0
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
        $model = Models\RoutePath::firstOrCreate([
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
                $parameters[] = new Models\RoutePathParameter([
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
            foreach (config('laravel-tracker.routes.model-columns', ['id']) as $column) {
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
