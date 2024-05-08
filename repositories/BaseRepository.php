<?php

namespace Initbiz\Newsletter\Repositories;

use GuzzleHttp\ClientInterface;

class BaseRepository
{
    /**
     * Guzzle client
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * base URI of the endpoint
     *
     * @var string
     */
    protected $baseUri;

    public function __construct(string $baseUri, ?ClientInterface $client = null)
    {
        $this->setBaseUri($baseUri);

        if (is_null($client)) {
            $client = new \GuzzleHttp\Client();
        }

        $this->setClient($client);
    }

    /**
     * Get baseUri
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * Set baseUri
     */
    public function setBaseUri($baseUri): self
    {
        $this->baseUri = $baseUri;
        return $this;
    }

    /**
     * Get client
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * Set client
     */
    public function setClient($client): self
    {
        $this->client = $client;
        return $this;
    }

    public function get(string $uri, array $headers = [])
    {
        $client = $this->getClient();
        $options = [
            'headers' => $headers
        ];

        $response = $client->request('GET', $this->getBaseUri() . $uri, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function post(string $uri, array $body = [], array $headers = [])
    {
        $client = $this->getClient();
        $options = [
            'headers' => $headers,
            'form_params' => $body,
        ];

        $response = $client->request('POST', $this->getBaseUri() . $uri, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function patch(string $uri, array $body = [], array $headers = [])
    {
        $client = $this->getClient();
        $options = [
            'headers' => $headers,
            'body' => json_encode($body),
        ];

        $response = $client->request('PATCH', $this->getBaseUri() . $uri, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function delete(string $uri, array $headers = [])
    {
        $client = $this->getClient();
        $options = [
            'headers' => $headers,
        ];

        $response = $client->request('DELETE', $this->getBaseUri() . $uri, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function json(string $uri, array $body = [], array $headers = [])
    {
        $headers += [
            'Content-Type' => 'application/json'
        ];

        $client = $this->getClient();
        $options = [
            'headers' => $headers,
            'json' => $body,
        ];

        $response = $client->request('POST', $this->getBaseUri() . $uri, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function put(string $uri, array $body = [], array $headers = [])
    {
        $headers += [
            'Content-Type' => 'application/json'
        ];

        $client = $this->getClient();
        $options = [
            'headers' => $headers,
            'json' => $body,
        ];

        $response = $client->request('PUT', $this->getBaseUri() . $uri, $options);

        return json_decode($response->getBody()->getContents(), true);
    }
}
