<?php
declare(strict_types=1);

namespace Idx\Token;

use Idx\Container;
use Idx\Token\BasicToken;
use Idx\Token\FileCacheToken;
use Idx\Token\SharedMemoryCacheToken;

class TokenFactory
{

    /** @var string Shared memory store key. */
    const SHARED_MEMORY_STORE = 'shared-memory';

    /** @var string File store key. */
    const FILE_STORE = 'file';

    /** @var Idx\Container The container. */
    private static $container;

    /**
     * Create token serivce.
     *
     * @return Idx\TokenInterface
     */
    public static function createToken()
    {
        if (is_null(self::$container)) {
            self::$container = Container::getInstance();
        }

        $config = self::$container->get('Config');
        if ($config->get('cache-store') === self::SHARED_MEMORY_STORE && extension_loaded('sysvshm')) {
            return self::$container->get(SharedMemoryCacheToken::class);
        }
        return self::$container->get(FileCacheToken::class);
    }
}
