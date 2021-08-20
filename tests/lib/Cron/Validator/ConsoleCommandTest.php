<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron\Validator;

use KejawenLab\ApiSkeleton\Cron\Validator\ConsoleCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class ConsoleCommandTest extends TestCase
{
    public function testGetMessage(): void
    {
        $this->assertSame('sas.validator.cron.command_not_found', (new ConsoleCommand())->getMessage());
    }

    public function testGetTargets(): void
    {
        $this->assertSame(Constraint::CLASS_CONSTRAINT, (new ConsoleCommand())->getTargets());
    }
}
