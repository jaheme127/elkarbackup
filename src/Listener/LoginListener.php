<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace App\Listener;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Monolog\Logger;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Custom login listener.
 */
class LoginListener
{
    private ContainerInterface $container;
    private Security $security;
    private Logger $logger;
    private EntityManager $em;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @param Security $security
     * @param Logger $logger
     */
    public function __construct(ContainerInterface $container, Security $security, Logger $logger, EntityManager $em)
    {
        $this->container = $container;
        $this->security = $security;
        $this->logger = $logger;
        $this->em = $em;

    }

    /**
     * Add the login event to the log record.
     *
     * @param Event $event
     * @throws NotSupported
     */
    public function onSecurityInteractiveLogin(Event $event): void
    {
        $u = $this->em->getRepository(User::class)->findOneBy(["usernmame" => $this->security->getToken()->getUser()->getUserIdentifier()]);
        $logger = $this->logger;
        $trans = $this->container->get('translator');
        $username = $u->getUsername();
        $msg = $trans->trans('User %username% logged in.', array('%username%' => $username), 'BinovoElkarBackup');
        $logger->info($msg, array('source' => 'Authentication'));

        $user = $this->security->getToken()->getUser();
        $locale = $u->getLanguage();
        $request = $event->getRequest();
        $request->getSession()->set('_locale', $locale);
    }
}
