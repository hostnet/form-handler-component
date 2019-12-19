<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler\Fixtures\ActionSubscriber;

use Hostnet\Component\FormHandler\ActionSubscriberInterface;

class HenkSubscriber implements ActionSubscriberInterface
{
    public function getSubscribedActions(): array
    {
        return ['henk' => 'onHenk'];
    }

    public function onHenk(): void
    {
    }
}
