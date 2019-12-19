<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\Form\Simple;

use Hostnet\Component\Form\Exception\FormNotFoundException;
use Hostnet\Component\Form\FormFailureHandlerInterface;
use Hostnet\Component\Form\FormHandlerInterface;
use Hostnet\Component\Form\FormProviderInterface;
use Hostnet\Component\Form\FormSuccessHandlerInterface;
use Hostnet\Component\Form\NamedFormHandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SimpleFormProvider implements FormProviderInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $form_factory;

    /**
     * @param FormFactoryInterface $form_factory
     */
    public function __construct(FormFactoryInterface $form_factory)
    {
        $this->form_factory = $form_factory;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, FormHandlerInterface $handler, FormInterface $form = null)
    {
        if (null !== $form) {
            $handler->setForm($form);
        } elseif (null === ($form = $handler->getForm())) {
            if ($handler instanceof NamedFormHandlerInterface && null !== ($name = $handler->getName())) {
                $form = $this->form_factory->createNamed(
                    $name,
                    $handler->getType(),
                    $handler->getData(),
                    $handler->getOptions()
                );
            } else {
                $form = $this->form_factory->create(
                    $handler->getType(),
                    $handler->getData(),
                    $handler->getOptions()
                );
            }

            if (!$form instanceof FormInterface) {
                throw new FormNotFoundException($handler);
            }

            // set the form which is associated with the handler
            $handler->setForm($form);
        }

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return null;
        }

        if ($form->isValid()) {
            if ($handler instanceof FormSuccessHandlerInterface) {
                return $handler->onSuccess($request);
            }
        } elseif ($handler instanceof FormFailureHandlerInterface) {
            return $handler->onFailure($request);
        }
    }
}
