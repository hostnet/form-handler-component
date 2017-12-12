<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler;

use Hostnet\Component\FormHandler\Exception\UnknownSubscribedActionException;

/**
 * Implementations of the config allow for configuring the handler information.
 * This information contains items like the type, options and onSuccess
 * callables.
 */
interface HandlerConfigInterface
{
    /**
     * Type to use for creating the form. This is usually a class name of a
     * form type.
     *
     * @param string $type
     */
    public function setType($type);

    /**
     * Name of the form, this is used to create a named form.
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Options to use when creating the form. This can be an array or a
     * callable. The callable will receive one parameter, the bind data
     * instance.
     *
     * Note: this method is called before form submission, so the data contains
     * the bind data from your controller. NOT the submitted data.
     *
     * @param array|callable $options
     */
    public function setOptions($options);

    /**
     * Register the on success action.
     *
     * @param callable $callback
     */
    public function onSuccess(callable $callback);

    /**
     * Register the on failure action.
     *
     * @param callable $callback
     */
    public function onFailure(callable $callback);

    /**
     * Register the FormSubmitProcessor.
     *
     * This will overwrite the default FormSubmitProcessor.
     *
     * signature:
     * function (FormInterface $form, Request $request): void;
     *
     * @param callable $callback
     */
    public function setFormSubmitProcessor(callable $callback);

    /**
     * Register an action subscriber. If an unknown action is given, a
     * UnknownSubscribedActionException is thrown.
     *
     * Note: this method will override any success or failure actions that
     * were previously defined.
     *
     * @param ActionSubscriberInterface $action_subscriber
     * @throws UnknownSubscribedActionException
     */
    public function registerActionSubscriber(ActionSubscriberInterface $action_subscriber);
}
