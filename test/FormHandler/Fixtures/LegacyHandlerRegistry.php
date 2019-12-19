<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler\Fixtures;

use Hostnet\Component\FormHandler\Exception\InvalidHandlerTypeException;
use Hostnet\Component\FormHandler\HandlerRegistryInterface;
use Hostnet\Component\FormHandler\HandlerTypeAdapter;
use Hostnet\Component\FormHandler\HandlerTypeInterface;

final class LegacyHandlerRegistry implements HandlerRegistryInterface
{
    private $handlers;

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * {@inheritdoc}
     */
    public function getType($class): HandlerTypeInterface
    {
        foreach ($this->handlers as $handler) {
            if ($handler instanceof $class) {
                return $handler;
            }
            if ($handler instanceof HandlerTypeAdapter && $handler->getHandlerClass() === $class) {
                return $handler;
            }
        }

        throw new InvalidHandlerTypeException($class);
    }
}
