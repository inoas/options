<?php
namespace OptionBuilder;

use OptionFilte\VoidReturnType;

use ArrayAccess;
use RuntimeException;
use UnexpectedValueException;

abstract class BaseOptionBuilder implements ArrayAccess
{

    protected $name = 'Base';
    protected $options = [];
    protected $validationErrors = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Generic Setter/Getter
     *
     * In the concrete implementations of this class you CAN overwrite all the options fields you want to support
     * to allow IDE auto completition.
     *
     * @return array URL as array
     */
    public function __call($key, $args) 
    {
        // Getter
        if ($key === 'get') {
            return $this->toArray();
        } elseif (strpos($key, 'get') === 0) {
            $key = lcfirst(substr($key, 3));
            if (array_key_exists($key, $this->options)) {
                return $this->options[$key];
            } else {
                return null;
            }
        }

        // Setter / Builder
        if (count($args) === 1) {
            $this->options[$key] = $args[0];
        } else {
            $this->options[$key] = $args;
        }

        return $this;
    }

    public function validateOrFail($schema = null)
    {
        if ($this->_validateOptions($schema) === false) {
            throw new RuntimeException(sprintf(
                "%sOptions cannot be consumed because: %s",
                $this->name, print_r($this->validationErrors, true)));
        }

        return $this;
    }

    protected function addValidationError($key, $msg)
    {
        $this->validationErrors[$key] = $msg;
    }

    abstract protected function _validateOptions($schema = null);

    /**
     * Get the URL as array.
     *
     * @return array URL as array
     */
    public function toArray()
    {
        return $this->options;
    }

    /**
     * Called when whenever checked if an offset exists.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset An offset to check for.
     * @return bool true on success or false on failure.
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->options) === true;
    }

    /**
     * Called when getting an offset key.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $key The offset to retrieve.
     * @return mixed Can return all value types.
     */
    public function offsetGet($key)
    {
        if (array_key_exists($key, $this->options) === false) {
            return new VoidReturnType(sprintf("Offset key '%s' is void.", $key));
        }

        return $this->options[$key];
    }

    /**
     * Called whenever an offset key is being set.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $key The offset to assign the value to.
     * @param mixed $value The value to set.
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->{$key}($value);
    }

    /**
     * Called whenever an offset key is unset.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $key The offset to unset.
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->url[$key]);
    }

    public function __debugInfo()
    {
        return $this->toArray();
    }
}
