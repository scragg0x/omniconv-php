<?php

namespace Omniconv;

class Client
{
    public $client;
    public $baseUri;

    public function __construct($baseUri = 'http://omniconv.com')
    {
        $this->client = new \GuzzleHttp\Client(['base_url' => $baseUri]);
    }

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
}