<?php

namespace SimpleIT\ClaireAppBundle\Services\Exercise\Resource;

use SimpleIT\ApiResourcesBundle\Course\CourseResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\CommonResource;
use
    SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\MultipleChoice\MultipleChoicePropositionResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\MultipleChoiceQuestionResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\PictureResource;
use SimpleIT\ApiResourcesBundle\Exercise\ExerciseResource\TextResource;
use SimpleIT\ApiResourcesBundle\Exercise\ResourceResource;
use SimpleIT\ClaireAppBundle\Repository\Exercise\Resource\ResourceRepository;

/**
 * Class ResourceService
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 */
class ResourceService
{
    /**
     * @var  ResourceRepository
     */
    private $resourceRepository;

    /**
     * Set resourceRepository
     *
     * @param \SimpleIT\ClaireAppBundle\Repository\Exercise\Resource\ResourceRepository $resourceRepository
     */
    public function setResourceRepository($resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * @param int   $resourceId   Resource id
     * @param array $parameters   Parameters
     *
     * @return ResourceResource
     */
    public function getResourceToEdit($resourceId, array $parameters = array())
    {
        return $this->resourceRepository->findToEdit($resourceId, $parameters);
    }

    /**
     * Save a resource
     *
     * @param int              $resourceId Resource id
     * @param ResourceResource $resource
     * @param array            $parameters
     *
     * @return ResourceResource
     */
    public function save($resourceId, ResourceResource $resource, array $parameters = array())
    {
        return $this->resourceRepository->update($resourceId, $resource, $parameters);
    }

    /**
     * Save a multiple choice question
     *
     * @param       $resourceId
     * @param array $resourceContentArray
     *
     * @return ResourceResource
     */
    public function saveMCQuestion($resourceId, array $resourceContentArray)
    {
        $question = new MultipleChoiceQuestionResource();
        $question->setQuestion($resourceContentArray['question']);
        $question->setMaxNumberOfPropositions($resourceContentArray['maxProp']);
        $question->setMaxNumberOfRightPropositions($resourceContentArray['maxRightProp']);
        $question->setComment($resourceContentArray['comment']);

        $propositions = array();
        foreach ($resourceContentArray['propositionText'] as $key => $propText) {
            $prop = new MultipleChoicePropositionResource();
            $prop->setText($propText);
            $prop->setRight(
                isset ($resourceContentArray['propositionRight'][$key]) &&
                $resourceContentArray['propositionRight'][$key] == 1
            );
            $propositions[] = $prop;
        }
        $question->setPropositions($propositions);

        $resource = new ResourceResource();
        $resource->setContent($question);

        return $this->save($resourceId, $resource);
    }

    /**
     * Add a resource
     *
     * @param ResourceResource $resource
     *
     * @return ResourceResource
     */
    public function add(ResourceResource $resource)
    {
        $content = null;
        switch ($resource->getType()) {
            case CommonResource::MULTIPLE_CHOICE_QUESTION:
                $content = new MultipleChoiceQuestionResource();
                $content->setQuestion('Ennoncé de la question');
                $content->setComment(
                    'Commentaire qui sera affiché à l\'apprenant quand il verra la correction'
                );
                $content->setMaxNumberOfPropositions(0);
                $content->setMaxNumberOfRightPropositions(0);
                break;
            case CommonResource::TEXT:
                $content = new TextResource();
                $content->setText('Exemple de texte');
                break;
            case CommonResource::PICTURE:
                $content = new PictureResource();
                $content->setSource('adresse/de/votre/image');
                break;
        }

        $resource->setContent($content);
        $resource->setRequiredExerciseResources(array());

        $resource = $this->resourceRepository->insert($resource);

        return $resource;
    }

    /**
     * Get a resource
     *
     * @param int $resourceId Resource id
     *
     * @return ResourceResource
     */
    public function getToEdit($resourceId)
    {
        return $this->resourceRepository->findToEdit($resourceId);
    }

    /**
     * Delete a resource
     *
     * @param $resourceId
     */
    public function delete($resourceId)
    {
        $this->resourceRepository->delete($resourceId);
    }
}
