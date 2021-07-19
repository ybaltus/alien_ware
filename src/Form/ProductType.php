<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "product.name",
                "attr" => [
                    "placeholder" => "product.name",
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => "product.description",
                "attr" => [
                    "placeholder" => "product.description",
                ],
                "constraints" => [
                    new Length([
                        'min' => 10,
                        'max' => 500,
                        'minMessage' => $this->translator->trans('product.errors.minDescription'),
                        'maxMessage' => $this->translator->trans('product.errors.maxDescription')
                    ]),
                ]
            ])
            ->add('price')
            ->add('stock')
            ->add('image', FileType::class, [
                'label' => 'Image (PNG, JPG)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // every time you edit the Product details
                'required' => true,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image document',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
