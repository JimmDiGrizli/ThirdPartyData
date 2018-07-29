<?php

namespace ThirdPartyData\Integration;

use GuzzleHttp\Psr7\Response;

class DataProvider implements DataProviderInterface
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var null|string
     */
    private $user;

    /**
     * @var null|string
     */
    private $password;


    /**
     * Decorator constructor.
     * @param string $host
     * @param null|string $user
     * @param null|string $password
     */
    public function __construct(string $host, ?string $user, ?string $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @inheritdoc
     */
    public function get(array $input): Response
    {
        // returns a response from external service
        return new Response();
    }
}
