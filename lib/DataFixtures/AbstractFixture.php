<?php

declare(strict_types=1);

namespace KejawenLab\ApiSkeleton\DataFixtures;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture as Base;
use Doctrine\Persistence\ObjectManager;
use KejawenLab\ApiSkeleton\Security\Model\GroupInterface;
use KejawenLab\ApiSkeleton\Security\Model\PermissionInterface;
use KejawenLab\ApiSkeleton\Security\Service\PermissionService;
use KejawenLab\ApiSkeleton\Service\Message\EntityPersisted;
use KejawenLab\ApiSkeleton\Util\StringUtil;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@gmail.com>
 */
abstract class AbstractFixture extends Base
{
    protected const REF_KEY = 'ref:';

    public function __construct(private PermissionService $service, protected KernelInterface $kernel, private MessageBusInterface $messageBus)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($this->getData() as $fixtures) {
            $entity = $this->createNew();

            foreach ($fixtures as $key => $value) {
                if (static::REF_KEY === sprintf('%s:', $key)) {
                    $this->setReference(StringUtil::uppercase(sprintf('%s#%s', $this->getReferenceKey(), $value)), $entity);
                } else {
                    if (\is_string($value) && str_contains($value, static::REF_KEY)) {
                        $value = $this->getReference(StringUtil::uppercase(str_replace('ref:', '', $value)));
                    }

                    if (\is_string($value) && str_contains($value, 'date:')) {
                        $value = DateTime::createFromFormat('Y-m-d', str_replace('date:', '', $value));
                    }

                    try {
                        $accessor->setValue($entity, $key, $value);
                    } catch (NoSuchPropertyException) {
                        if ('id' === $key && $entity instanceof GroupInterface)  {
                            $reflect = new \ReflectionProperty($entity, $key);
                            $reflect->setAccessible(true);
                            $reflect->setValue($entity, Uuid::fromString(GroupInterface::SUPER_ADMIN_ID));
                        }
                    }
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
