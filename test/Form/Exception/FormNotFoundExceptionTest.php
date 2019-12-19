<?php
/**
 * @copyright 2017 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\Form\Exception;

use Hostnet\Component\Form\FormHandlerInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\Form\Exception\FormNotFoundException
 */
class FormNotFoundExceptionTest extends TestCase
{
    public function testConstructor(): void
    {
        $handler = $this->createMock(FormHandlerInterface::class);

        $e = new FormNotFoundException($handler);

        self::assertContains(get_class($handler), $e->getMessage());
    }
}
