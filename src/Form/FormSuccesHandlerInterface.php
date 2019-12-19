<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\Form;

@trigger_error(sprintf(
    '%s is deprecated, use %s instead.',
    FormSuccesHandlerInterface::class,
    FormSuccessHandlerInterface::class
), E_USER_DEPRECATED);

/**
 * @deprecated Use {@see FormSuccessHandlerInterface} instead.
 */
interface FormSuccesHandlerInterface extends FormSuccessHandlerInterface
{
}
