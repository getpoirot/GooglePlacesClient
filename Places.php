<?php
namespace Poirot\GooglePlaceClient;

use Poirot\ApiClient\aClient;
use Poirot\ApiClient\Interfaces\iPlatform;

use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\Connection\Http\ConnectionHttpSocket;
use Poirot\Connection\Interfaces\iConnection;

use Poirot\Std\Interfaces\Pact\ipOptionsProvider;
use Poirot\Std\Interfaces\Struct\iDataOptions;

class Places
    extends aClient
    implements ipOptionsProvider
{
    /** @var Platform */
    protected $platform;
    /** @var ConnectionHttpSocket */
    protected $transporter;
    /** @var PlacesOptions */
    protected $options;


    /**
     * Places Construct
     * @param array|\Traversable $options
     */
    function __construct($options = null)
    {
        if ($options != null)
            $this->optsData()->import($options);
    }

    // ...

    /**
     * Get place predictions.
     *
     * The service can be used to provide autocomplete functionality
     * for text-based geographic searches, by returning places such
     * as businesses, addresses and points of interest as a user types.
     *
     * @param string       $input    The text string on which to search
     * @param string       $types    The types of place results to return.
     *                               if no type is specified, all types will be returned.
     *                               in general only a single type is allowed.
     * @param array        $location The point around which you wish to retrieve place information.
     *                               must be specified as [latitude, longitude]
     * @param int          $radius   The distance (in meters) within which to return place results.
     * @param int          $offset   The input term is 'Google abc' and the offset is 3,
     *                               the service will attempt to match against 'Goo abc'.
     *                               if no offset is supplied, the service will use the whole term.
     * @param string       $language The language code, indicating in which language
     *                               the results should be returned, if possible.
     *                               @link https://developers.google.com/maps/faq#languagesupport
     * @param string       $country The country must be passed as a two character,
     *                              ISO 3166-1 Alpha-2 compatible country code.
     *                              exp. fr
     *
     * @return iResponse
     */
    function getAutocomplete($input
        , $types = null
        , $location = null
        , $radius = null
        , $offset = null
        , $language = null
        , $country = null
    ) {
        $method = new AutoComplete();
        $method->setInput($input);
        
        ($types    === null) ?: $method->setTypes($types);
        ($location === null) ?: $method->setLocation($location);
        ($radius   === null) ?: $method->setRadius($radius);
        ($offset   === null) ?: $method->setOffset($offset);
        ($language === null) ?: $method->setLanguage($language);
        ($country  === null) ?: $method->setComponents(array('country' => (string) $country));

        return $this->call($method);
    }

    // ...

    /**
     * Get Client Platform
     *
     * - used by request to build params for
     *   server execution call and response
     *
     * @return Platform|iPlatform
     */
    function platform()
    {
        if (!$this->platform)
            $this->platform = new Platform($this);
        
        return $this->platform;
    }

    /**
     * Get Transporter Adapter
     *
     * @return ConnectionHttpSocket|iConnection
     */
    function transporter()
    {
        if (!$this->transporter)
            $this->transporter = new ConnectionHttpSocket;

        return $this->transporter;
    }

    
    // ...
    
    /**
     * @return PlacesOptions|iDataOptions
     */
    function optsData()
    {
        if (!$this->options)
            $this->options = self::newOptsData();
        
        return $this->options;
    }

    /**
     * @inheritdoc
     * @return PlacesOptions|iDataOptions
     */
    static function newOptsData($builder = null)
    {
        $options = new PlacesOptions;
        if ($builder !== null)
            $options->import($builder);
        
        return $options;
    }
}
