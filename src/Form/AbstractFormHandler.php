<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\Form;

use Symfony\Component\Form\FormInterface;

/**
 * @author Yannick de Lange <ydelange@hostnet.nl>
 *
 * @deprecated Use the HandlerTypeInterface instead. Will be removed in version 2.0.
 */
abstract class AbstractFormHandler implements NamedFormHandlerInterface
{
    /**
     * @var FormInterface
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
