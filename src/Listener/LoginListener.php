<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace App\Listener;

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

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @param Security $security
     * @param Logger $logger
     */
    public function __construct(ContainerInterface $container, Security $security, Logger $logger)
    {
        $this->container = $container;
        $this->security = $security;
        $this->logger = $logger;
    }

    /**
     * Add the login event to the log record.
     *
     * @param Event $event
     */
    public function onSecurityInteractiveLogin(Event $event): void
    {
        $logger = $this->logger;
        $trans = $this->container->get('translator');
        $username = $this->security->getToken()->getUser()->getUsername(); // TODO: Broken
        $msg = $trans->trans('User %username% logged in.', array('%username%' => $username), 'BinovoElkarBackup');
        $logger->info($msg, array('source' => 'Authentication'));

        $user = $this->security->getToken()->getUser();
        $locale = $user->getLanguage(); // TODO: Broken
        $request = $event->getRequest();
        $request->getSession()->set('_locale', $locale);
    }
}
