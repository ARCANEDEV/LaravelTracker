<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Parsers\RefererParser;
use Arcanedev\LaravelTracker\Contracts\Trackers\RefererTracker as RefererTrackerContract;
use Arcanedev\LaravelTracker\Support\BindingManager;
use Illuminate\Support\Arr;

/**
 * Class     RefererTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RefererTracker extends AbstractTracker implements RefererTrackerContract
{
    /* -----------------------------------------------------------------
     |  Getters and Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the model.
     *
     * @return \Arcanedev\LaravelTracker\Models\Referer
     */
    protected function getModel()
    {
        return $this->makeModel(BindingManager::MODEL_REFERER);
    }

    /**
     * @return \Arcanedev\LaravelTracker\Contracts\Parsers\RefererParser
     */
    private function getRefererParser()
    {
        return $this->make(RefererParser::class);
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Track the referer and return the id.
     *
     * @param  string  $refererUrl
     * @param  string  $pageUrl
     *
     * @return int|null
     */
    public function track($refererUrl, $pageUrl)
    {
        $firstParsed = $this->getRefererParser()->parseUrl($refererUrl);

        if ($firstParsed) {
            $attributes = [
                'url'               => $firstParsed['url'],
                'host'              => $firstParsed['host'],
                'domain_id'         => $this->trackDomain($firstParsed['domain']),
                'medium'            => null,
                'source'            => null,
                'search_terms_hash' => null,
            ];

            $secondParsed = $this->getRefererParser()->parse($firstParsed['url'], $pageUrl);

            if ($secondParsed->isKnown()) {
                $attributes['medium']            = $secondParsed->getMedium();
                $attributes['source']            = $secondParsed->getSource();
                $attributes['search_terms_hash'] = sha1($secondParsed->getSearchTerm());
            }

            /** @var  \Arcanedev\LaravelTracker\Models\Referer  $referer */
            $referer = $this->getModel()->newQuery()->firstOrCreate(
                Arr::only($attributes, ['url', 'search_terms_hash']),
                $attributes
            );

            if ($secondParsed->isKnown()) {
                $this->trackRefererSearchTerms($referer, $secondParsed->getSearchTerm());
            }

            return $referer->id;
        }

        return null;
    }

    /**
     * Track the domain and get the id.
     *
     * @param  string  $name
     *
     * @return int
     */
    private function trackDomain($name)
    {
        return $this->makeModel(BindingManager::MODEL_DOMAIN)
            ->newQuery()
            ->firstOrCreate(compact('name'))
            ->getKey();
    }

    /**
     * Store the referer's search terms.
     *
     * @param  \Arcanedev\LaravelTracker\Contracts\Models\Referer  $referer
     * @param  string                                              $searchTerms
     */
    private function trackRefererSearchTerms($referer, $searchTerms)
    {
        $terms = [];

        foreach (explode(' ', $searchTerms) as $term) {
            $terms[] = $this->makeModel(BindingManager::MODEL_REFERER_SEARCH_TERM)->fill([
                'search_term' => $term,
            ]);
        }

        if (count($terms) > 0)
            $referer->searchTerms()->saveMany($terms);

    }
}
