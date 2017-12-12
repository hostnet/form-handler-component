<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler\Fixtures\HandlerType;

use Hostnet\Component\FormHandler\Fixtures\TestType;
use Hostnet\Component\FormHandler\HandlerConfigInterface;
use Hostnet\Component\FormHandler\HandlerTypeInterface;

class SimpleFormHandler implements HandlerTypeInterface
{
    public function configure(HandlerConfigInterface $config)
    {
        $config->setType(TestType::class);
    }
}
