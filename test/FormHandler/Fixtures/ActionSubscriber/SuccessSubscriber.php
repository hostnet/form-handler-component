<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler\Fixtures\ActionSubscriber;

use Hostnet\Component\FormHandler\ActionSubscriberInterface;
use Hostnet\Component\FormHandler\HandlerActions;

class SuccessSubscriber implements ActionSubscriberInterface
{
    public $success = false;
    public $failure = false;

    public function getSubscribedActions()
    {
        return [HandlerActions::SUCCESS => 'onSuccess'];
    }

    public function onSuccess()
    {
        $this->success = true;
    }
}
