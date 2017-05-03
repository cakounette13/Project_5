<?php

namespace OC\UserBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Constraints\NotBlank;
use OC\UserBundle\Entity\User;

class ChangeUsernameType extends AbstractType
{
	/**
	 * @var EntityManager
	 */
	private $em;
	/**
	 * @var Session
	 */
	private $session;
	/**
	 * @var TokenStorage
	 */
	private $token;

	public function __construct(EntityManager $em, Session $session, TokenStorage $token)
	{

		$this->em = $em;
		$this->session = $session;
		$this->token = $token;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder->add('username', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
	    $resolver->setDefaults([
		    'data_class' => User::class,
		    'constraints' => [
			    new NotBlank(),
			    new UniqueEntity([
				    'fields' => [ 'username' ],
				    'message' => 'nom d\'utilisateur impossible, essayez-en autre.'
			    ])
		    ]
	    ]);
    }

    public function getName()
    {
        return 'user_bundle_change_username';
    }
}
