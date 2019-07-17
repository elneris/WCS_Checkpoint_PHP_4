<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Ticket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', null, [
                'label' => 'Rechercher un tickets',
                'required' => false
            ])
            ->add('date', EntityType::class, [
                'class' => Event::class,
                'choice_label' => 'getdatestring',
                'required' => false,
                'placeholder' => 'Toutes les dates'
            ])
            ->add('valide', ChoiceType::class, [
                'label' => 'valide',
                'choices' => [
                    'Tout les tickets' => null,
                    'Oui' => true,
                    'Non' => false
                ]
            ])
            ->add('Rechercher', SubmitType::class);
        ;
    }
}
