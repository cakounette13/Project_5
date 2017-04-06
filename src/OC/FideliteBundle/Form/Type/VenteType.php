<?php

namespace OC\FideliteBundle\Form\Type;

use OC\FideliteBundle\Entity\Client;
use OC\FideliteBundle\Repository\ClientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VenteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateVente', DateTimeType::class, array(
                'label' => 'Date de la vente',
                'format' => 'dd-MM-yyyy',
                'html5' => 'false',
                'invalid_message' => 'La date saisie n\'est pas au bon format (ex:01/01/2050)',
                'widget' => 'single_text',
                'attr' => array(
                    'placeholder' => 'Date de vente ex:01/01/2050',
                )
            ))
            ->add('client',  EntityType::class, [
                'class' => 'OC\FideliteBundle\Entity\Client',
                'label' => 'Nom du client',
                'query_builder' =>
                    function (ClientRepository $er) {
                        return $er->createQueryBuilder( 't' )
                            ->orderBy( 't.nom', 'ASC' );
                    },
                "choice_label" => function (Client $client, $nom)
                {
                    return "- ". $client->getNom() . " " . $client->getPrenom() . " - " . $client->getSociete();
                },
            ])
            ->add('montantVente', NumberType::class, array(
                'label' => 'Montant TTC',
            ))
            ->add('pointsFideliteUtilises', NumberType::class, [
                'label' => 'Points de fidélité utilisés'
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\FideliteBundle\Entity\Vente'
        ));
    }
}
