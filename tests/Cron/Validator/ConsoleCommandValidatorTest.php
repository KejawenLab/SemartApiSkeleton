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

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class ConsoleCommandValidatorTest extends KernelTestCase
{

    public function testInvalidConstraint(): void
    {
        $kernel = $this->createMock(KernelInterface::class);

        $validator = new ConsoleCommandValidator($kernel);

        $constraint = $this->createMock(Constraint::class);

        $this->expectException(UnexpectedTypeException::class);

        $validator->validate('fake', $constraint);
    }

    public function testInvalidValue(): void
    {
        $kernel = $this->createMock(KernelInterface::class);

        $validator = new ConsoleCommandValidator($kernel);

        $this->expectException(UnexpectedTypeException::class);

        $validator->validate('fake', new ConsoleCommand());
    }

    public function testValidate(): void
    {
        $kernel = self::bootKernel();

        $validator = new ConsoleCommandValidator($kernel);

        $cron = $this->createMock(CronInterface::class);
        $cron->expects($this->once())->method('isSymfonyCommand')->willReturn(false);

        $validator->validate($cron, new ConsoleCommand());

        $cron = $this->createMock(CronInterface::class);
        $cron->expects($this->once())->method('isSymfonyCommand')->willReturn(true);
        $cron->expects($this->once())->method('getCommand')->willReturn('debug:router');

        $validator->validate($cron, new ConsoleCommand());
    }
}
