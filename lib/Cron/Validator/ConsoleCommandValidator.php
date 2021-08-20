<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\Cron\Validator;

use KejawenLab\ApiSkeleton\Cron\Model\CronInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
final class ConsoleCommandValidator extends ConstraintValidator
{
    public function __construct(private KernelInterface $kernel, private TranslatorInterface $translator)
    {
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
            $console->get($command[0]);
        } catch (CommandNotFoundException) {
            $this->context->buildViolation($this->translator->trans($constraint->getMessage(), ['%command%' => $command[0]], 'validators'))->addViolation();
        }
    }
}
