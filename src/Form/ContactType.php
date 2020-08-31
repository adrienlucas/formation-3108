<?php

declare(strict_types=1);

namespace App\Form;

use App\Model\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class ContactType extends AbstractType
{
    CONST MAX_MESSAGE_LENGTH = 120;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => new Email(['message' => 'Merci de saisir un email valide'])
            ])
            ->add('message', TextareaType::class, [
                'constraints' => new Length(['max' => self::MAX_MESSAGE_LENGTH, 'maxMessage' => 'Merci de saisir un message de moins de 120 char'])
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', Contact::class);
    }
}
