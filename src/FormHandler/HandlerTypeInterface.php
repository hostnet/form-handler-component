<?php
namespace Hostnet\Component\FormHandler;

/**
 * Implementations for this class allow for configuring of an handler config.
 */
interface HandlerTypeInterface
{
    /**
     * Configure the Handler.
     *
     * @param HandlerConfigInterface $config
     */
    public function configure(HandlerConfigInterface $config);
}
