<?php

namespace App\Api\DataProviders;

use App\Entity\Job;
use App\Entity\User;
use App\Exception\NotFoundException;
use App\Exception\PermissionException;
use App\Service\LoggerService;
use App\Service\RouterService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class JobItemDataProvider
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
     * @throws NotFoundException
     * @throws NonUniqueResultException
     * @throws PermissionException
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Job
    {
        $repository = $this->entityManager->getRepository('App:Job');
        $query = $repository->createQueryBuilder('j');
        $query->where($query->expr()->eq('j.id', $id));
        if (null == $query->getQuery()->getOneOrNullResult()) {
            throw new NotFoundException(sprintf('The job "%s" does not exist.', $id));
        }
        if (!$this->authChecker->isGranted('ROLE_ADMIN')) {
            $query->join('j.client', 'c');
            $user = $this->security->getToken()->getUser();
            $u = $this->entityManager->getRepository(User::class)->findOneBy(["username" => $user->getUserIdentifier()]);
            $query->andWhere($query->expr()->eq('c.owner', $u->getId()));
            if (null == $query->getQuery()->getOneOrNullResult()) {
                throw new PermissionException(sprintf("Permission denied to get job %s", $id));
            }
        }
        $idClient = $query->getQuery()->getOneOrNullResult()->getClient()->getId();
        $this->logger->debug(
            'View job %clientid%',
            array('%clientid%' => $id),
            array('link' => $this->router->generateJobRoute($id, $idClient))
        );
        $this->entityManager->flush();
        return $query->getQuery()->getOneOrNullResult();
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Job::class === $resourceClass;
    }
}

