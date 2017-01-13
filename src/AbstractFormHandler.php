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
     * @deprecated since 1.4 and to be removed in 2.0.
     */
    private $form;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    public function setForm(FormInterface $form)
    {
        $this->form = $form;

        return $this;
    }
}
