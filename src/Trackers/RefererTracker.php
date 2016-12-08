<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Parsers\RefererParser;
use Arcanedev\LaravelTracker\Contracts\Trackers\RefererTracker as RefererTrackerContract;
use Arcanedev\LaravelTracker\Models\Domain;
use Arcanedev\LaravelTracker\Models\Referer;
use Arcanedev\LaravelTracker\Models\RefererSearchTerm;
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
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
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

            $referer = Referer::firstOrCreate(
                Arr::only($attributes, ['url', 'search_terms_hash']),
                $attributes
            );

            if ($secondParsed->isKnown()) {
                $this->trackRefererSearchTerms($referer->id, $secondParsed->getSearchTerm());
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
        $data = compact('name');

        return Domain::firstOrCreate($data, $data)->id;
    }

    /**
     * Store the referer's search terms.
     *
     * @param  int     $refererId
     * @param  string  $searchTerms
     */
    private function trackRefererSearchTerms($refererId, $searchTerms)
    {
        foreach (explode(' ', $searchTerms) as $term) {
            $attributes = [
                'referer_id'  => $refererId,
                'search_term' => $term,
            ];

            RefererSearchTerm::firstOrCreate($attributes, $attributes);
        }
    }
}
