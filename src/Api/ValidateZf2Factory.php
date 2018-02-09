<?php

namespace Reliv\ValidationRatZf\Api;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidateZf2Factory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return ValidateZf2
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(
        ContainerInterface $serviceContainer
    ) {
        return new ValidateZf2(
            $serviceContainer,
            ValidateZf2::DEFAULT_INVALID_CODE
        );
    }
}
