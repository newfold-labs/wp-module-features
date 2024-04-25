<?php
namespace NewfoldLabs\WP\Module\Features;

use WP_Forge\Options\Options;

/**
 * Registry for managing features within the plugin.
 */
class Registry {

    /**
     * Name of option
     * 
     * @var string
     */
    private $option_name = 'newfold_features';

    /**
     * Options object
     * See https://github.com/wp-forge/wp-options
     */
    private $options;

    /**
     * Constructor
     */
    public function __construct() {
        $options = new Options($option_name);
    }

    /**
     * Get Options
     */
    public function getOptions() {
        return $this->$options;
    }

    /**
     * Checks if a feature is registered.
     *
     * @param string $name The feature name.
     * @return bool True if registered, false otherwise.
     */
    public function has($name) {
        return $this->$options->has($name);
    }

    /**
     * Registers a feature with the registry.
     *
     * @param string $name The feature name.
     * @param mixed $instance The feature instance.
     */
    public function set($name, $value) {
        $this->$options->set($name, $value);
    }

    /**
     * Retrieves a feature instance by name.
     *
     * @param string $name The feature name.
     * @return mixed|null The feature instance if found, null otherwise.
     */
    public function get($name) {
        return $this->$options->get($name);
    }

    /**
     * Removes a feature from the registry.
     *
     * @param string $name The feature name.
     */
    public function remove($name) {
        return $this->$options->delete($name);
    }

    /**
     * Returns all registered feature names.
     *
     * @return array The list of feature names.
     */
    public function keys() {
        return array_keys($this->all());
    }

    /**
     * Retrieves all registered features and values
     *
     * @return array The list of features.
     */
    public function all() {
        return $this->$options->all();
    }

    /**
     * Resets the registry by clearing all features.
     */
    public function reset() {
        // populate with an empty array
        $this->$options->populate(array());
    }
}