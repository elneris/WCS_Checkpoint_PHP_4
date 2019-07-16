<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Ticket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('name')
            ->add('event', EntityType::class, [
                'class' => Event::class,
                'choice_label' => 'city'
            ])
            ->add('category', ChoiceType::class,[
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    'basic'   => 'basic',
                    'vip'   => 'vip',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
