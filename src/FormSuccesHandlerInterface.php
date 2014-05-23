<?php
namespace Hostnet\Component\Form;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
interface FormSuccesHandlerInterface
{
    /**
     * @return mixed
     */
    public function onSuccess();
}
