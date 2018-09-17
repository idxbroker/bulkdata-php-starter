<?php
declare(strict_types=1);

namespace Idx;

/**
 * Class User
 * @author Charlie Chang <icharlie.osu@gmail.com>
 */
class User
{

    /** @var string The username. */
    private $username;

    /** @var string The password. */
    private $password;

    /**
     * @param string $username The username.
     * @param string $password The password.
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
