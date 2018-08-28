<?php

namespace AppBundle\Controller;

use EntrepriseBundle\Entity\Compte;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use ProfilBundle\Entity\Prof;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @RouteResource("registration", pluralize=false)
 */
class RestRegistrationController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Annotations\Post("/register/{ref}")
     */
    public function registerAction(Request $request,$ref)
    {

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        $user = $userManager->createUser();
        $user->setEnabled(true);
        // selon le type de profil !!!! à changer


        if($ref==0){
            $file=$this->getDoctrine()->getRepository('AppBundle:File')->find(35);

            $user->setRole('profile-it');
            $profile=new Prof();
           $profile->setSexe('homme');
            $profile->setImage($file);
            $em = $this->getDoctrine()->getManager();
            $em->persist($profile);
            $em->flush();
            $pro=$this->getDoctrine()->getRepository('ProfilBundle:Prof')->find($profile);
            $user->setProf($pro);
            $pro->setUser($user);
        }
        if($ref==1){
            $user->setRole('company');
            $email=(string)$user->getEmail();
            $username=(string)$user->getUsername();
            \Stripe\Stripe::setApiKey("sk_test_R6f3tuAsgbkH45pSm6tcr4s7");
            $rep=\Stripe\Customer::create(array(
                "email" => "$email",
                "description" =>"$username"
                //   "source" => "tok_1CNhZVEZKCNOiQPS91IhWQMg"
                // obtained with Stripe.js
            ));
            $compte=new Compte();
            $compte->setSolde(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($compte);
            $em->flush();
            $pro=$this->getDoctrine()->getRepository('EntrepriseBundle:Compte')->find($compte);
            $user->setCompte($compte);
            $compte->setUser($user);
            $compte->setStripeId($rep->id);
        }


        //


        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm([
            'csrf_protection'    => false
        ]);
        $form->setData($user);
        $form->submit($request->request->all());

        if ( ! $form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }

            return $form;
        }

        $event = new FormEvent($form, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

        if ($event->getResponse()) {
            return $event->getResponse();
        }

        $userManager->updateUser($user);

      //


        //
        $response = new JsonResponse(
            [
                'msg' => $this->get('translator')->trans('registration.flash.user_created', [], 'FOSUserBundle'),
                'token' => $this->get('lexik_jwt_authentication.jwt_manager')->create($user), // creates JWT
            ],
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl(
                    'get_profile',
                    [ 'user' => $user->getId() ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ]
        );

        $dispatcher->dispatch(
            FOSUserEvents::REGISTRATION_COMPLETED,
            new FilterUserResponseEvent($user, $request, $response)
        );

        return $response;
    }
}
