<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler;

/**
 * Implementation of this interface allows for easy registration of Handler
 * Actions.
 *
 * @see HandlerActions
 */
interface ActionSubscriberInterface
{
    /**
     * Returns an array of action names this subscriber wants to listen to.
     *
     * The returned array should contain a flat structure where the key is the
     * action and the value the method name that should be called.
     *
     * For instance:
     *     return [HandlerActions::SUCCESS => 'onSuccess'];
     *
     * @return array The actions names to listen to
     */
    public function getSubscribedActions();
}
