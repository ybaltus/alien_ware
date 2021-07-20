<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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
                ],
                "constraints" => [
                    new Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => $this->translator->trans('product.errors.minName'),
                        'maxMessage' => $this->translator->trans('product.errors.maxName')
                    ]),
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
            ->add('price', NumberType::class, [
                'label' => "product.price",
                "attr" => [
                    "placeholder" => "product.price",
                ]
            ])
            ->add('stock', NumberType::class, [
                'label' => "Stock",
                "attr" => [
                    "placeholder" => "Stock",
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (PNG, JPG)',
                'mapped' => false,
                "attr" => [
                    "placeholder" => "product.addImage",
                ],
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => $this->translator->trans('product.errors.mimeTypesMessage'),
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
