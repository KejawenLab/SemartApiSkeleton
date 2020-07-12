<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Client;

use Alpabit\ApiSkeleton\Entity\Client;
use Alpabit\ApiSkeleton\Pagination\AliasHelper;
use Alpabit\ApiSkeleton\Client\Model\ClientRepositoryInterface;
use Alpabit\ApiSkeleton\Service\AbstractService;
use Alpabit\ApiSkeleton\Service\Model\ServiceInterface;
use Alpabit\ApiSkeleton\Util\Encryptor;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.kejawen@gmail.com>
 */
final class ClientService extends AbstractService implements ServiceInterface
{
    public function __construct(MessageBusInterface $messageBus, ClientRepositoryInterface $repository, AliasHelper $aliasHelper)
    {
        parent::__construct($messageBus, $repository, $aliasHelper);
    }

    public function save(object $object): void
    {
        /** @var Client $object */
        $object->setApiKey(sha1(date('Y-m-d H:i:s')));
        $object->setSecretKey(Encryptor::hash($object->getApiKey()));

        parent::save($object);
    }
}
