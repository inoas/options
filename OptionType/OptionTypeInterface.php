<?php
namespace OptionBuilder\OptionType;

interface OptionTypeInterface
{
    public function process($key, $value, &$validationErrors, $castTypes, $cleanInput);
}
