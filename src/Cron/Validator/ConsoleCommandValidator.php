<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Cron\Validator;

use Alpabit\ApiSkeleton\Cron\Model\CronInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class ConsoleCommandValidator extends ConstraintValidator
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ConsoleCommand) {
            throw new UnexpectedTypeException($constraint, ConsoleCommand::class);
        }

        if (!$value instanceof CronInterface) {
            throw new UnexpectedValueException($value, CronInterface::class);
        }

        if (!$value->isSymfonyCommand()) {
            return;
        }

        $console = new Application($this->kernel);
        $command = explode(' ', $value->getCommand());
        try {
            $console->get((string) $command[0]);
        } catch (CommandNotFoundException $exception) {
            $this->context->buildViolation($constraint->getMessage())->setParameter('[COMMAND]', $command[0])->addViolation();
        }
    }
}
