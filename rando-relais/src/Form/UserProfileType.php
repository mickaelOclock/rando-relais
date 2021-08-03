<?php

namespace App\Form;

use App\Controller\RegistrationController;
use App\Entity\Service;
use App\Entity\User;
use Doctrine\ORM\Query\Expr\From;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // We use the addEventlistener method on PPRE_SET_DATA to modify the form depending on the pre-populated data (adding or removing fields dynamically).
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData'])
            // We use the addEventlistener method on PRE_SUBMIT to add form fields, before submitting the data to the form.
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit'])
            ->add('status', CheckboxType::class, [
                'label'     => 'Ange du chemin',
                'disabled'  => true,
            ])
            ->add('picture', FileType::class, [
                'label'         => 'Photo de profil',
                'label_attr'    => [
                    'class' => 'd-none'
                ],
                'attr'          => [
                    'class' => 'd-none'
                ],
                'data_class'    => null,
            ])
            ->add('firstName', null, [
                'label'         => "Prénom",
                'label_attr'    => [
                    'class' => "d-none"
                ],
                'attr'          => [
                    'disabled' => true,
                ]
            ])
            ->add('lastName', null, [
                'label'         => "Nom",
                'label_attr'    => [
                    'class' => "d-none"
                ],
                'attr'          => [
                    'disabled' => true,
                ]
            ])
            ->add('email', null, [
                'label'         => "Adresse email",
                'label_attr'    => [
                    'class' => "d-none"
                ],
                'attr'          => [
                    'disabled' => true,
                ]
            ])
            // ->add('plainPassword', PasswordType::class, [
            //     // Instead of being set onto the object directly, this is read and encoded in the UserController.
            //     'mapped'        => false,
            //     'required'      => false,
            //     'label'         => "Mot de passe",
            //     'label_attr'    => [
            //         'class' => "d-none"
            //     ],
            //     'attr'          => [
            //         'autocomplete'  => 'new-password',
            //         'placeholder'   => 'Mot de passe'
            //     ],
            // ])
            ->add('phoneNumber', TelType::class, [
                'required'      => false,
                'label'         => "Numéro de téléphone",
                'label_attr'    => [
                    'class' => "d-none"
                ],
                'attr'          => [
                    'disabled' => true,
                ]
            ])
            ->add('zipCode', null, [
                'required'      => false,
                'label'         => "Code postale",
                'label_attr'    => [
                    'class' => "d-none"
                ],
                'attr'          => [
                    'disabled' => true,
                ]
            ])
            ->add('city', null, [
                'required'      => false,
                'label'         => "Numéro de téléphone",
                'label_attr'    => [
                    'class' => "d-none"
                ],
                'attr'          => [
                    'disabled' => true,
                ]
            ])
            ->add('services', EntityType::class, [
                'required'      =>  false,
                'label'         => false,
                // 'label_attr'    => [
                //     'class' => "d-none"
                // ],
                'class'         => Service::class,
                'by_reference'  => false,
                'multiple'      => true,
                'expanded'      => true,
                'disabled' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    /**
     * Method who modify the form depending on the pre-populated data (adding or removing fields dynamically).
     *
     * @param FormEvent $event
     * @return void
     */
    public function onPreSetData(FormEvent $event)
    {
        // We get the form data.
        $user = $event->getData();
        $form = $event->getForm();

        // We set to $hikerStatus the value of HIKER_STATUS.
        $hikerStatus = RegistrationController::HIKER_STATUS;
        // We set to $angelStatus the value of ANGEL_STATUS.
        $angelStatus = RegistrationController::ANGEL_STATUS;

        // If the user's status is HIKER_STATUS.
        if ($user->getStatus() === $hikerStatus) {
            // We uncheck the checkbox.
            $switchValue = false;
            // We set the label's value.
            $label = "Cocher pour devenir Ange";
        } // Else if, the user's status is ANGEL_STATUS.
        elseif ($user->getStatus() === $angelStatus) {
            // We check the checkbox.
            $switchValue = true;
            // We set the label's value.
            $label = "Décocher pour redevenir Marcheur";
        } // Else, we should not drop here but just in case.
        else {
            // We stop the execution of the condition.
            exit();
        }

        // We dynamically check or uncheck the switch according to the user's staus.
        $form
            ->add('status', CheckboxType::class, [
            'label'     => $label,
            'data'      => $switchValue,
            'disabled'  => true
        ]);

        
        
        // TODO START : not working so we comment the field UserProfileForm.plainPassword in the templates\user\profil.html.twig.
        // // If we have a user in the databse.
        // if ($user->getId()) {
        //     // dd($user);
        //     // We don't required the password field.
        //     // We dont allow the password modification because, for is own security, the user must use the forget resset password feature.
        //     $required = false;
        // } // Else, we should not drop here but just in case.
        // else {
        //     // We stop the execution of the condition.
        //     exit();
        // }
        // // We dynamically add the password field.
        // $form
        //     ->add('plainPassword', PasswordType::class, [
        //         // Instead of being set onto the object directly, this is read and encoded in the UserController.
        //         'mapped'        => false,
        //         'required'      => $required,
        //         'label'         => "Mot de passe",
        //         'label_attr'    => [
        //             'class' => "d-none"
        //         ],
        //         'attr'          => [
        //             'autocomplete'  => 'new-password',
        //             'placeholder'   => 'Mot de passe'
        //         ],
        //         'constraints' => [
        //             new NotBlank([
        //                 'message' => 'Merci de saisir un mot de passe.'
        //             ]),
        //             new Length([
        //                 'min'        => 6,
        //                 'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} characteres.',
        //                 // max length allowed by Symfony for security reasons
        //                 'max'        => 4096,
        //             ])
        //         ],
        //     ]);
        // TODO END.
    }

    /**
     * Method wo display the form fields required for the angel registration if ($user['status']) === true / if the switch is checked
     *
     * @param FormEvent $event
     * @return void
    */
    public function onPreSubmit(FormEvent $event)
    {
        // We get the form data.
        $user = $event->getData();
        $form = $event->getForm();
       
        // We check if the switch button is checked.
        // If $user['status'] === true that mean the user want to register as a Angel (status 2).
        // In order to collect the data related to the Angel's status we need to require the form fields related to this status.
        if (isset($user['status'])) {
            // We add the form fields related to the Angel's status with the attribute required => true.
            $form
                ->add('phoneNumber', null, [
                    'required'      => true,
                    'label'         => "Numéro de téléphone",
                    'label_attr'    => [
                        'class' => "d-none"
                    ],
                    'attr'          => [
                        'placeholder' => 'Numéro de téléphone'
                    ],
                    'constraints'   => [
                        new NotBlank([
                            'message' => 'Merci de saisir votre numéro de téléphone.'
                        ]),
                    ]
                ])
                    ->add('zipCode', null, [
                    'required'      => true,
                    'label'         => "Code postale",
                    'label_attr'    => [
                        'class' => "d-none"
                    ],
                    'attr'          => [
                        'placeholder' => 'Code postale'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci de saisir votre code postale.'
                        ])
                    ]
                ])
                ->add('city', null, [
                    'required'      => true,
                    'label'         => "Numéro de téléphone",
                    'label_attr'    => [
                        'class' => "d-none"
                    ],
                    'attr'          => [
                        'placeholder' => 'Commune'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci de saisir le nom de votre commune.'
                        ])
                    ]
                ])
                ->add('services', EntityType::class, [
                    'required'      => true,
                    'label'         => false,
                    // 'label_attr'    => [
                    //     'class' => "d-none"
                    // ],
                    'class'         => Service::class,
                    'by_reference'  => false,
                    'multiple'      => true,
                    'expanded'      => true,
                    'constraints'   => [
                        new Count([
                            'min' => 1,
                            'minMessage' => 'Merci de sélectionner au minimum un {{ limit }} service.'
                        ]),
                    ],
                    'disabled' => true
                ]);
        }
    }
}