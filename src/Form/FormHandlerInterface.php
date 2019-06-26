<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\Form;

use Hostnet\Component\FormHandler\HandlerTypeInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

@trigger_error(sprintf(
   '%s is deprecated, use %s instead.',
   FormHandlerInterface::class,
   HandlerTypeInterface::class
), E_USER_DEPRECATED);

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 *
 * @deprecated Use the HandlerTypeInterface instead. Will be removed in version 2.0.
 */
interface FormHandlerInterface
{
    /**
     * @return string|FormTypeInterface
     */
    public function getType();

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @return FormInterface
     */
    public function getForm();

    /**
     * @param FormInterface $form
     */
    public function setForm(FormInterface $form);
}
