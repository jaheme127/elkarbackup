<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace App\Logger;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Processor\WebProcessor;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Monolog\LogRecord;

/**
 * Injects url/method and remote IP of the current web request in all records
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class WebUserLoggerProcessor extends WebProcessor implements ContainerAwareInterface
{
    private Security $security;
    private $container;
    private EntityManagerInterface $em;

    /**
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        parent::__construct($security);
        $this->em = $em;
        $this->security = $security;
    }

    public function __invoke($record): LogRecord
    {
        $record = parent::__invoke($record);
        $record['extra'] = array_merge(
            $record['extra'],
            array(
                'user' => null,
                'user_email' => '',
                'user_id' => '',
                'user_name' => '',
            )
        );
        $user = null;
        $token = $this->security->getToken();
        $user = $token?->getUser();
        $u = $this->em->getRepository(User::class)->findOneBy(["username" => $user->getUserIdentifier()]);

        if ($user) {
            if (!is_string($user) && $user != "anon.") {
                $record['extra'] = array_merge(
                    $record['extra'],
                    array(
                        'user' => $u,
                        'user_email' => $u->getEmail(),
                        'user_id' => $u->getId(),
                        'user_name' => $u->getUsername(),
                    )
                );
            }
        }

        return $record;
    }

    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }
}
