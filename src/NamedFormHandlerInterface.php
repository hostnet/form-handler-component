<?php
namespace Hostnet\Component\Form;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
interface NamedFormHandlerInterface extends FormHandlerInterface
{
    /**
     * Return the name of the form, by default this can be the form type
     *
     * @return string
     */
    public function getName();
}
