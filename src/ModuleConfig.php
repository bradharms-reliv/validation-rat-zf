<?php

namespace Reliv\ValidationRatZf;

use Reliv\ValidationRatZf\Api\ValidateZf;
use Reliv\ValidationRatZf\Api\ValidateZf2Factory;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    /**
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    ValidateZf::class => [
                        'factory' => ValidateZf2Factory::class,
                    ],
                ],
            ],
        ];
    }
}
