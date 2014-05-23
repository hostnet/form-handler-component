<?php
namespace Hostnet\Component\Form;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Iltar van der Berg <ivanderberg@hostnet.nl>
 * @author Yannick de Lange <ydelange@hostnet.nl>
 */
interface FormProviderInterface
{
    /**
     * @param Request              $request
     * @param FormHandlerInterface $handler
     */
    public function handle(Request $request, FormHandlerInterface $handler);
}
