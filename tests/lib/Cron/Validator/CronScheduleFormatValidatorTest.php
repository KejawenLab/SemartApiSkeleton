<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron\Validator;

use Cron\Validator\CrontabValidator;
use KejawenLab\ApiSkeleton\Cron\Validator\CronScheduleFormat;
use KejawenLab\ApiSkeleton\Cron\Validator\CronScheduleFormatValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class CronScheduleFormatValidatorTest extends TestCase
{
    public function testInvalidConstraint(): void
    {
        $validator = new CronScheduleFormatValidator(new CrontabValidator(), $this->createMock(TranslatorInterface::class));

        $constraint = $this->createMock(Constraint::class);

        $this->expectException(UnexpectedTypeException::class);

        $validator->validate('fake', $constraint);
    }

    public function testInvalidValue(): void
    {
        $validator = new CronScheduleFormatValidator(new CrontabValidator(), $this->createMock(TranslatorInterface::class));

        $this->expectException(UnexpectedTypeException::class);

        $validator->validate(1, new CronScheduleFormat());
    }

    public function testValidate(): void
    {
        $validator = new CronScheduleFormatValidator(new CrontabValidator(), $this->createMock(TranslatorInterface::class));

        $validator->validate('* * * * *', new CronScheduleFormat());

        $this->assertSame($validator, $validator);
    }
}
