<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\Form;

use Symfony\Component\HttpFoundation\Request;

interface FormFailureHandlerInterface
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function onFailure(Request $request);
}
