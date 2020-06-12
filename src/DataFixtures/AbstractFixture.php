<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\DataFixtures;

use Alpabit\ApiSkeleton\Entity\Message\EntityPersisted;
use Alpabit\ApiSkeleton\Security\Model\PermissionInterface;
use Alpabit\ApiSkeleton\Security\Service\PermissionService;
use Alpabit\ApiSkeleton\Util\StringUtil;
use Doctrine\Bundle\FixturesBundle\Fixture as Base;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
abstract class AbstractFixture extends Base
{
    protected const REF_KEY = 'ref:';

    private PermissionService $service;

    private MessageBusInterface $messageBus;

    protected KernelInterface $kernel;

    public function __construct(PermissionService $service, KernelInterface $kernel, MessageBusInterface $messageBus)
    {
        $this->service = $service;
        $this->kernel = $kernel;
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

            if ($entity instanceof PermissionInterface) {
                $persist = $this->service->getPermission($entity->getGroup(), $entity->getMenu());
                $persist->setAddable($entity->isAddable());
                $persist->setEditable($entity->isEditable());
                $persist->setDeletable($entity->isDeletable());
                $persist->setViewable($entity->isViewable());
                $entity = $persist;
            }

            $manager->persist($entity);
            $this->messageBus->dispatch(new EntityPersisted($entity));
        }

        $manager->flush();
    }

    protected function getData(): iterable
    {
        return Yaml::parse((string) file_get_contents(sprintf('%s/fixtures/%s.yaml', $this->kernel->getProjectDir(), $this->getReferenceKey())));
    }

    abstract protected function createNew();

    abstract protected function getReferenceKey(): string;
}
