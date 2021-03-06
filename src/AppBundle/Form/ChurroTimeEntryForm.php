<?php

namespace AppBundle\Form;

use AppBundle\Entity\Baker;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class ChurroTimeEntryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, ['choices' => [
                'vanilla' => 'Vanilla',
                'chocolate' => 'Chocolate'
            ]])
            ->add('startCookingAt', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datetime-field'],
            ])
            ->add('endCookingAt', DateTimeType::class)
            ->add('startCleanupAt', DateTimeType::class)
            ->add('endCleanupAt', DateTimeType::class)
            ->add('quantityMade', IntegerType::class)
            ->add('bakedBy', EntityType::class, [
                'class' => Baker::class,
                'choice_label' => 'getAbbreviatedName',
                'placeholder' => 'Choose a Baker',

            ])
        ;
    }
}