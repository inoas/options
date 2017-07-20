<?php
namespace OptionBuilder;

class OptionSchemaBuilder
{
    protected $keys = [];
    protected $coexist = [];
    protected $acceptOther = false;
    protected $castTypes = false;
    protected $cleanInput = false;

    public function __construct($acceptOther, $castTypes, $cleanInput) {
        $this->acceptOther = $acceptOther;
        $this->castTypes = $castTypes;
        $this->cleanInput = $cleanInput;
    }

    public static function dict($acceptOther = false, $castTypes = false, $cleanInput = false) {
        return new self($acceptOther, $castTypes, $cleanInput);
    }

    public function validate($options, &$validationErrors)
    {
        $validationErrors = [];
        $returnOptions = $options;
        foreach ($this->keys as $key => $flags) {
            if (array_key_exists('default', $flags) && !array_key_exists($key, $options)) {
                $returnOptions[$key] = $flags['default'];
            }
            if (array_key_exists($key, $options)) {
                if (is_a($flags['type'], '\OptionBuilder\OptionSchemaBuilder')) {
                    // TODO sub schema here
                } else {
                    $returnOptions[$key] = $flags['type']->process(
                        $key, $options[$key], $validationErrors, $this->castTypes, $this->cleanInput);
                }
                unset($options[$key]);
            } elseif ($flags['required'] === true) {
                $validationErrors[$key] = 'required'; // setting reference here
            }
        }
        if ($this->acceptOther === false) {
            foreach (array_keys($options) as $unexpectedOption) {
                $validationErrors[$unexpectedOption] = 'unexpected'; // setting reference here
            }
        }
        if (count($validationErrors) > 0) {
            return false;
        }

        return $returnOptions;
    }

    public function required($key, $optionType)
    {
        $this->keys[$key] = [
            'type' => $optionType,
            'required' => true,
        ];

        return $this;
    }

    public function default($key, $optionType, $default)
    {
        $this->keys[$key] = [
            'type' => $optionType,
            'required' => false,
            'default' => $default,
        ];

        return $this;        
    }

    public function optional($key, $optionType)
    {
        $this->keys[$key] = [
            'type' => $optionType,
            'required' => false,
        ];

        return $this;
    }

    public function coexist($keys)
    {
        $this->coexist[] = $keys;

        return $this;
    }

    public static function or()
    {
        return new OptionType\OrOptionType;
    }

    public static function null()
    {
        return new OptionType\NullOptionType;
    }

    public static function string()
    {
        return new OptionType\StringOptionType;
    }

    public static function bool()
    {
        return new OptionType\BoolOptionType;
    }

    public static function int()
    {
        return new OptionType\IntOptionType;
    }

    public static function float()
    {
        return new OptionType\FloatOptionType;
    }

    public static function uuid()
    {
        return new OptionType\UuidOptionType;
    }
}
