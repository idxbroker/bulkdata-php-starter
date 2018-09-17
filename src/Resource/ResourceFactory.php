<?php
declare(strict_types=1);

namespace Idx\Resource;

use Idx\Container;

class ResourceFactory
{

    /** @var string Listing service' name. */
    const LISTING_SERVICE = 'Listing';

    /** @var string Image service' name. */
    const IMAGE_SERVICE = 'Image';

    /** @var string Agent service' name. */
    const AGENT_SERVICE = 'Agent';

    /** @var string Office service' name. */
    const OFFICE_SERVICE = 'Office';

    /** @var string MlsInfo service' name. */
    const MLSINFO_SERVICE = 'MlsInfo';

    /** @var Idx\Container The container. */
    private static $container;

    /**
     * Create bulkdata service object.
     *
     * @param string $service
     *
     * @return mixed
     */
    public static function create(string $service)
    {
        if (is_null(self::$container)) {
            self::$container = Container::getInstance();
        }
        return self::$container->get($service);
    }
}
