<?php

namespace Omniconv;

class Client
{
    public $client;
    public $baseUri;

    /**
     * @param string $baseUri
     */
    public function __construct($baseUri = 'http://omniconv.com')
    {
        $this->baseUri = $baseUri;
        $this->client = new \GuzzleHttp\Client(['base_url' => $baseUri]);
    }

    /**
     * @param $toFormat
     * @param $inFile
     * @param null $outFile
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|\GuzzleHttp\Ring\Future\FutureInterface|null
     */
    public function conv($toFormat, $inFile, $outFile=null)
    {
        $response = $this->client->post('/conv', array(
            'body' => array(
                'format' => $toFormat,
                'data' => fopen($inFile, 'r'),
            )
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
            $response = $this->client->get('/ping', ['timeout' => $timeout]);
            return ($response->getStatusCode() == 200);
        } catch (\Exception $e) {
            return false;
        }
    }
}