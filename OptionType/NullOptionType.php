<?php
namespace OptionBuilder\OptionType;

class NullOptionType implements OptionTypeInterface
{
    public function process($key, $value, &$validationErrors, $castTypes, $cleanInput)
    {
    }
}
