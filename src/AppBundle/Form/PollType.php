<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PollType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false
            ])
            ->add('pollOptions', CollectionType::class, [
                'label' => false,
                'attr' => ['class' => 'form-collection-container'],
                'entry_type' => PollOptionType::class,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true,
//                'by_reference' => false, // enable "adder" and a "remover" methods to easily handle doctrine db relationships
                'allow_delete' => true
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Poll'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_poll';
    }


}
