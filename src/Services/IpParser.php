<?php

namespace Peresmishnyk\Task\Services;

use GuzzleHttp\Client;

class IpParser
{
    protected $client;
    protected $config;

    public function __construct(string $config_key)
    {
        $this->client = new Client();
        $this->config = config($config_key);
    }

    public function getContinentByIp(string $ip)
    {
        $res = $this->client->request('GET', $this->config['endpoint'] . $ip, [
            'query' => $this->config['query']
        ]);

        if ($res->getStatusCode() == 200) {
            $data = json_decode($res->getBody(), true);
            return $data['continent_code'] ?? null;
        }
        return null;
    }
}