<?php
namespace Hostnet\Component\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
interface FormHandlerInterface
{
    /**
     * @return string|FormTypeInterface
     */
    public function getType();

    /**
     * @return mixed
     * @deprecated deprecated since 1.4 and to be removed in 2.0. Pass data as a parameter into the handle function instead
     */
    public function getData();

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @return FormInterface
     * @deprecated deprecated since 1.4 and to be removed in 2.0. Pass form as a parameter into the handle function instead
     */
    public function getForm();

    /**
     * @param FormInterface $form
     * @deprecated deprecated since 1.4 and to be removed in 2.0. Pass form as a parameter into the handle function instead
     */
    public function setForm(FormInterface $form);
}
