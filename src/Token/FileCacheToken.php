<?php
declare(strict_types=1);

namespace Idx\Token;

use Idx\Config;
use Carbon\Carbon;
use Idx\Token\BasicToken;
use Idx\Token\TokenInterface;

class FileCacheToken implements TokenInterface
{

    /** @var string The cache file path. */
    private $filePath;

    /** @var BasicToken The basic token. */
    private $basicToken;

    /** @var Idx\Config The config. */
    private $config;

    /**
     * Create a new AccessToken handler.
     *
     * @param BasicToken $basicToken The GuzzleHttp\Client.
     *
     * @return void
     */
    public function __construct(BasicToken $basicToken, Config $config)
    {
        $this->basicToken = $basicToken;
        $this->config = $config;
        $this->filePath = $config->get('token-cache-file-path').'/'.$config->get('token-cache-key');
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
        $now = Carbon::now();
        $token = new \stdClass;
        $content = null;

        if (file_exists($this->filePath)) {
            $content = file_get_contents($this->filePath) ?: null;
        }

        if (!is_null($content)) {
            $token = json_decode($content);
            $timestamp = new Carbon($token->timestamp);
            if (json_last_error() == JSON_ERROR_NONE
                && $timestamp->diffInRealMinutes($now) < 51
            ) {
                return $token->accessToken;
            }
        }

        $newToken = $this->basicToken->getAccessToken();
        file_put_contents(
            $this->filePath,
            json_encode(
                [
                    'accessToken' => $newToken,
                    'timestamp' => $now->toDateTimeString()
                ]
            )
        );
        return $newToken;
    }
}
