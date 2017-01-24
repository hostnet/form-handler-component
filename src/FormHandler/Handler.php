<?php
namespace Hostnet\Component\FormHandler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handler wraps around a form and provides a way to handle the form validation
 * results.
 *
 * It will contain the form it belongs to and two callables for processing a
 * valid and an invalid form validation result.
 *
 * @internal Will be removed when upgrading to PHP 7.0 with anonymous class.
 */
final class Handler implements HandlerInterface
{
    /**
     * @var FormSubmitProcessor
     */
    private $submit_processor;

    /**
     * @var FormFactoryInterface
     */
    private $form_factory;

    /**
     * @var HandlerTypeInterface
     */
    private $handler_type;

    /**
     * @param FormFactoryInterface $form_factory
     * @param HandlerTypeInterface $handler_type
     */
    public function __construct(
        FormFactoryInterface $form_factory,
        HandlerTypeInterface $handler_type
    ) {
        $this->form_factory = $form_factory;
        $this->handler_type = $handler_type;
    }

    /**
     * Implemented to preserve backwards compatibility between the old handlers
     * and the new.
     *
     * @param string $name
     * @param array  $arguments
     * @return mixed
     * @throws \BadMethodCallException when called on something else than a HandlerTypeAdapter
     */
    public function __call($name, $arguments)
    {
        if (!$this->handler_type instanceof HandlerTypeAdapter) {
            throw new \BadMethodCallException(sprintf(
                'Attempted to call an undefined method named "%s" of class "%s".',
                $name,
                get_class($this)
            ));
        }

        @trigger_error(sprintf(
            'Delegating call "%s::%s()", please use your data object to transfer data from your controller to the ' .
            'handler.',
            $this->handler_type->getHandlerClass(),
            $name
        ), E_USER_DEPRECATED);

        return $this->handler_type->delegateCall($name, $arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        if (null === $this->submit_processor) {
            throw new \LogicException('Cannot retrieve form when it has not been handled.');
        }

        return $this->submit_processor->getForm();
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $data = null)
    {
        if ($this->handler_type instanceof HandlerTypeAdapter) {
            $data = $this->handler_type->syncData($data);
        }

        $builder = new HandlerBuilder();
        $this->handler_type->configure($builder);

        $this->submit_processor = $builder->build($this->form_factory, $data);

        return $this->submit_processor->process($request);
    }
}
