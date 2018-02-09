<?php

namespace Reliv\ValidationRatZf\Api;

use Reliv\ValidationRat\Api\Validator\Validate;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface ValidateZf extends Validate
{
    const OPTION_NAME = 'name';
    const OPTION_ZEND_VALIDATOR = 'zend-validator';
    const OPTION_ZEND_VALIDATOR_IS_SERVICE = 'zend-validator-is-service';
    const OPTION_ZEND_VALIDATOR_OPTIONS = 'zend-validator-options';

    const DEFAULT_INVALID_CODE = 'invalid';
}
