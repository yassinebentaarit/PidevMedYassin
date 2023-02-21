<?php

namespace App\Form;

use App\Entity\Stock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Categorie;
use Symfony\Component\Form\Extension\Core\Type\DateType ;


class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('produit')
            ->add('quantite')
            ->add('quantiteDisponible')
            ->add('categorie',EntityType::class,[
                'class' => Categorie::class                
            ])
            ->add('dateExpiration', DateType::class, [
                'required' => false,
                'empty_data' => null,
                'widget' => 'choice',
                'html5' => false,
                'format'=>"d  M y",
            ])
            ->add('Next',SubmitType::class)
            ->add('Ajouter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
