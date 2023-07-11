<?php

namespace App\Api\DataProviders;

use App\Entity\Client;
use App\Entity\User;
use App\Service\LoggerService;
use App\Service\RouterService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Exception\NotFoundException;
use App\Exception\PermissionException;

final class ClientItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    private AuthorizationCheckerInterface $authChecker;
    private EntityManagerInterface $entityManager;
    private LoggerService $logger;
    private RouterService $router;
    private Security $security;

    public function __construct(EntityManagerInterface $em, AuthorizationCheckerInterface $authChecker, LoggerService $logger, RouterService $router, Security $security)
    {
        $this->authChecker = $authChecker;
        $this->entityManager = $em;
        $this->logger = $logger;
        $this->router = $router;
        $this->security = $security;
    }

    /**
     * @throws PermissionException
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Client
    {
        $repository = $this->entityManager->getRepository('App:Client');
        $query = $repository->createQueryBuilder('c');
        $query->where($query->expr()->eq('c.id', $id));
        if (null == $query->getQuery()->getOneOrNullResult()) {
            throw new NotFoundException(sprintf('The client "%s" does not exist.', $id));
        }
        if (!$this->authChecker->isGranted('ROLE_ADMIN')) {
            $user = $this->security->getToken()->getUser();
            $u = $this->entityManager->getRepository(User::class)->findOneBy(["username" => $user->getUserIdentifier()]);
            $query->andWhere($query->expr()->eq('c.id', $id))->andWhere($query->expr()->eq('c.owner', $u->getId()));
            if (null == $query->getQuery()->getOneOrNullResult()) {
                throw new PermissionException(sprintf("Permission denied to get client %s", $id));
            }
        }
        $this->logger->debug(
            'View client %clientid%',
            array('%clientid%' => $id),
            array('link' => $this->router->generateClientRoute($id))
        );
        $this->entityManager->flush();
        return $query->getQuery()->getOneOrNullResult();
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Client::class === $resourceClass;
    }
}
