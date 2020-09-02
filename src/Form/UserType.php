<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserType extends AbstractType {

  /**
   * @var AuthorizationCheckerInterface
   */
  protected $authorizationChecker;

  public function __construct(AuthorizationCheckerInterface $authorizationChecker) {
    $this->authorizationChecker = $authorizationChecker;
  }

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('username')
      ->add('password');

    if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
      $builder->add('admin');
    }
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(
      [
        'data_class' => User::class,
      ]
    );
  }
}
