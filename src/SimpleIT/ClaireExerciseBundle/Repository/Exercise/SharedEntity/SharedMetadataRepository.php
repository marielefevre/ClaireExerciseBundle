<?php

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\SharedEntity;

use Doctrine\ORM\QueryBuilder;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\Metadata;
use SimpleIT\ClaireExerciseBundle\Entity\SharedEntity\SharedEntity;
use SimpleIT\CoreBundle\Exception\NonExistingObjectException;
use SimpleIT\CoreBundle\Repository\BaseRepository;
use SimpleIT\Utils\Collection\CollectionInformation;
use SimpleIT\Utils\Collection\PaginatorInterface;
use SimpleIT\Utils\Collection\Sort;

/**
 * SharedMetadata repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class SharedMetadataRepository extends BaseRepository
{
    const METADATA_TABLE = 'Name of the table';

    const ENTITY_ID_FIELD_NAME = 'Name of the field';
    const ENTITY_NAME = 'Name of the entity';

    /**
     * Find a model by id
     *
     * @param array $parameters
     *
     * @return Metadata
     * @throws NonExistingObjectException
     */
    public function find($parameters)
    {
        $metadata = parent::find($parameters);
        if ($metadata === null) {
            throw new NonExistingObjectException();
        }

        return $metadata;
    }

    /**
     * Return all the metadata
     *
     * @param CollectionInformation $collectionInformation
     * @param SharedEntity          $entity
     *
     * @return PaginatorInterface
     */
    public function findAllBy(
        $collectionInformation = null,
        $entity = null
    )
    {
        $queryBuilder = $this->createQueryBuilder('m');

        if (!is_null($entity)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'm.' . static::ENTITY_NAME,
                    $entity->getId()
                )
            );
        }

        // Handle Collection Information
        if (!is_null($collectionInformation)) {
            $sorts = $collectionInformation->getSorts();

            foreach ($sorts as $sort) {
                /** @var Sort $sort */
                switch ($sort->getProperty()) {
                    case static::ENTITY_NAME:
                        $queryBuilder->addOrderBy('m.' . static::ENTITY_NAME, $sort->getOrder());
                        break;
                    case 'key':
                        $queryBuilder->addOrderBy('m.key', $sort->getOrder());
                        break;
                }
            }
            $queryBuilder = $this->setRange($queryBuilder, $collectionInformation);
        } else {
            $queryBuilder->addOrderBy('m.' . static::ENTITY_NAME, 'ASC');
            $queryBuilder->addOrderBy('m.key', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Delete all the metadata for an entity
     *
     * @param int    $entityId
     */
    public function deleteAllByEntity($entityId)
    {
        $sql = 'DELETE FROM ' . static::METADATA_TABLE . ' AS emm WHERE emm.' .
            static::ENTITY_ID_FIELD_NAME . '= :entityId';

        $connection = $this->_em->getConnection();
        $connection->executeQuery(
            $sql,
            array('entityId' => $entityId)
        );
    }
}