<?php
declare(strict_types=1);

namespace Idx\Resource;

use Idx\Config;
use GuzzleHttp\Client;
use Idx\Resource\GetTrait;
use Idx\Token\TokenInterface as Token;

class Office
{
    use GetTrait;

    /** The offices api path. */
    const PATH = 'offices';

    /** @var GuzzleHttp\Client The GuzzleHttp Client. */
    private $client;

    /** @var Idx\Config The config. */
    private $config;

    /** @var Idx\Token\TokenInterface Tgghe Token. */
    private $token;

    /**
     * Office.
     *
     * @param Client $client The GuzzleHttp\Client.
     * @param Token  $token  The token.
     * @param Config $config The config.
     */
    public function __construct(Client $client, Token $token, Config $config)
    {
        $this->client = $client;
        $this->token = $token;
        $this->config = $config;
    }
}
