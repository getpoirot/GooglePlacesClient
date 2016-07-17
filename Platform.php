<?php
namespace Poirot\GooglePlacesClient;

use Poirot\ApiClient\ResponseOfClient;
use Poirot\Connection\Http\ConnectionHttpSocket;
use Poirot\Connection\Http\StreamFilter\DechunkFilter;
use Poirot\Connection\Interfaces\iConnection;
use Poirot\Stream\Interfaces\iStreamable;
use Poirot\ApiClient\Interfaces\iPlatform;
use Poirot\ApiClient\Interfaces\Request\iApiMethod;
use Poirot\ApiClient\Interfaces\Response\iResponse;
use Poirot\Stream\Streamable\STemporary;

class Platform 
    implements iPlatform
{
    /** @var Places */
    protected $_client;

    protected $server = 'https://maps.googleapis.com/maps/api/place';


    /**
     * Platform constructor.
     * @param Places $places
     */
    function __construct(Places $places)
    {
        $this->_client = $places;
    }

    /**
     * Prepare Transporter To Make Call
     *
     * - validate transporter
     * - manipulate header or something in transporter
     * - get connect to resource
     *
     * @param iConnection $transporter
     * @param iApiMethod|null  $method
     *
     * @throws \Exception
     * @return iConnection
     */
    function prepareTransporter(iConnection $transporter, $method = null)
    {
        if (!$transporter instanceof ConnectionHttpSocket)
            // Nothing to do!!
            return $transporter;


        $transporter->optsData()->setServerAddress($this->server);
        $transporter->optsData()->setTimeout(30);
        $transporter->optsData()->setPersist(true);
        return $transporter;
    }

    /**
     * Build Platform Specific Expression To Send
     * Trough Transporter
     *
     * @param iApiMethod $method Method Interface
     *
     * @return mixed
     */
    function makeExpression(iApiMethod $method)
    {
        ## build request object
        $serverUrl  = $this->server;
        $parsSrvUrl = parse_url($serverUrl);

        $key = $this->_client->optsData()->getKey();

        $path    = (isset($parsSrvUrl['path'])) ? ltrim($parsSrvUrl['path'], '/') : '';
        $host    = strtolower($parsSrvUrl['host']);


        $args    = $method->getArguments();
        if (isset($args['components'])) {
            // components=country:fr
            $components = array();
            foreach ($args['components'] as $k => $v)
                $components[] = $k.':'.$v;

            $args['components'] = implode('|', $components);
        }

        $qparams = '?'.http_build_query(array_merge(array('key'=>$key), $args), null, '&');


        $request = 'GET /'. $path. $this->__getRequestUriByMethodName($method->getMethod()).$qparams. ' HTTP/1.1'."\r\n"
            . 'Host: '.$host."\r\n"
            . 'User-Agent: GooglePlaces-Poirot-PHP/'.PHP_VERSION."\r\n"
            ### enable compression if has enabled
//            . (($this->client->optsData()->isEnableCompression()) ? 'Accept-Encoding: gzip'."\r\n" : '')
            ### post method need request header with Content-Length
//            . 'Content-Length: '.strlen($body)."\r\n"
            . "\r\n"
//            . $body
        ;

        return $request;
    }

    /**
     * Build Response Object From Server Result
     *
     * - Result must be compatible with platform
     * - Throw exceptions if response has error
     *
     * @param \StdClass $response Server Result {s:header, s:body}
     *
     * @throws \Exception
     * @return iResponse
     */
    function makeResponse($response)
    {
        $parsedHeader = \Poirot\Connection\Http\parseResponseHeaders($response->header);

        if ($parsedHeader['status'] !== 200)
            // handle errors
            VOID;

        # filter body content
        /** @var iStreamable $stream */
        $stream = $response->body->rewind();

        if (
            isset($parsedHeader['headers']['Transfer-Encoding'])
            && $parsedHeader['headers']['Transfer-Encoding'] == 'chunked'
        ) {
            // Response Body Contain Compressed Data and Must Decompressed.
            // We are using stream deflate filter
            $stream->resource()->prependFilter(DechunkFilter::factory(), STREAM_FILTER_READ);
        }

        if (
            isset($parsedHeader['headers']['Content-Encoding'])
            && $parsedHeader['headers']['Content-Encoding'] == 'gzip'
        ) {
            $stream = new STemporary(gzinflate(substr($stream->read(), 10)));
            $stream->rewind();
        }

        # make response:

        $body = $stream->read();

        kd($body);

        // TODO handle exceptions

        $response  = new ResponseOfClient([
            'meta'     => Util::parseResponseHeaders($response->header),
            'raw_body' => $body,

            ## get response message as array
            'default_expected' => function($xmlString) use ($parsedRes) {
                return $parsedRes['Body'];
            }
        ]);

        return $response;
    }


    // ...

    /**
     * Get specific uri on server for a method call
     * @param string $methodName
     * @return string
     */
    protected function __getRequestUriByMethodName($methodName)
    {
        // https://maps.googleapis.com/maps/api/place[/autocomplete/json?parameters]
        $methodName = rtrim(strtolower($methodName), '/');
        $uri        = '/'.$methodName.'/json';

        return $uri;
    }
}
