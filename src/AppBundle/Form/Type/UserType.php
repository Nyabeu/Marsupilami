<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('color', TextType::class, [
                'label' => 'Couleur',
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Quel est votre âge',
            ])
            ->add('family', TextType::class, [
                'label' => 'Quelle est sa famille ?',
            ])
            ->add('race', TextType::class, [
                'label' => 'Quelle est sa race',
            ])
            ->add('food', TextType::class, [
                'label' => 'Sa nourriture ',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
