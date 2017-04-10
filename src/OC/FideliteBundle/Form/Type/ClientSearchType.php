<?php

namespace OC\FideliteBundle\Form\Type;

use OC\FideliteBundle\Entity\Client;
use OC\FideliteBundle\Repository\ClientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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
                'class' => 'OCFideliteBundle:Client',
                'label' => 'Choix du client',
                'required' => true,
                'multiple' => false,
                'placeholder' => '-- Sélectionner le client --',
                'query_builder' =>
                    function (ClientRepository $er) {
                        return $er->createQueryBuilder( 't' )
                            ->orderBy( 't.nom', 'ASC' );
                    },
                "choice_label" => function (Client $client, $id)
                {
                    return "- ". $client->getNom() . " " . $client->getPrenom() . " - " . $client->getSociete();
                },
            ]);
    }
}
