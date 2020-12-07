<?php

namespace App\Tests\Form;

use App\Entity\Task;
use App\Form\AppFormFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactory;

/**
 * @internal
 */
class AppFormFactoryTest extends TestCase
{
    public function testCreateWithBadName(): void
    {
        /** @var FormFactory $formFatory */
        $formFatory = $this
            ->getMockBuilder(FormFactory::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $appFormFactory = new AppFormFactory($formFatory); /* @phpstan-ignore-line */

        $form = $appFormFactory->create('bad name', new Task());
        $this->assertTrue(null === $form);
    }
}
