<?php
namespace App\Api\DataProviders;

use ApiPlatform\Doctrine\Orm\Util\QueryNameGenerator;
use App\Entity\Job;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class JobCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private AuthorizationCheckerInterface $authChecker;
    private iterable $collectionExtensions;
    private EntityManagerInterface $entityManager;
    private Security $security;
    /**
     * Constructor
     */
    public function __construct(AuthorizationCheckerInterface $authChecker, EntityManagerInterface $em, iterable $collectionExtensions, Security $security)
    {
        $this->authChecker          = $authChecker;
        $this->collectionExtensions = $collectionExtensions;
        $this->entityManager        = $em;
        $this->security             = $security;
    }
    
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $repository = $this->entityManager->getRepository('App:Job');
        $query = $repository->createQueryBuilder('j')->addOrderBy('j.id', 'ASC');
        if (!$this->authChecker->isGranted('ROLE_ADMIN')) {
            $user = $this->security->getToken()->getUser();
            $u = $this->entityManager->getRepository(User::class)->findOneBy(["username" => $user->getUserIdentifier()]);
            $query->join('j.client', 'c');
            $query->where($query->expr()->eq('c.owner', $u->getId()));
        }
        $queryNameGenerator = new QueryNameGenerator();
        foreach ($this->collectionExtensions as $extension) {
            $extension->applyToCollection($query, $queryNameGenerator, $resourceClass, $operationName);
        }
        
        return $query->getQuery()->getResult();
    }
    
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Job::class === $resourceClass;
    }
}