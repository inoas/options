<?php
namespace OptionBuilder;

class OptionFactory
{
    public static function create($builder = 'generic')
    {
        $class = '\OptionBuilder\\' . ucfirst($builder) . 'OptionBuilder';
        
        return new $class(ucfirst($builder));
    }
}
