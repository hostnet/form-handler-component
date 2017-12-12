<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler\Exception;

/**
 * Exception thrown when non-existing handler was requests.
 */
class InvalidHandlerTypeException extends \InvalidArgumentException
{
    /**
     * @param string          $name
     * @param \Exception|null $previous
     */
    public function __construct($name, \Exception $previous = null)
    {
        parent::__construct(sprintf('No handler found for class "%s".', $name), 0, $previous);
    }
}
