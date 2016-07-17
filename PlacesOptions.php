<?php
namespace Poirot\GooglePlacesClient;

use Poirot\Std\Struct\aDataOptions;

class PlacesOptions
    extends aDataOptions
{
    protected $key;

    
    /**
     * Set Your application's API key
     * @link https://developers.google.com/places/web-service/get-api-key
     *
     * @param string $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = (string) $key;
        return $this;
    }

    /**
     * API Application Key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
}