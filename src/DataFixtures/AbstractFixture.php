<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\DataFixtures;

use Alpabit\ApiSkeleton\Entity\Message\EntityPersisted;
use Alpabit\ApiSkeleton\Util\StringUtil;
use Doctrine\Bundle\FixturesBundle\Fixture as Base;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractFixture extends Base
{
    protected const REF_KEY = 'ref:';

    private $messageBus;

    protected $container;

    public function __construct(ContainerInterface $container, MessageBusInterface $messageBus)
    {
        $this->container = $container;
        $this->messageBus = $messageBus;
    }

    public function load(ObjectManager $manager)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($this->getData() as $fixtures) {
            $entity = $this->createNew();

            foreach ($fixtures as $key => $value) {
                if (static::REF_KEY === sprintf('%s:', $key)) {
                    $this->setReference(StringUtil::uppercase(sprintf('%s#%s', $this->getReferenceKey(), $value)), $entity);
                } else {
                    if (\is_string($value) && false !== strpos($value, static::REF_KEY)) {
                        $value = $this->getReference(StringUtil::uppercase(str_replace('ref:', '', $value)));
                    }

                    if (\is_string($value) && false !== strpos($value, 'date:')) {
                        $value = \DateTime::createFromFormat('Y-m-d', str_replace('date:', '', $value));
                    }

                    $accessor->setValue($entity, $key, $value);
                }
            }

            $this->messageBus->dispatch(new EntityPersisted($entity));
            $manager->persist($entity);
        }

        $manager->flush();
    }

    protected function getData(): array
    {
        $path = sprintf('%s/fixtures/%s.yaml', $this->container->getParameter('kernel.project_dir'), $this->getReferenceKey());

        return Yaml::parse((string) file_get_contents($path));
    }

    abstract protected function createNew();

    abstract protected function getReferenceKey(): string;
}
