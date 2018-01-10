<?php

namespace AppBundle\Controller;


use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        $form = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 64])
                    ]
                ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 64])
                    ]
                ])
            ->add('remember_me', CheckboxType::class, [
                'required' => false
                ])
            ->add('login', SubmitType::class)
            ->getForm();

        if ($error) {
            $form->addError(new FormError($error->getMessageKey()));
        }

        $form->handleRequest($request);

        return $this->render(
            'security/login.html.twig',
            [
                // last username entered by the user
                'last_username' => $authUtils->getLastUsername(),
                'form'          => $form->createView()
            ]
        );
    }
}