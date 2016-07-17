<?php
namespace Poirot\GooglePlacesClient\Method;

use Poirot\ApiClient\Request\Method;

class AutoComplete 
    extends Method
{
    /** @link https://developers.google.com/places/web-service/autocomplete#place_types */
    const TYPE_GEOCODE = 'geocode';
    const TYPE_ADDRESS = 'address';
    const TYPE_ESTABLISHMENT = 'establishment';
    const TYPE_REGIONS = 'regions';
    const TYPE_CITIES = 'cities';


    protected $input;
    protected $types;
    protected $location;
    protected $radius;
    protected $offset;
    protected $language;
    protected $components = array();

    /**
     * @override restrict method name change
     *
     * Set Method Name
     *
     * @param string $method Method Name
     *
     * @return $this
     * @throws \Exception
     */
    function setMethod($method)
    {
        throw new \Exception('Method change not allowed.');
    }

    /**
     * @override restrict method name change
     *
     * Get Method Name
     *
     * @return string
     */
    function getMethod()
    {
        return 'autocomplete';
    }

    /**
     * @override options as arguments
     *
     * Get Method Arguments
     *
     * - we can define default arguments with some
     *   values
     *
     * @return array
     */
    function getArguments()
    {
        $args = $this->args;

        $merged = array();

        $input      = $this->getInput();
        $types      = $this->getTypes();
        $location   = $this->getLocation();
        $radius     = $this->getRadius();
        $offset     = $this->getOffset();
        $language   = $this->getLanguage();
        $components = $this->getComponents();

        ($input    === null) ?: $merged['input']    = $input;
        ($types    === null) ?: $merged['types']    = $types;
        ($location === null) ?: $merged['location'] = $location;
        ($radius   === null) ?: $merged['radius']   = $radius;
        ($offset   === null) ?: $merged['offset']   = $offset;
        ($language === null) ?: $merged['language'] = $language;
        (empty($components)) ?: $merged['components'] = $components;

        $args = array_merge($args, $merged);
        return $args;
    }

    // options:

    /**
     * The text string on which to search.
     * The Place Autocomplete service will return candidate-
     * matches based on this string and order results based-
     * on their perceived relevance
     *
     * @param string $input
     * @return $this
     */
    function setInput($input)
    {
        $input = (string) $input;
        if ($input == '')
            throw new \InvalidArgumentException('Input string is empty.');

        $this->input = $input;
        return $this;
    }

    /**
     * @return string|null
     */
    function getInput()
    {
        return $this->input;
    }

    /**
     * The types of place results to return.
     * if no type is specified, all types will be returned.
     *
     * @param string $types
     *
     * @return $this
     */
    function setTypes($types)
    {
        $this->types = (string) $types;
        return $this;
    }

    /**
     * Get Types
     * @return array
     */
    function getTypes()
    {
        return $this->types;
    }

    /**
     * The point around which you wish to retrieve place information.
     * must be specified as [latitude, longitude]
     *
     * @param array $location
     *
     * @return $this
     */
    function setLocation(array $location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * Get Location as [latitude, longitude]
     * @return array
     */
    function getLocation()
    {
        return $this->location;
    }

    /**
     * The distance (in meters) within which to return place results.
     *
     * @param int $radius
     *
     * @return $this
     */
    function setRadius($radius)
    {
        $this->radius = $radius;
        return $this;
    }

    /**
     * Get Radius
     * @return int|null
     */
    function getRadius()
    {
        return $this->radius;
    }

    /**
     * The input term is 'Google abc' and the offset is 3,
     * the service will attempt to match against 'Goo abc'.
     *
     * @param int $offset
     *
     * @return $this
     */
    function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return int|null
     */
    function getOffset()
    {
        return $this->offset;
    }

    /**
     * The language code, indicating in which language
     * the results should be returned, if possible.
     * @link https://developers.google.com/maps/faq#languagesupport
     *
     * @param string $language
     *
     * @return $this
     */
    function setLanguage($language)
    {
        $this->language = (string) $language;
        return $this;
    }

    /**
     * Get Language
     * @return string|null
     */
    function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param array $components
     * @return $this
     */
    function setComponents(array $components)
    {
        $this->components = array_merge($this->components, $components);
        return $this;
    }

    /**
     * Get Components
     * @return array
     */
    function getComponents()
    {
        return $this->components;
    }
}
