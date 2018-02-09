<?php

namespace Reliv\ValidationRatZf\Api;

use Reliv\ArrayProperties\Property;
use Reliv\ValidationRat\Model\ValidationResult;
use Reliv\ValidationRat\Model\ValidationResultBasic;
use Zend\Validator\ValidatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidateZf3 extends ValidateZf2 implements ValidateZf
{
    /**
     * @param ValidatorInterface $validator
     * @param                    $value
     * @param array              $options
     *
     * @return ValidationResult
     */
    protected function validate(
        ValidatorInterface $validator,
        $value,
        array $options = []
    ): ValidationResult {
        $name = Property::getString(
            $options,
            static::OPTION_NAME,
            'default'
        );
        $valid = $validator->isValid($value);

        // @todo ZF3 Validators may return a result object, deal with the result here

        $messages = $validator->getMessages();

        $result = new ValidationResultBasic(
            $valid,
            $this->buildCode($valid, $messages),
            [
                'zf2' => true,
                'messages-zf2' => $messages,
                'name' => $name,
                'validator-zf2' => get_class($validator),
            ]
        );

        return $result;
    }
}
