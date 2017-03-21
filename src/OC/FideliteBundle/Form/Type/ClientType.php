<?php

namespace OC\FideliteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('denomination', ChoiceType::class, array(
                'label' => 'Dénomination',
                'choices' => array('M.' => 'Monsieur', 'Mme' => 'Madame', 'Société' => 'Société'),
                'required' => true,
                'expanded' => true,
            ))
            ->add('nom', TextType::class, array(
                'label' => 'Nom',
                'attr' => array(
                    'placeholder' => 'Nom',
                )
            ))
            ->add('prenom', TextType::class, array(
                'label' => 'Prénom',
                'attr' => array(
                    'placeholder' => 'Prénom',
                )
            ))
            ->add('societe', TextType::class, array(
                'label' => 'Société',
                'attr' => array(
                    'placeholder' => 'Société',
                ),
                'required' => false,
            ))
            ->add('codePostal', TextType::class, array(
                'label' => 'Code Postal',
                'attr' => array(
                    'placeholder' => 'Code postal à 5 chiffres',
                )
            ))
            ->add('ville', TextType::class, array(
                'label' => 'Ville',
                'attr' => array(
                    'placeholder' => 'Ville',
                )
            ))
            ->add('portable', TextType::class, array(
                'label' => 'N° de portable',
                'attr' => array(
                    'placeholder' => 'N° de portable ex:0699999999',
                )
            ))
            ->add('dateNaissance', DateType::class, array(
                'label' => 'Date de Naissance',
                'format' => 'dd/MM/yyyy',
                'html5' => 'false',
                'invalid_message' => 'La date saisie n\'est pas au bon format (01/01/2050)',
                'widget' => 'single_text',
                'attr' => array(
                    'placeholder' => 'Date de naissance ex:01/01/2050',
                    'id'=> 'datepicker'
                )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email',
                'attr' => array(
                    'placeholder' => 'Email',
                )
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\FideliteBundle\Entity\Client'
        ));
    }
}
