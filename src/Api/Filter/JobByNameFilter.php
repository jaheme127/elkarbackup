<?php

namespace App\Api\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Class JobByNameFilter
 * @package App\Api\Filter
 */
class JobByNameFilter extends AbstractFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, $operation = null, array $context = []): void
    {
        if ($property === 'name') {
            $parameterName = $queryNameGenerator->generateParameterName($property);
            $queryBuilder->andWhere(sprintf('j.%s = :%s', $property, $parameterName));
            $queryBuilder->setParameter($parameterName, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(string $resourceClass): array
    {
        $description['name'] = [
            'property' => 'name',
            'type' => 'string',
            'required' => false,
            'openapi' => [
                'description' => 'Filter job by name',
            ],
        ];

        return $description;
    }
}

