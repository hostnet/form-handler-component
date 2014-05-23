<?php
namespace Hostnet\Component\Form\Simple;

use Hostnet\Component\Form\FormSuccesHandlerInterface;
use Hostnet\Component\Form\FormFailureHandlerInterface;
use Hostnet\Component\Form\FormHandlerInterface;
use Hostnet\Component\Form\FormProviderInterface;
use Symfony\Component\Form\FormFactoryInterface;

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
     * @see \Hostnet\Component\Form\FormProviderInterface::handle()
     */
    public function handle(Request $request, FormHandlerInterface $handler)
    {
        $form = $this->form_factory->create($handler->getType(), $handler->getData(), $handler->getOptions());
        $form->handleRequest($request);

        $handler->setForm($form);

        if (!$form->isSubmitted()) {
            return;
        }

        if ($form->isValid()) {
            if ($handler instanceof FormSuccesHandlerInterface) {
                return $handler->onSuccess();
            }
        } elseif ($handler instanceof FormFailureHandlerInterface) {
            return $handler->onFailure();
        }
    }
}
