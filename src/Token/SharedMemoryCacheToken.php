<?php
declare(strict_types=1);

namespace Idx\Token;

use Idx\Config;
use Carbon\Carbon;
use Idx\Token\BasicToken;
use Idx\Token\TokenInterface;

class SharedMemoryCacheToken implements TokenInterface
{
    /** @var integer The shmSize. */
    const SHM_SIZE = 2048;

    /** @var string The shmKey. */
    private $shmKey;

    /** @var Idx\Toekn\BasicToken The basic token. */
    private $basicToken;

    /**
     * Create a new AccessToken handler.
     *
     * @param Idx\Token\BasicToken $basicToken The GuzzleHttp\Client.
     *
     * @return void
     */
    public function __construct(BasicToken $basicToken)
    {
        $this->basicToken = $basicToken;
        $this->shmKey = ftok(__FILE__, 't');
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

        $shmId = shmop_open($this->shmKey, 'c', 0644, SharedMemoryCacheToken::SHM_SIZE);

        if ($shmId !== false) {
            $content = shmop_read($shmId, 0, SharedMemoryCacheToken::SHM_SIZE) ?: null;
        }

        if (!is_null($content)) {
            $token = json_decode(trim($content));
            $timestamp = $token ? new Carbon($token->timestamp) : null;
            if (json_last_error() == JSON_ERROR_NONE
                && $timestamp
                && $timestamp->diffInRealMinutes($now) < 51
            ) {
                return $token->accessToken;
            }

        }

        $newToken = $this->basicToken->getAccessToken();
        shmop_write(
            $shmId,
            json_encode(
                [
                    'accessToken' => $newToken,
                    'timestamp' => $now->toDateTimeString()
                ]
            ),
            0
        );
        return $newToken;
    }
}
