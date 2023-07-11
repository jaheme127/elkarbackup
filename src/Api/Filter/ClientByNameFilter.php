<?php

namespace App\Api\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ClientByNameFilter
 * @package App\Api\Filter
 */
class ClientByNameFilter extends AbstractFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, $operation = null, array $context = []): void
    {
        if ($property === 'name') {
            $parameterName = $queryNameGenerator->generateParameterName($property);
            $queryBuilder->andWhere(sprintf('c.%s = :%s', $property, $parameterName));
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
                'description' => 'Filter client by name',
            ],
        ];

        return $description;
    }
}

