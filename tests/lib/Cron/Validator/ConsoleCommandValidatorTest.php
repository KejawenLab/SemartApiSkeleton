<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron\Validator;

use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use KejawenLab\ApiSkeleton\Cron\Validator\ConsoleCommand;
use KejawenLab\ApiSkeleton\Cron\Validator\ConsoleCommandValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class ConsoleCommandValidatorTest extends KernelTestCase
{

    public function testInvalidConstraint(): void
    {
        $kernel = $this->createMock(KernelInterface::class);
        $translator = $this->createMock(TranslatorInterface::class);

        $validator = new ConsoleCommandValidator($kernel, $translator);

        $constraint = $this->createMock(Constraint::class);

        $this->expectException(UnexpectedTypeException::class);

        $validator->validate('fake', $constraint);
    }

    public function testInvalidValue(): void
    {
        $kernel = $this->createMock(KernelInterface::class);
        $translator = $this->createMock(TranslatorInterface::class);

        $validator = new ConsoleCommandValidator($kernel, $translator);

        $this->expectException(UnexpectedTypeException::class);

        $validator->validate('fake', new ConsoleCommand());
    }

    public function testValidate(): void
    {
        $kernel = self::bootKernel();
        $translator = $this->createMock(TranslatorInterface::class);

        $validator = new ConsoleCommandValidator($kernel, $translator);

        $cron = $this->createMock(CronInterface::class);
        $cron->expects($this->once())->method('isSymfonyCommand')->willReturn(false);

        $validator->validate($cron, new ConsoleCommand());

        $cron = $this->createMock(CronInterface::class);
        $cron->expects($this->once())->method('isSymfonyCommand')->willReturn(true);
        $cron->expects($this->once())->method('getCommand')->willReturn('debug:router');

        $validator->validate($cron, new ConsoleCommand());
    }
}
