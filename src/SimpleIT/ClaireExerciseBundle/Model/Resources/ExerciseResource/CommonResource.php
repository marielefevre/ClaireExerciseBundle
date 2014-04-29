<?php


namespace SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource;

use JMS\Serializer\Annotation as Serializer;
use SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\LocalFormula;
use SimpleIT\ClaireExerciseBundle\Model\Resources\Validable;

/**
 * Class ResourceResource
 *
 * @author Baptiste Cablé <baptiste.cable@liris.cnrs.fr>
 * @Serializer\Discriminator(field = "object_type", map = {
 *    "picture": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\PictureResource",
 *    "text": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\TextResource",
 *    "sequence": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\SequenceResource",
 *    "multiple_choice_question": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\MultipleChoiceQuestionResource",
 *    "open_ended_question": "SimpleIT\ClaireExerciseBundle\Model\Resources\ExerciseResource\OpenEndedQuestionResource"
 * })
 */
abstract class CommonResource implements Validable
{
    /**
     * @const PICTURE = "picture"
     */
    const PICTURE = "picture";

    /**
     * @const TEXT = "text"
     */
    const TEXT = "text";

    /**
     * @const MULTIPLE_CHOICE_QUESTION = "multiple-choice-question"
     */
    const MULTIPLE_CHOICE_QUESTION = "multiple-choice-question";

    /**
     * @const OPEN_ENDED_QUESTION = "open-ended-question"
     */
    const OPEN_ENDED_QUESTION = "open-ended-question";

    /**
     * @const SEQUENCE = "sequence"
     */
    const SEQUENCE = "sequence";

    /**
     * @var LocalFormula A LocalFormula
     * @Serializer\Type("SimpleIT\ClaireExerciseBundle\Model\Resources\DomainKnowledge\Formula\LocalFormula")
     * @Serializer\Groups({"details", "exercise_model_storage"})
     */
    protected $formula;

    /**
     * Set formula
     *
     * @param LocalFormula $formula
     */
    public function setFormula($formula)
    {
        $this->formula = $formula;
    }

    /**
     * Get formula
     *
     * @return LocalFormula
     */
    public function getFormula()
    {
        return $this->formula;
    }

    /**
     * Checks if a type of resource is valid
     *
     * @param string $type
     *
     * @return bool
     */
    public static function isValidType($type)
    {
        if (
            $type === self::TEXT
            || $type === self::SEQUENCE
            || $type === self::PICTURE
            || $type === self::MULTIPLE_CHOICE_QUESTION
            || $type === self::OPEN_ENDED_QUESTION
        ) {
            return true;
        }

        return false;
    }
}