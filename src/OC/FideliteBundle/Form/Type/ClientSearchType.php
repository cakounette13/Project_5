<?php

namespace OC\FideliteBundle\Form\Type;

use OC\FideliteBundle\Repository\ClientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientSearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id',  EntityType::class, [
                'class' => 'OC\FideliteBundle\Entity\Client',
                'label' => 'Nom du client',
                'query_builder' =>
                    function (ClientRepository $er) {
                        return $er->createQueryBuilder( 't' )
                            ->orderBy( 't.nom', 'ASC' );
                    },
                "choice_label" => function ($client, $nom)
                {
                    return "- ". $client->getNom() . " " . $client->getPrenom() . " - " . $client->getSociete();
                },
            ]);
    }

    /**
     *
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\FideliteBundle\Entity\Client'
        ));
    }

}