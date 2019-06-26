<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\Form;

@trigger_error(sprintf(
    '%s is deprecated, use %s instead.',
    FormSuccesHandlerInterface::class,
    FormSuccessHandlerInterface::class
), E_USER_DEPRECATED);

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 *
 * @deprecated Use the HandlerConfigInterface instead. Will be removed in version 2.0.
 */
interface FormSuccesHandlerInterface extends FormSuccessHandlerInterface
{
}
