<?php

namespace EntrepriseBundle\Controller;

use PaymentBundle\Entity\Charge;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
class ComptePackController extends Controller
{

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="acheter un pack",
     *     section="pack_compte"
     * )
     * @Route("/api/achat/{id_gc}/{id_plan}", name="pack_achat")
     * @Method({"POST"})
     */
    public function addPackToCompte( $id_gc,$id_plan)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_gc);
        $Compte=$user->getCompte();
        $customer=$Compte->getStripeId();
        //tester l'existence du compte
        if (empty($Compte)) {
            $response=array(
                'code'=>1,
                'message'=>'Compte introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        //retreive the customer from stripe
        \Stripe\Stripe::setApiKey("sk_test_R6f3tuAsgbkH45pSm6tcr4s7");
        $cus=\Stripe\Customer::retrieve((string)$customer);
        $plan=$this->getDoctrine()->getRepository('PaymentBundle:Plan')->find($id_plan);
        //tester l'existence du plan
        if (empty($plan)) {
            $response=array(
                'code'=>1,
                'message'=>'Plan introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        //test charge on stripe
        \Stripe\Stripe::setApiKey("sk_test_R6f3tuAsgbkH45pSm6tcr4s7");
        $rep=\Stripe\Charge::create(array(
            "amount" => (string)$plan->getAmmount(),
            "currency" => "eur",
            "customer" => (string)$customer,
            "description" => (string)$user->getEmail()
        ));
        if ($rep->failure_code==null){

            $pack=$plan->getPack();
        $Compte->setPacks($pack);
        $em = $this->getDoctrine()->getManager();
        $em->persist($Compte);
        $charge=new Charge();
        $charge->setCustomer($Compte)
            ->setStripeId($rep->id)
            ->setDate(new \DateTime())
            ->setAmount($plan->getAmmount())
            ->setSource($cus->default_source);
        $em->persist($charge);
        $em->flush();
        $this->get('validator')->validate($Compte);
        $response=array(
            'code'=>0,
            'message'=>'Pack ajouté au Compte',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response, 200);
        }else{
            $response=array(
                'code'=>1,
                'message'=>'probleme de carte',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="reset pack à FREE ",

     *     section="pack_compte"
     * )
     * @Route("/api/reset_pack/{id_gc}", name="pack_reset")

     * @Method({"PUT"})

     */

    public function resetPackOfCompte($id_gc)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_gc);
        $Compte=$user->getCompte();
        //tester l'existence du compte
        if (empty($Compte)) {
            $response=array(
                'code'=>1,
                'message'=>'Compte introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }


        $Compte->setPacks(null);
        //màj du BD
        $em = $this->getDoctrine()->getManager();
        $em->persist($Compte);
        $em->flush();
        $this->get('validator')->validate($Compte);
        $response=array(
            'code'=>0,
            'message'=>'Compte initialisé',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response, 200);
    }
}
