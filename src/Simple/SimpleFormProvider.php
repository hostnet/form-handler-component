<?php
namespace Hostnet\Component\Form\Simple;

use Hostnet\Component\Form\Exception\FormNotFoundException;
use Hostnet\Component\Form\FormInformationInterface;
use Hostnet\Component\Form\FormFailureHandlerInterface;
use Hostnet\Component\Form\FormProviderInterface;
use Hostnet\Component\Form\FormSuccesHandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
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
     * @see \Hostnet\Component\Form\FormProviderInterface::handle()
     */
    public function handle(Request $request, FormInformationInterface $handler, FormInterface $form = null)
    {
        if (null !== $form) {
            $handler->setForm($form);
        } elseif (null === ($form = $handler->getForm())) {
            throw new FormNotFoundException($handler);
        }

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return;
        }

        if ($form->isValid()) {
            if ($handler instanceof FormSuccesHandlerInterface) {
                return $handler->onSuccess($request);
            }
        } elseif ($handler instanceof FormFailureHandlerInterface) {
            return $handler->onFailure($request);
        }
    }
}
