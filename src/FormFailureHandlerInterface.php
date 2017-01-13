<?php
namespace Hostnet\Component\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
interface FormFailureHandlerInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function onFailure(Request $request, FormInterface $form);
}
