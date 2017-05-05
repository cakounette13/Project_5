<?php

namespace OC\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ResetPassword extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder
		    ->add('plainPassword', RepeatedType::class, [
			    'constraints' => [
				    new NotBlank(),
				    new Length([
					    'min' => 6,
					    'minMessage' => 'mot de passe minimum de 6 caracteres'
				    ])
			    ],
			    'type' => PasswordType::class
		    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user_bundle_reset_password';
    }
}
