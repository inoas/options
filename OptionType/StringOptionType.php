<?php
namespace OptionBuilder\OptionType;

class StringOptionType implements OptionTypeInterface
{
    public function process($key, $value, &$validationErrors, $castTypes, $cleanInput)
    {
        if (is_string($value)) {
            if ($cleanInput === true) {
                $value = trim($value);
            }
            return $value;
        }
        if ($castTypes === true) {
            $value = (string)$value;
            if ($cleanInput === true) {
                $value = trim($value);
            }
            return $value;
        }

        $validationErrors[$key] = 'type string expected';
        return $value;
    }
    
    public function oneOf($enum)
    {
        return;
    }
}
