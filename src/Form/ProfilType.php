<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProfilType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                "label" => "user.form.firstname",
                "attr" => [
                    "placeholder" => "user.form.firstname",
                ]
            ])
            ->add('lastname', TextType::class, [
                "label" => "user.form.lastname",
                "attr" => [
                    "placeholder" => "user.form.lastname",
                ]
            ])
            ->add('email', EmailType::class)
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,

                "label" => "user.form.password",
                'attr' => [
                    'autocomplete' => 'new-password',
                    "placeholder" => "user.form.password",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('user.errors.notBlankPassword'),
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => $this->translator->trans('user.errors.minPassword'),
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
