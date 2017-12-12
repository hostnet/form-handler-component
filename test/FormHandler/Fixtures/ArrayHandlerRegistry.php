<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler\Fixtures;

use Hostnet\Component\FormHandler\Exception\InvalidHandlerTypeException;
use Hostnet\Component\FormHandler\HandlerRegistryInterface;

final class ArrayHandlerRegistry implements HandlerRegistryInterface
{
    private $handlers;

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * {@inheritdoc}
     */
    public function getType($class)
    {
        foreach ($this->handlers as $handler) {
            if ($handler instanceof $class) {
                return $handler;
            }
        }

        throw new InvalidHandlerTypeException($class);
    }
}
