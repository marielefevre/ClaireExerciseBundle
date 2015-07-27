<?php
/*
 * This file is part of CLAIRE.
 *
 * CLAIRE is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CLAIRE is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CLAIRE. If not, see <http://www.gnu.org/licenses/>
 */

namespace SimpleIT\ClaireExerciseBundle\Repository\Exercise\CreatedExercise;

use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Answer;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Attempt;
use SimpleIT\ClaireExerciseBundle\Entity\CreatedExercise\Item;
use SimpleIT\ClaireExerciseBundle\Exception\NonExistingObjectException;
use SimpleIT\ClaireExerciseBundle\Repository\BaseRepository;

/**
 * Answer repository
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class AnswerRepository extends BaseRepository
{
    /**
     * Find an answer by id
     *
     * @param int $itemId
     *
     * @return Answer
     * @throws NonExistingObjectException
     */
    public function find($itemId)
    {
        $item = parent::find($itemId);
        if ($item === null) {
            throw new NonExistingObjectException();
        }

        return $item;
    }

    /**
     * Get all the answers. An item can be specified.
     *
     * @param Item    $item
     * @param Attempt $attempt
     *
     * @return array
     */
    public function findAllBy($item = null, $attempt = null)
    {
        $queryBuilder = $this->createQueryBuilder('a');

        if (!is_null($item)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'a.item',
                    $item->getId()
                )
            );
        }

        if (!is_null($attempt)) {
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(
                    'a.attempt',
                    $attempt->getId()
                )
            );
        }

        $queryBuilder->add('orderBy', 'a.id', true);

        return $queryBuilder->getQuery()->getResult();
    }
}
