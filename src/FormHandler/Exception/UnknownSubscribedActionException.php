<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
namespace Hostnet\Component\FormHandler\Exception;

/**
 * Exception thrown when trying to subscribe on an unknown action.
 */
class UnknownSubscribedActionException extends \LogicException
{
    /**
     * @param string          $class
     * @param array|string[]  $extra
     * @param \Exception|null $previous
     */
    public function __construct($class, array $extra, \Exception $previous = null)
    {
        parent::__construct(sprintf(
            'Unknown subscribed action(s) "%s" given by "%s".',
            implode('", "', $extra),
            $class
        ), 0, $previous);
    }
}
