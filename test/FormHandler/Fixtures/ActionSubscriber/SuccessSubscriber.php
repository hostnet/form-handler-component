<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler\Fixtures\ActionSubscriber;

use Hostnet\Component\FormHandler\ActionSubscriberInterface;
use Hostnet\Component\FormHandler\HandlerActions;

class SuccessSubscriber implements ActionSubscriberInterface
{
    public $success = false;
    public $failure = false;

    public function getSubscribedActions(): array
    {
        return [HandlerActions::SUCCESS => 'onSuccess'];
    }

    public function onSuccess(): void
    {
        $this->success = true;
    }
}
