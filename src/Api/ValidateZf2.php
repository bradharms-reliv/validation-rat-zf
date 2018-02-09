<?php

namespace Reliv\ValidationRatZf\Api;

use Psr\Container\ContainerInterface;
use Reliv\ArrayProperties\Property;
use Reliv\ValidationRat\Model\ValidationResult;
use Reliv\ValidationRat\Model\ValidationResultBasic;
use Zend\Validator\ValidatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidateZf2 implements ValidateZf
{
    protected $serviceContainer;
    protected $defaultInvalidCode;

    /**
     * @param ContainerInterface $serviceContainer
     * @param string             $defaultInvalidCode
     */
    public function __construct(
        ContainerInterface $serviceContainer,
        string $defaultInvalidCode = self::DEFAULT_INVALID_CODE
    ) {
        $this->serviceContainer = $serviceContainer;
        $this->defaultInvalidCode = $defaultInvalidCode;
    }

    /**
     * @param mixed $value
     * @param array $options
     *
     * @return ValidationResult
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Throwable
     * @throws \Reliv\ArrayProperties\Exception\ArrayPropertyException
     */
    public function __invoke(
        $value,
        array $options = []
    ): ValidationResult {
        $validator = $this->getValidator(
            $options
        );

        return $this->validate(
            $validator,
            $value,
            $options
        );
    }

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

    /**
     * @param array $options
     *
     * @return ValidatorInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Throwable
     * @throws \Reliv\ArrayProperties\Exception\ArrayPropertyException
     */
    protected function getValidator(
        array $options
    ): ValidatorInterface {
        $validator = Property::getRequired(
            $options,
            static::OPTION_ZEND_VALIDATOR
        );

        // NOTE: by default we will assume validator to be a service
        $validatorIsService = Property::getBool(
            $options,
            static::OPTION_ZEND_VALIDATOR_IS_SERVICE,
            true
        );

        $validatorOptions = Property::getArray(
            $options,
            static::OPTION_ZEND_VALIDATOR_OPTIONS,
            []
        );

        if ($validatorIsService) {
            /** @var \Zend\Validator\ValidatorInterface|\Zend\Validator\AbstractValidator $validatorService */
            $validatorService = $this->serviceContainer->get($validator);
            // We clone to deal with ZF2 stateful validators
            $validator = clone($validatorService);
        } else {
            /** @var \Zend\Validator\ValidatorInterface|\Zend\Validator\AbstractValidator $validator */
            $validator = new $validator();
        }

        if (method_exists($validator, 'setOptions')) {
            $validator->setOptions($validatorOptions);
        }

        return $validator;
    }

    /**
     * @param bool  $valid
     * @param array $messages
     *
     * @return string
     */
    protected function buildCode(bool $valid, array $messages)
    {
        if ($valid) {
            return '';
        }

        return $this->defaultInvalidCode;
    }
}
