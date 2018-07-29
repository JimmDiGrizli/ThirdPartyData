<?php

namespace ThirdPartyData\Integration;

use GuzzleHttp\Psr7\Response;

interface DataProviderInterface
{
    /**
     * @param array $input
     * @throws DataProviderException
     * @return Response
     */
    public function get(array $input): Response;
}