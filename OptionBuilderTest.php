<?php
    spl_autoload_extensions('.php'); // comma-separated list
    spl_autoload_register();

use OptionBuilder\OptionFactory;
use OptionBuilder\OptionSchemaBuilder as os;
function opt($builder = 'generic') { return OptionFactory::create($builder); }

function test1($options) {
    $schema = os::dict()
        ->required('maxItems', os::int())
        ->optional('category', os::string())
        ->required('minRank', os::or(os::float(), os::int()))
        ->required('link', os::dict()
            ->optional('controller', os::string())
            ->required('action', os::string())
            ->optional(0, os::or(os::int(), os::uuid()))
        )
        ->required('filters', os::string()->oneOf('onSale', 'EOL'))
        ->default('showArchived', os::bool(), true);
    $options->validateOrFail($schema);

    return $options;
}

function test2($options) {
    $schema = os::dict()
        ->optional('controller', os::string())
        ->required('action', os::string())
        ->optional('param', os::or(os::float(), os::uuid()))
        ->optional('q', os::dict()
            ->optional('show', os::string()->oneOf('recent', 'old'))
        );
    $options->validateOrFail($schema);

    return $options;
}

$options1 = ['controller' => 'Articles', 'action' => 'view', 5, 'q' => ['show' => 'recent']];
$options2 = opt('url')->controller('Articles')->action('view')->param(5)->q(['show' => 'recent']);
$options3 = opt('url')->controller('Articles')->param(5)->q(['show' => 'recent']);
$options4 = opt()->controller('Articles')->action('view')->param(5)->q(['show' => 'recent'])->foo('bar');
$options5 = opt()->controller('Articles')->action('view')->param(5)->q(['show' => 'recent']);

echo '<pre>';
var_dump(test2($options5));