<?php
namespace Hostnet\Component\FormHandler;

use Hostnet\Component\FormHandler\Exception\UnknownSubscribedActionException;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Allows for configuring and creating HandlerInterface instances.
 */
final class HandlerBuilder implements HandlerConfigInterface
{
    private $type = FormType::class;
    private $name;
    private $options = [];
    private $on_success;
    private $on_failure;

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function onSuccess(callable $callback)
    {
        $this->on_success = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function onFailure(callable $callback)
    {
        $this->on_failure = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function registerActionSubscriber(ActionSubscriberInterface $action_subscriber)
    {
        $subscribed_actions = $action_subscriber->getSubscribedActions();
        $diff               = array_diff(
            array_keys($subscribed_actions),
            [HandlerActions::SUCCESS, HandlerActions::FAILURE]
        );

        if (count($diff) > 0) {
            throw new UnknownSubscribedActionException(get_class($action_subscriber), $diff);
        }

        $actions = array_merge(
            [HandlerActions::SUCCESS => null, HandlerActions::FAILURE => null],
            $subscribed_actions
        );

        $this->on_success = $actions[HandlerActions::SUCCESS]
            ? [$action_subscriber, $actions[HandlerActions::SUCCESS]]
            : null;

        $this->on_failure = $actions[HandlerActions::FAILURE]
            ? [$action_subscriber, $actions[HandlerActions::FAILURE]]
            : null;
    }

    /**
     * Create a handler which is able to process the request.
     *
     * @param FormFactoryInterface $form_factory
     * @param mixed                $data
     * @return FormSubmitProcessor
     */
    public function build(FormFactoryInterface $form_factory, $data = null)
    {
        $options = is_callable($this->options) ? call_user_func($this->options, $data) : $this->options;

        if (null !== $this->name) {
            $form = $form_factory->createNamed($this->name, $this->type, $data, $options);
        } else {
            $form = $form_factory->create($this->type, $data, $options);
        }

        return new FormSubmitProcessor($form, $this->on_success, $this->on_failure);
    }
}
