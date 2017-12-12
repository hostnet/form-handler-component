<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler\Fixtures\ActionSubscriber;

use Hostnet\Component\FormHandler\ActionSubscriberInterface;

class HenkSubscriber implements ActionSubscriberInterface
{
    public function getSubscribedActions()
    {
        return ['henk' => 'onHenk'];
    }

    public function onHenk()
    {
    }
}
