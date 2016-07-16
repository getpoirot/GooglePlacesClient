<?php
namespace Poirot\GooglePlaceClient;

use Poirot\ApiClient\aClient;
use Poirot\ApiClient\Interfaces\iPlatform;

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
            $this->platform = new Platform();
        
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
