<?php
namespace OptionBuilder;

use InvalidArgumentException;
use OptionBuilder\GenericOptionBuilder;

/**
 * URL Object
 */
class UrlOptionBuilder extends GenericOptionBuilder
{
    /**
     * Absolute URL
     *
     * @var bool
     */
    protected $absolute;

    protected function _validateOptions($schema = null)
    {
        $options = parent::_validateOptions($schema);
        
        // // TODO/FIXME Replace this with magic:
        // $validationErrors = [];
        // if (array_key_exists('action', $this->options) === false) {
        //     $validationErrors['action'] = 'required';
        // }
        // $allowedKeys = ['controller', 'action', 'params', 'q'];
        // $existingKeys = array_keys($this->options);
        // 
        // if (array_intersect($allowedKeys, $existingKeys) !== $existingKeys) {
        //     $unexpectedKeys = array_diff($existingKeys, $allowedKeys);
        //     foreach ($unexpectedKeys as $key) {
        //         $validationErrors[$key] = 'not expected';
        //     }
        // }

        return $options;
    }

    public function param($value)
    {
        $this->options['params'][] = $value;

        return $this;
    }

    public function q(array $queryParam)
    {
        $this->options['q'] = array_merge((array)$this->getQ(), $queryParam);

        return $this;
    }

    public function qs(array $queryParams)
    {
        $this->options['q'] = $queryParams;

        return $this;
    }

    /**
     * Set the object to generate an absolute or relative URL
     *
     * @param bool $absolute Generate an absolute URL or not, default is true
     * @return $this
     */
    public function absolute($absolute = true)
    {
        $this->absolute = (bool)$absolute;

        return $this;
    }

    /**
     * Returns the URL as string
     *
     * @return string URL as string value
     */
    public function toString()
    {
        return Router::url($this->options, $this->absolute);
    }

    /**
     * To string
     *
     * @return string String URL
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Get the URL as array.
     *
     * @return array URL as array
     */
    public function toArray()
    {
        $options = parent::toArray($this->options);
        $options = array_merge($options, $options['params']);
        unset($options['params']);

        return $options;
    }

}
