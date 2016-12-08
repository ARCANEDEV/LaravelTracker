<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Parsers\RefererParser;
use Arcanedev\LaravelTracker\Contracts\Trackers\RefererTracker as RefererTrackerContract;
use Arcanedev\LaravelTracker\Models\AbstractModel;
use Illuminate\Support\Arr;

/**
 * Class     RefererTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RefererTracker extends AbstractTracker implements RefererTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters and Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the model.
     *
     * @return \Arcanedev\LaravelTracker\Models\Referer
     */
    protected function getModel()
    {
        return $this->makeModel(AbstractModel::MODEL_REFERER);
    }

    /**
     * @return \Arcanedev\LaravelTracker\Contracts\Parsers\RefererParser
     */
    private function getRefererParser()
    {
        return $this->make(RefererParser::class);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
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
            $domainId   = $this->trackDomain($firstParsed['domain']);
            $attributes = [
                'url'               => $firstParsed['url'],
                'host'              => $firstParsed['host'],
                'domain_id'         => $domainId,
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

            $referer = $this->getModel()->firstOrCreate(
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
        return $this->makeModel(AbstractModel::MODEL_DOMAIN)
                    ->firstOrCreate(compact('name'))->id;
    }

    /**
     * Store the referer's search terms.
     *
     * @param  \Arcanedev\LaravelTracker\Models\Referer     $referer
     * @param  string                                       $searchTerms
     */
    private function trackRefererSearchTerms($referer, $searchTerms)
    {
        $terms = [];

        foreach (explode(' ', $searchTerms) as $term) {
            $terms[] = $this->makeModel(AbstractModel::MODEL_REFERER_SEARCH_TERM)->fill([
                'search_term' => $term,
            ]);
        }

        if (count($terms) > 0)
            $referer->searchTerms()->saveMany($terms);

    }
}
