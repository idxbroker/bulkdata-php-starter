<?php
declare(strict_types=1);

namespace Idx;

class Config
{

    /** @var array The settings. */
    private $settings;

    /**
     * Create a new Config instance.
     *
     * @param string $filename The settings file path.
     */
    public function __construct(string $filename = './settings.json')
    {
        $this->settings = json_decode(file_get_contents($filename), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid settings file. Error: '.json_last_error_msg());
        }
    }

    /**
     * Dynamically access container services.
     *
     * @param  string  $key
     * @return mixed
     */
    public function get($name)
    {
        if (isset($this->settings[$name])) {
            return $this->settings[$name];
        }
        return null;
    }
}
