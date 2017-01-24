<?php
namespace Hostnet\Component\FormHandler\Fixtures\ActionSubscriber;

use Hostnet\Component\FormHandler\ActionSubscriberInterface;
use Hostnet\Component\FormHandler\HandlerActions;

class FailureSubscriber implements ActionSubscriberInterface
{
    public $success = false;
    public $failure = false;

    public function getSubscribedActions()
    {
        return [HandlerActions::FAILURE => 'onFailure'];
    }

    public function onFailure()
    {
        $this->failure = true;
    }
}
