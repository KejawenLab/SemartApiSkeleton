<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Tests\Cron\Validator;

use KejawenLab\ApiSkeleton\Cron\Validator\ConsoleCommand;
use PHPUnit\Framework\TestCase;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
class ConsoleCommandTest extends TestCase
{
    public function testGetMessage(): void
    {
        $this->assertSame('sas.validator.command.not_found', (new ConsoleCommand())->getMessage());
    }

    public function testGetTargets(): void
    {
        $this->assertSame(ConsoleCommand::CLASS_CONSTRAINT, (new ConsoleCommand())->getTargets());
    }
}
