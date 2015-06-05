<?php

namespace Omniconv;

class Client
{
    public $client;
    public $key;

    /**
     * @param string $key
     * @param array $options
     */
    public function __construct($key = null, $options = array())
    {
        $this->key = $key;

        $defaults = array(
            'base_url' => 'http://omniconv.com'
        );

        $options = array_replace_recursive($defaults, $options);

        $this->client = new \GuzzleHttp\Client($options);
    }

    /**
     * @param $toFormat
     * @param $inFile
     * @param string $outFile
     * @param array $requestOptions
     * @param bool $returnRequest
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function conv($toFormat, $inFile, $outFile = null, $requestOptions = array(), $returnRequest = false)
    {
        $defaults = array(
            'headers' => array('X-Omniconv-Key', $this->key),
            'body' => array(
                'format' => $toFormat,
                'data' => fopen($inFile, 'r'),
            )
        );

        $requestOptions = array_replace_recursive($defaults, $requestOptions);

        $request = $this->client->createRequest('POST', '/conv', $requestOptions);

        if ($returnRequest) {
            return $request;
        }

        $response = $this->client->send($request);

        if ($outFile) {
            file_put_contents($outFile, $response->getBody());
        }

        return $response;
    }

    /**
     * @param int $timeout milliseconds
     * @return bool
     */
    public function isUp($timeout = 2000)
    {
        try {
            $response = $this->client->get('/ping', array('timeout' => $timeout));

            return ($response->getStatusCode() == 200);
        } catch (\Exception $e) {
            return false;
        }
    }
}