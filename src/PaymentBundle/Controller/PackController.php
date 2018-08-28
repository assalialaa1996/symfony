<?php

namespace PaymentBundle\Controller;

use PaymentBundle\Entity\Pack;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProfilBundle\Service\Validate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
class PackController extends Controller
{
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="supprimer un Pack",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="l'identifiant unique du pack."
     *         }
     *     },
     *     section="Packs"
     * )
     * @Route("/api/Packs/{id}",name="pack")
     * @Method({"DELETE"})
     */

    public function deletePack($id)
    {
        $pack=$this->getDoctrine()->getRepository('PaymentBundle:Pack')->find($id);

        if (empty($pack)) {
            $response=array(
                'code'=>2,
                'message'=>'Pack introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        $test=true;



        $em=$this->getDoctrine()->getManager();
        $em->remove($pack);
        $em->flush();
        //générer la réponse
        $response=array(
            'code'=>0,
            'message'=>'Pack supprimé !',
            'errors'=>null,
            'result'=>null
        );


        return new JsonResponse($response,200);

    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="Retourner un  Pack par son ID",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="l'identifiant unique du pack."
     *         }
     *     },
     *     section="Packs"
     * )
     * @Route("/api/Packs/{id}",name="show_Pack")
     * @Method({"GET"})
     */
    public function showPack($id)
    {

        $pack=$this->getDoctrine()->getRepository('PaymentBundle:Pack')->find($id);
        //vérifier l"existence du pack
        if (empty($pack)){
            $response=array(
                'code'=>1,
                'message'=>'Pack introuvable',
                'error'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        $data=$this->get('jms_serializer')->serialize($pack,'json');
        //générer la réponse
        $response=array(
            'code'=>0,
            'message'=>'pack recuperé avec succés',
            'errors'=>null,
            'result'=>json_decode($data)
        );
        return new JsonResponse($response,200);
    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="créer un  Pack",
     *     section="Packs"
     * )
     * @Route("/api/Packs", name="Pack_create")
     * @Method({"POST"})

     */
    public function createAction(Request $request)

    {
        $data = $request->getContent();
        $pack = $this->get('jms_serializer')->deserialize($data, 'PaymentBundle\Entity\Pack', 'json');

        $em = $this->getDoctrine()->getManager();
        $this->get('validator')->validate($pack);
        $em->persist($pack);
        $em->flush();
        return new JsonResponse('ajouté avec succés', 200);
    }


    /**
     *@ApiDoc(
     *      resource=true,
     *     description="retourner la liste des Packs",
     *     section="Packs"
     * )
     * @Route("/api/Packs", name="Packs_list")
     * @Method({"GET"})
     */

    public function listAction()

    {
        $pack = $this->getDoctrine()->getRepository('PaymentBundle:Pack')->findAll();
        $data = $this->get('jms_serializer')->serialize($pack, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }



    /**
     *@ApiDoc(
     *      resource=true,
     *     description="modifier un  Pack",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="l'identifiant unique du pack."
     *         }
     *     },
     *     section="Packs"
     * )
     * @param Request $request
     * @param $id
     * @param Validate $validate
     * @return JsonResponse
     * @Route("/api/Packs/{id}",name="update_Pack")
     * @Method({"PUT"})
     */
    public function updatePack(Request $request,$id)
    {
        $pack =new Pack();
        $pack=$this->getDoctrine()->getRepository('PaymentBundle:Pack')->find($id);
        //tester l'existence du pack
        if (empty($pack))
        {
            $response=array(
                'code'=>1,
                'message'=>'Pack introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        $body=$request->getContent();
        $data=$this->get('jms_serializer')->deserialize($body,'PaymentBundle\Entity\Pack','json');

        if (!empty($reponse))
        {
            return new JsonResponse($reponse, Response::HTTP_BAD_REQUEST);
        }
        //modifier le pack
        $pack->setNom($data->getNom());
        $pack->setDescription($data->getDescription());
        $pack->setPrix($data->getPrix());
        //persister la BD
        $em=$this->getDoctrine()->getManager();
        $em->persist($pack);
        $em->flush();
        //modifier la pack dans STRIPE
        \Stripe\Stripe::setApiKey("sk_test_R6f3tuAsgbkH45pSm6tcr4s7");
        $product = \Stripe\Product::retrieve((string)$pack->getStripeId());
        $product->name = (string) $data->getNom();
        $product->save();
        //générer la reponse
        $response=array(
            'code'=>0,
            'message'=>'Pack à jour!',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }
}

