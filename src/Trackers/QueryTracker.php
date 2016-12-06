<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\QueryTracker as QueryTrackerContract;
use Arcanedev\LaravelTracker\Models\Query;
use Arcanedev\LaravelTracker\Models\QueryArgument;

/**
 * Class     QueryTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class QueryTracker implements QueryTrackerContract
{
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
        if (count($queries) == 0) return null;

        /** @var \Arcanedev\LaravelTracker\Models\Query $query */
        $query = Query::firstOrCreate([
            'query' => $this->prepareArguments($queries)
        ]);

        $this->saveArguments($query, $queries);

        return $query->id;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set the query arguments.
     *
     * @param  \Arcanedev\LaravelTracker\Models\Query  $query
     * @param  array                                   $queries
     *
     * @return array
     */
    private function saveArguments($query, array $queries)
    {
        $arguments = [];

        foreach ($queries as $argument => $value) {
            $arguments[] = new QueryArgument([
                'argument' => $argument,
                'value'    => $this->prepareArguments($value),
            ]);
        }

        return $query->arguments()->saveMany($arguments);
    }

    /**
     * Prepare the query arguments.
     *
     * @param  array|string  $queries
     *
     * @return string
     */
    private function prepareArguments($queries)
    {
        return is_array($queries)
            ? str_replace(['%5B', '%5D'], ['[', ']'], http_build_query($queries, null, '|'))
            : $queries;
    }
}
