<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler;

use Hostnet\Component\Form\FormFailureHandlerInterface;
use Hostnet\Component\Form\FormHandlerInterface;
use Hostnet\Component\Form\FormSuccessHandlerInterface;
use Hostnet\Component\Form\NamedFormHandlerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Wrapper class around the old FormHandlerInterface classes. This provides the
 * BC layer so they can be used with the new handler factory.
 */
final class HandlerTypeAdapter implements HandlerTypeInterface
{
    /**
     * @var FormHandlerInterface
     */
    private $legacy_handler;

    /**
     * @param FormHandlerInterface $legacy_handler
     */
    public function __construct(FormHandlerInterface $legacy_handler)
    {
        $this->legacy_handler = $legacy_handler;
    }

    /**
     * Return the original class name.
     *
     * @return string
     */
    public function getHandlerClass()
    {
        return get_class($this->legacy_handler);
    }

    /**
     * Make a function call on the old handler and return the results. This is
     * used to preserve backwards compatibility between the old handlers and the new.
     *
     * @return string
     */
    public function delegateCall($name, $arguments)
    {
        return call_user_func_array([$this->legacy_handler, $name], $arguments);
    }

    /**
     * Sync the data from the controller data to the handler data.
     *
     * @param mixed $controller_data
     * @return mixed
     */
    public function syncData($controller_data)
    {
        if (is_object($controller_data)) {
            $handler_data = $this->legacy_handler->getData();

            $refl_class = new \ReflectionClass(get_class($handler_data));
            $filter     = \ReflectionProperty::IS_PUBLIC
                | \ReflectionProperty::IS_PROTECTED
                | \ReflectionProperty::IS_PRIVATE;

            foreach ($refl_class->getProperties($filter) as $property) {
                $property->setAccessible(true);
                $property->setValue($handler_data, $property->getValue($controller_data));
                $property->setAccessible(false);
            }
        }

        return $this->legacy_handler->getData();
    }

    /**
     * {@inheritdoc}
     */
    public function configure(HandlerConfigInterface $config)
    {
        @trigger_error(sprintf(
            'Using %s is deprecated, use %s instead.',
            FormHandlerInterface::class,
            HandlerTypeInterface::class
        ), E_USER_DEPRECATED);

        if ($this->legacy_handler instanceof NamedFormHandlerInterface
            && null !== ($name = $this->legacy_handler->getName())
        ) {
            $config->setName($name);
        }

        $config->setType($this->legacy_handler->getType());
        $config->setOptions($this->legacy_handler->getOptions());

        if ($this->legacy_handler instanceof FormSuccessHandlerInterface) {
            $config->onSuccess(function ($data, FormInterface $form, Request $request) {
                $this->legacy_handler->setForm($form);

                return $this->legacy_handler->onSuccess($request);
            });
        }

        if ($this->legacy_handler instanceof FormFailureHandlerInterface) {
            $config->onFailure(function ($data, FormInterface $form, Request $request) {
                $this->legacy_handler->setForm($form);

                return $this->legacy_handler->onFailure($request);
            });
        }
    }
}
