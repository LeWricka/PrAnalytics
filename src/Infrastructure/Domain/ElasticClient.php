<?php

namespace App\Infrastructure\Domain;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Psr\Container\ContainerInterface;

class ElasticClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->client = ClientBuilder::create()
            ->setHosts([$container->getParameter('elasticsearch_url')])
            ->build();
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function index(array $parameters): array
    {
        return $this->client->index($parameters);
    }

    /**
     * @param string $index
     *
     * @return bool
     */
    public function exists(string $index): bool
    {
        return $this->client->indices()->exists(['index' => $index]);
    }

    /**
     * @param string $index
     */
    public function create(string $index)
    {
        $this->client->indices()->create(['index' => $index]);
    }
}
