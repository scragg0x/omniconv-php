<?php

namespace Omniconv;

class Client
{
    public $client;

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        $defaults = array(
            'base_url' => 'http://omniconv.com'
        );

        $options = array_replace($defaults, $options);

        $this->client = new \GuzzleHttp\Client($options);
    }

    /**
     * @param $toFormat
     * @param $inFile
     * @param string $outFile
     * @param int $timeout
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function conv($toFormat, $inFile, $outFile=null, $timeout=0)
    {
        $response = $this->client->post('/conv', array(
            'body' => array(
                'format' => $toFormat,
                'data' => fopen($inFile, 'r'),
            ),
            'timeout' => $timeout
        ));

        if ($outFile) {
            file_put_contents($outFile, $response->getBody());
        }

        return $response;
    }

    /**
     * @param int $timeout milliseconds
     * @return bool
     */
    public function isUp($timeout=1000)
    {
        try {
            $response = $this->client->get('/ping', array('timeout' => $timeout));
            return ($response->getStatusCode() == 200);
        } catch (\Exception $e) {
            return false;
        }
    }
}