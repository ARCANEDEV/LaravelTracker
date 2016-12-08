<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\QueryTracker as QueryTrackerContract;
use Arcanedev\LaravelTracker\Models\AbstractModel;
use Illuminate\Support\Arr;

/**
 * Class     QueryTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class QueryTracker extends AbstractTracker implements QueryTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters and Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the model.
     *
     * @return \Arcanedev\LaravelTracker\Models\Query
     */
    protected function getModel()
    {
        return $this->makeModel(AbstractModel::MODEL_QUERY);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the query.
     *
     * @param  array  $queries
     *
     * @return int|null
     */
    public function track(array $queries)
    {
        if (count($queries) == 0)
            return null;

        $data = [
            'query'     => $this->prepareQuery($queries),
            'arguments' => $this->prepareArguments($queries),
        ];

        return $this->getModel()
            ->firstOrCreate(Arr::only($data, ['query']), $data)->id;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Prepare the query.
     *
     * @param  array|string  $queries
     *
     * @return string
     */
    public function prepareQuery($queries)
    {
        return is_array($queries)
            ? str_replace(['%5B', '%5D'], ['[', ']'], http_build_query($queries, null, '|'))
            : $queries;
    }

    /**
     * Prepare the arguments.
     *
     * @param  array  $queries
     *
     * @return array
     */
    public function prepareArguments(array $queries)
    {
        $arguments = [];

        foreach ($queries as $key => $value) {
            $arguments[$key] = $this->prepareQuery($value);
        }

        return $arguments;
    }
}
