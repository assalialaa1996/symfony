<?php

namespace PaymentBundle\Controller;
use Stripe\Plan;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProfilBundle\Service\Validate;
use Symfony\Component\HttpFoundation\JsonResponse;
class PlanController extends Controller
{
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="créer un Plan et l'attacher au pack",
     *     section="Plans"
     * )
     * @Route("/api/Plans/{id}", name="Plan_create")
     * @Method({"POST"})
     */

    public function createAction(Request $request,$id)

    {
        $data = $request->getContent();
        $plan = $this->get('jms_serializer')->deserialize($data, 'PaymentBundle\Entity\Plan', 'json');
        $em = $this->getDoctrine()->getManager();
        $this->get('validator')->validate($plan);
        //créer le produit
        \Stripe\Stripe::setApiKey("sk_test_R6f3tuAsgbkH45pSm6tcr4s7");

        $rep=\Stripe\Product::create(array(
            "name" => (string)$plan->getNom(),
            "type" => "service",
        ));
        // ajouter le plan au Stripe
        \Stripe\Stripe::setApiKey("sk_test_R6f3tuAsgbkH45pSm6tcr4s7");
        $d=\Stripe\Plan::create(array(
            "amount" => $plan->getAmmount(),
            "interval" => (string)$plan->getIntervale(),
            "product" => array(
                "name" => (string)$rep->id
            ),
            "currency" => "eur",
        ));

        $plan->setStripeId((string)$d->id);
        $em->persist($plan);
        $em->flush();
        return new JsonResponse('plan cré avec succés', 200);
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="supprimer le plan du pack",
     *
     *     section="Plans"
     * )
     * @Route("/api/Plans/{id}",name="plan_delete")
     * @Method({"DELETE"})
     */

    public function deletePost($id)
    {
        $plan1=$this->getDoctrine()->getRepository('PaymentBundle:Plan')->find($id);
        //verifier l'existence du plan
        if (empty($plan1)) {
            $response=array(
                'code'=>1,
                'message'=>'Plan inexistant !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        //supprimer le plan du stripe
        \Stripe\Stripe::setApiKey("sk_test_R6f3tuAsgbkH45pSm6tcr4s7");
        $plan = \Stripe\Plan::retrieve((string)$plan1->getStripeId());
        $plan->delete();
        //supprimer le plan du BD
        $em=$this->getDoctrine()->getManager();
        $plan1->setPack(null);
        $em->remove($plan1);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'Plan supprimé !',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="retourner la liste des Plans",
     *     section="Plans"
     * )
     * @Route("/api/Plans", name="Plans_list_all")
     * @Method({"GET"})
     */

    public function listAction()

    {
        $plan = $this->getDoctrine()->getRepository('PaymentBundle:Plan')->findAll();
        $data = $this->get('jms_serializer')->serialize($plan, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="retourner la liste des Plans",
     *     section="Plans"
     * )
     * @Route("/api/Plans/{id}", name="Plans_list")
     * @Method({"GET"})
     */

    public function listPlansOfSelectedPack($id)

    {
        $pack=$this->getDoctrine()->getRepository('PaymentBundle:Pack')->find($id);
        $plan = $this->getDoctrine()->getRepository('PaymentBundle:Plan')->findby((array('pack'=>$pack)));
        $data = $this->get('jms_serializer')->serialize($plan, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
