<?php
/**
 * @file
 * Contains a Vultan service.
 */

namespace Drupal\vultan\Service;

use Vultan\Vultan;

/**
 * Class VultanService
 *
 * @package Drupal\vultan\Service
 */
class Hawkmen {

  /**
   * A flight of Vultans.
   *
   * @var array
   */
  protected $hawkmen;

  /**
   * config
   *
   * @var \Vultan\Config
   */
  protected $config;

  /**
   * settings
   *
   * @var array
   */
  protected $settings;

  /**
   * Constructor.
   */
  public function __construct() {

    // Load the base settings from the Config API.
    $this->settings = \Drupal::config('vultan')->getRawData();
  }

  /**
   * Start a connection.
   *
   * @param string $alias
   *   (optional) Alias to use. Defaults to the localhost default specified in
   *   the modules default config.
   */
  public function initialise($alias = 'default') {

    $settings = $this->getSettings($alias);

    $this->config->setDb($this->extractValue($settings, 'database'));
    $this->config->setHost($this->extractValue($settings, 'host', 'localhost'));
    $this->config->setPass($this->extractValue($settings, 'port', '27017'));
    $this->config->setUser($this->extractValue($settings, 'user'));
    $this->config->setUser($this->extractValue($settings, 'pass'));
    $this->config->setUser($this->extractValue($settings, 'options'));

    $hawkman = Vultan::init($this->config);

    $this->setHawkman($alias, $hawkman);
  }

  /**
   * Connect to, and return, the Vultan MongoDB database.
   *
   * @param string $alias
   *   (optional) Alias to connect to. Defaults to the localhost default
   *   specified in the modules default config.
   *
   * @return \Vultan\Vultan\Database
   *   A Vultan database to work with.
   */
  public function connect($alias = 'default') {

    return $this->getHawkman($alias)->connect();
  }

  /**
   * Set the value for Hawkmen.
   *
   * @param string $name
   *   Alias name for the hawkman
   * @param Vultan $hawkman
   *   A Vultan instance.
   */
  public function setHawkman($name, Vultan $hawkman) {

    $this->hawkmen[$name] = $hawkman;
  }

  /**
   * Get the value for a Hawkman.
   *
   * @param string $name
   *   Name of the hawkman to give orders to.
   *
   * @return Vultan
   *   The value of Hawkmen.
   */
  public function getHawkman($name) {

    if (isset($this->hawkmen[$name])) {
      return $this->hawkmen[$name];
    }
  }

  /**
   * Retrieve settings for a Vultan alias.
   *
   * @param string $alias
   *   The Settings alias.
   *
   * @throws \Exception
   * @return array
   *   An array of settings.
   */
  public function getSettings($alias = 'default') {

    if (isset($this->settings[$alias])) {
      return $this->settings[$alias];
    }

    throw new \Exception('Invalid alias called for a Vultan MongoDB setting');
  }

  /**
   * Extract a value from an array, or use a default.
   *
   * @param array $settings
   *   The settings array to extract the value from.
   * @param string $key
   *   The config value to use.
   * @param mixed|null $default
   *   The default value.
   *
   * @return mixed|null
   *   Value of the key or default.
   */
  protected function extractValue(array $settings, $key, $default = NULL) {

    if (isset($settings[$key])) {
      return $settings[$key];
    }

    return $default;
  }

}
