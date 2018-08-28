<?php

namespace EntrepriseBundle\Controller;

use\Stripe\Charge;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProfilBundle\Service\Validate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CompteJetonController extends Controller
{
    /**
     * @ApiDoc(
     *      resource=true,
     *     description="ajouter un jeton au grand compte",
     *     section="jeton_compte"
     * )
     * @param $id_gc
     * @param $id_jeton
     * @param $id_pack
     * @Route("/api/comptes/{id_gc}/{id_pack}", name="add_jeton_compte")
     * @Method({"POST"})
     * @return Response
     */
    public function addJetonToCompte($id_gc,$id_pack)
    {
        $em = $this->getDoctrine()
            ->getManager();



        $pack = $em->getRepository('PaymentBundle:Pack')
            ->find($id_pack);
        //verifier l'existence du Pack
        if (empty($pack)) {
            $response=array(
                'code'=>1,
                'message'=>'Pack introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }

        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_gc);
        $compte=$user->getCompte();
        $customer=$compte->getStripeId();
        //Vérifier l'existence du compte
        if (empty($compte)) {
            $response=array(
                'code'=>1,
                'message'=>'Compte introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        //test charge on stripe
        \Stripe\Stripe::setApiKey(('sk_test_R6f3tuAsgbkH45pSm6tcr4s7'));
        $rep=\Stripe\Charge::create(array(
            "amount" => (string)$pack->getPrix(),
            "currency" => "eur",
            "customer" => (string)$customer,
            "description" => (string)$user->getEmail()
        ));
        if ($rep->failure_code==null){

        $compte->setSolde($compte->getSolde()+$pack->getCredits());
        $em->persist($compte);
        $em->flush();
        }else{
            //générer la réponse
            $response=array(
                'code'=>1,
                'message'=>'Probleme de paiement',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }


        //générer la réponse
        $response=array(
            'code'=>0,
            'message'=>'pack  achete !',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response, 200);
    }


}
