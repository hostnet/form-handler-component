<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler\Fixtures\HandlerType;

use Hostnet\Component\FormHandler\Fixtures\TestType;
use Hostnet\Component\FormHandler\HandlerConfigInterface;
use Hostnet\Component\FormHandler\HandlerTypeInterface;

class SimpleFormHandler implements HandlerTypeInterface
{
    public function configure(HandlerConfigInterface $config): void
    {
        $config->setType(TestType::class);
    }
}
