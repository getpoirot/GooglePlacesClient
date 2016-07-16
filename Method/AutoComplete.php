<?php
namespace Poirot\GooglePlaceClient;

use Poirot\ApiClient\Request\Method;

class AutoComplete 
    extends Method
{
    /**
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
     * Get Method Name
     *
     * @return string
     */
    function getMethod()
    {
        return 'autocomplete';
    }

    
}
