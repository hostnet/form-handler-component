<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler;

use Symfony\Component\Form\FormFactoryInterface;

/**
 * Factory for creating handlers.
 */
final class HandlerFactory implements HandlerFactoryInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $form_factory;

    /**
     * @var HandlerRegistryInterface
     */
    private $registry;

    /**
     * @param FormFactoryInterface     $form_factory
     * @param HandlerRegistryInterface $registry
     */
    public function __construct(FormFactoryInterface $form_factory, HandlerRegistryInterface $registry)
    {
        $this->form_factory = $form_factory;
        $this->registry     = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function create($class)
    {
        return new Handler($this->form_factory, $this->registry->getType($class));
    }
}
