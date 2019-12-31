<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\FormHandler;

use Hostnet\Component\FormHandler\Exception\InvalidHandlerTypeException;

/**
 * Implementations of this interface allow for fetching a handler type based on
 * a class name.
 */
interface HandlerRegistryInterface
{
    /**
     * Return the HandlerType for a given class name. If none was found a
     * InvalidHandlerTypeException is thrown.
     *
     * @param string $class
     * @return HandlerTypeInterface
     * @throws InvalidHandlerTypeException
     */
    public function getType($class);
}
