<?php
declare(strict_types=1);

namespace Idx\Token;

use Idx\User;
use Idx\Config;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class BasicToken implements TokenInterface
{

    /** The token api path. */
    const PATH = 'token';

    /** @var GuzzleHttp\Client The GuzzleHttp Client. */
    private $client;

    /** @var Idx\Config The config.. */
    private $config;

    /** @var Idx\User The user. */
    private $user;

    /**
     * Create a new AccessToken handler.
     *
     * @param Client $client The GuzzleHttp\Client.
     * @param User   $user   The user.
     * @param Config $config The config.
     */
    public function __construct(Client $client, User $user, Config $config)
    {
        $this->client = $client;
        $this->user = $user;
        $this->config = $config;
    }

    /**
     * Get access token.
     *
     * @return string The AccessToken
     *
     * @throws Exception|RequestException
     */
    public function getAccessToken()
    {
        $response = $this->client->request(
            'POST',
            $this->config->get('api-endpoint').BasicToken::PATH,
            [
                RequestOptions::HEADERS => [
                    'x-api-key' => $this->config->get('x-api-key')
                ],
                RequestOptions::JSON => [
                    'username' => $this->user->getUserName(),
                    'password' => $this->user->getPassword()
                ]
            ]
        );
        $content = json_decode($response->getBody()->getContents());
        if (json_last_error() === JSON_ERROR_NONE) {
            return $content->accessToken;
        }
        throw new Exception(json_last_error_msg());
    }
}
