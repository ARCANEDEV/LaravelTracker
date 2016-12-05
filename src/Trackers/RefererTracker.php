<?php namespace Arcanedev\LaravelTracker\Trackers;

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
class RefererTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The referer parser.
     *
     * @var  \Arcanedev\LaravelTracker\Contracts\Parsers\RefererParser
     */
    protected $parser;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct()
    {
        $this->parser = app(\Arcanedev\LaravelTracker\Contracts\Parsers\RefererParser::class);
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
        $parsed = $this->parser->parseUrl($refererUrl);

        if ($parsed) {
            $domainId   = $this->getDomainId($parsed['domain']);
            $attributes = [
                'url'               => $parsed['url'],
                'host'              => $parsed['host'],
                'domain_id'         => $domainId,
                'medium'            => null,
                'source'            => null,
                'search_terms_hash' => null,
            ];

            $parsed = $this->parser->parse($parsed['url'], $pageUrl);

            if ($parsed->isKnown()) {
                $attributes['medium']            = $parsed->getMedium();
                $attributes['source']            = $parsed->getSource();
                $attributes['search_terms_hash'] = sha1($parsed->getSearchTerm());
            }

            $referer = Referer::firstOrCreate(
                Arr::only($attributes, ['url', 'search_terms_hash']),
                $attributes
            );

            if ($parsed->isKnown()) {
                $this->storeSearchTerms($referer->id, $parsed->getSearchTerm());
            }

            return $referer->id;
        }

        return null;
    }

    /**
     * Get the domain id.
     *
     * @param  string  $name
     *
     * @return int
     */
    private function getDomainId($name)
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
    private function storeSearchTerms($refererId, $searchTerms)
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
