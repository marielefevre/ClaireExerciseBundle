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

namespace SimpleIT\ClaireExerciseBundle\Model\Resources;

use SimpleIT\ClaireExerciseBundle\Entity\DomainKnowledge\Knowledge;

/**
 * Class KnowledgeResourceFactory
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
abstract class KnowledgeResourceFactory extends SharedResourceFactory
{

    /**
     * Create an KnowledgeResource collection
     *
     * @param array $knowledges
     *
     * @return array
     */
    public static function createCollection(array $knowledges)
    {
        $knowledgeResources = array();
        foreach ($knowledges as $knowledge) {
            $knowledgeResources[] = self::create($knowledge);
        }

        return $knowledgeResources;
    }

    /**
     * Create a KnowledgeResource
     *
     * @param Knowledge $knowledge
     *
     * @return KnowledgeResource
     */
    public static function create(Knowledge $knowledge)
    {
        $knowledgeResource = new KnowledgeResource();
        parent::fill(
            $knowledgeResource,
            $knowledge
        );

        // knowledge requirements
        $requirements = array();
        foreach ($knowledge->getRequiredKnowledges() as $req) {
            /** @var Knowledge $req */
            $requirements[] = $req->getId();
        }
        $knowledgeResource->setRequiredKnowledges($requirements);

        // removable
        if (count($knowledge->getRequiredByModels()) > 0
            || count($knowledge->getRequiredByResources()) > 0
            || count($knowledge->getRequiredByKnowledges()) > 0
        ) {
            $knowledgeResource->setRemovable(false);
        } else {
            $knowledgeResource->setRemovable(true);
        }

        return $knowledgeResource;
    }
}
