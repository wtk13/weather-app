<?php
declare(strict_types=1);

namespace AppBundle\Form;

use AppBundle\DTO\LatLngDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LatLngType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lat', NumberType::class, [
                'scale' => 2
            ])
            ->add('lng', NumberType::class, [
                'scale' => 2
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LatLngDTO::class,
            'csrf_protection' => false
        ]);
    }
}
