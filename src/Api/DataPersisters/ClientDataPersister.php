<?php
namespace App\Api\DataPersisters;

use App\Entity\Client;
use App\Exception\APIException;
use App\Exception\PermissionException;
use App\Service\ClientService;
use Doctrine\ORM\Cache\Persister;
use Exception;
use InvalidArgumentException;

class ClientDataPersister implements ContextAwareDataPersisterInterface
{
    private ClientService $clientService;
    
    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }
    
    public function persist($data, array $context = [])
    {
        try {
            $this->clientService->save($data);
            return $data;
        } catch (Exception $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
        
    }

    /**
     * @throws PermissionException
     * @throws APIException
     */
    public function remove($data, array $context = []): void
    {
        try{
            $this->clientService->delete($data->getId());
        } catch (PermissionException $e) {
            throw new PermissionException($e->getMessage());
        }catch (Exception $e) {
            throw new APIException($e->getMessage());
        }
        
    }
    
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Client;
    }
}