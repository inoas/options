<?php
namespace OptionBuilder;

use OptionBuilder\BaseOptionBuilder;
use OptionSchemaBuilder as os;


/**
 * URL Object
 */
class GenericOptionBuilder extends BaseOptionBuilder
{
    
    protected function _validateOptions($schema = null)
    {
        if (is_a($schema, '\OptionBuilder\OptionSchemaBuilder')) {
            $validationErrorsByReference;
            $result = $schema->validate($this->options, $validationErrorsByReference);
            $this->validationErrors = $validationErrorsByReference;
            if ($result === false) {
                return false;
            }
            $this->options = $result;
            return true;
        }
    }
}
