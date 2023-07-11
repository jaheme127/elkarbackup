<?php

namespace App\Api\DataPersisters;


use App\Entity\Job;
use App\Exception\APIException;
use App\Exception\PermissionException;
use App\Service\JobService;
use Exception;
use InvalidArgumentException;

class JobDataPersister implements ContextAwareDataPersisterInterface
{
    private JobService $jobService;

    public function __construct(JobService $clientService)
    {
        $this->jobService = $clientService;
    }

    public function persist($data, array $context = [])
    {
        try {
            $this->jobService->save($data);
            return $data;
        } catch (Exception $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

    }

    /**
     * @throws PermissionException
     * @throws APIException
     */
    public function remove($data, array $context = [])
    {
        try {
            $this->jobService->delete($data->getId());
        } catch (PermissionException $e) {
            throw new PermissionException($e->getMessage());
        } catch (Exception $e) {
            throw new APIException($e->getMessage());
        }

    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Job;
    }
}

