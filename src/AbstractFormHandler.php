<?php
namespace Hostnet\Component\Form;

use Symfony\Component\Form\FormInterface;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
abstract class AbstractFormHandler implements NamedFormHandlerInterface
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @see \Hostnet\Component\Form\NamedFormHandlerInterface::getName()
     */
    public function getName()
    {
        return null;
    }

    /**
     * @see \Hostnet\Component\Form\FormHandlerInterface::getOptions()
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @see \Hostnet\Component\Form\FormHandlerInterface::getForm()
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @see \Hostnet\Component\Form\FormHandlerInterface::setForm()
     */
    public function setForm(FormInterface $form)
    {
        $this->form = $form;

        return $this;
    }
}
