<?php

namespace App\Api\DataProviders;

use ApiPlatform\Doctrine\Orm\Util\QueryNameGenerator;
use App\Entity\Client;
use App\Entity\User;
use App\Service\LoggerService;
use App\Service\RouterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ClientCollectionDataProvider
{

    private AuthorizationCheckerInterface $authChecker;
    private iterable $collectionExtensions;
    private EntityManagerInterface $entityManager;
    private LoggerService $logger;
    private RouterService $router;
    private Security $security;

    /**
     * Constructor
     */
    public function __construct(EntityManagerInterface $em, AuthorizationCheckerInterface $authChecker, Security $security, LoggerService $logger, RouterService $router, iterable $collectionExtensions)
    {
        $this->authChecker = $authChecker;
        $this->collectionExtensions = $collectionExtensions;
        $this->entityManager = $em;
        $this->logger = $logger;
        $this->router = $router;
        $this->security = $security;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $repository = $this->entityManager->getRepository('App:Client');
        $query = $repository->createQueryBuilder('c')->addOrderBy('c.id', 'ASC');
        if (!$this->authChecker->isGranted('ROLE_ADMIN')) {
            $user = $this->security->getToken()->getUser();
            $u = $this->entityManager->getRepository(User::class)->findOneBy(["username" => $user->getUserIdentifier()]);
            $query->where($query->expr()->eq('c.owner', $u->getId()));
        }
        $queryNameGenerator = new QueryNameGenerator();

        foreach ($this->collectionExtensions as $extension) {
            $extension->applyToCollection($query, $queryNameGenerator, $resourceClass, $operationName);
        }
        $this->logger->debug(
            'View clients',
            array(),
            array('link' => $this->router->generateUrl('showClients'))
        );
        $this->entityManager->flush();
        return $query->getQuery()->getResult();
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Client::class === $resourceClass;
    }
}

