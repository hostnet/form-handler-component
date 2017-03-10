<?php
namespace Hostnet\Component\FormHandler;

use Hostnet\Component\FormHandler\Exception\InvalidHandlerTypeException;

/**
 * Implementations for the Handler Factory can create Handlers based on the
 * given class for the HandlerType.
 */
interface HandlerFactoryInterface
{
    /**
     * Create a Handler based on the given class and optionally some data.
     *
     * @param string $class
     * @return HandlerInterface
     * @throws InvalidHandlerTypeException
     */
    public function create($class);
}
