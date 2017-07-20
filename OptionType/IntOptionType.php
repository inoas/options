<?php
namespace OptionBuilder\OptionType;

class IntOptionType implements OptionTypeInterface
{
    public function process($key, $value, &$validationErrors, $castTypes, $cleanInput)
    {
        if (is_integer($value)) {
            return $value;
        }
        if ($castTypes === true) {
            if ($cleanInput === true && is_string($value)) {
                $value = trim($value);
            }
            $value = (int)$value;
            return $value;
        }

        $validationErrors[$key] = 'type int expected';
        return $value;
    }
}
