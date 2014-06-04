<?php
namespace Hostnet\Component\Form;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
interface FormSuccesHandlerInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function onSuccess(Request $request);
}
