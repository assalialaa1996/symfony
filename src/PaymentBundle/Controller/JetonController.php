<?php

namespace PaymentBundle\Controller;

use PaymentBundle\Entity\DetailJetonPack;
use PaymentBundle\Entity\Jeton;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;


class JetonController extends Controller
{
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="récuperer la liste des jetons",
     *
     *     section="jetons"
     * )
     * @Route("/api/jeton", name="jetons_list_all")
     * @Method({"GET"})
     */

    public function list_all_Jeton()

    {
        $jeton = $this->getDoctrine()->getRepository('PaymentBundle:Jeton')->findAll();
        $data = $this->get('jms_serializer')->serialize($jeton, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="créer un jeton",
     *     section="jetons"
     * )
     * @Route("/api/jeton", name="jeton_create")
     * @Method({"POST"})
     */

    public function createJeton( Request $request)

    {
        $data = $request->getContent();
        $jeton = $this->get('jms_serializer')->deserialize($data, 'PaymentBundle\Entity\Jeton', 'json');
        $jeton_existe=$this->getDoctrine()->getRepository('PaymentBundle:Jeton')->findOneBy(array('nom' => $jeton->getNom()));
        //verifier l'existence du jenton
        if ($jeton_existe != null){
            return new Response('existe déja', 201);
        }
        else{
        $em = $this->getDoctrine()->getManager();
        $em->persist($jeton);
        $em->flush();
        $this->get('validator')->validate($jeton);
            return new Response('ajouté avec succés', 200);
        }}

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="supprimer un jeton",
     *     section="jetons"
     * )
     * @Route("/api/jeton/{id_jeton}", name="jeton_delete")
     * @Method({"DELETE"})
     */

    public function removeJeton($id_jeton)

    {
        $jeton=$this->getDoctrine()->getRepository('PaymentBundle:Jeton')->find($id_jeton);
        //verifier l'existence du jeton
        if (empty($jeton)){
            $response=array(
                'code'=>1,
                'message'=>'jeton introuvable',
                'error'=>null,
                'result'=>null
            );
            return new JsonResponse($response,201);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($jeton);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'jeton supprimé',
            'error'=>null,
            'result'=>null
        );

        return new JsonResponse($response,200);
    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="modifier un jeton",
     *     section="jetons"
     * )
     * @Route("/api/jeton/{id_jeton}/{id_pack}/{prix}", name="jeton_update")
     * @Method({"PUT"})
     */

    public function updateJeton($id_jeton,Request $request,$id_pack,$prix)
    {
        $jeton=$this->getDoctrine()->getRepository('PaymentBundle:Jeton')->find($id_jeton);
        //verifier l'existence du jeton
        if (empty($jeton)){
            $response=array(
                'code'=>1,
                'message'=>'jeton introuvable !',
                'error'=>null,
                'result'=>null
            );
            return new JsonResponse($response,201);
        }
        $em = $this->getDoctrine()->getManager();
        //création du jeton :
        $data = $request->getContent();
        $pack=$this->getDoctrine()->getRepository('PaymentBundle:Pack')->find($id_pack);
        $jeton_new = $this->get('jms_serializer')->deserialize($data, 'PaymentBundle\Entity\Jeton', 'json');
        $jeton->setNbreProf($jeton_new->getNbreProf())
              ->setNom($jeton_new->getNom());
        $detail=$this->getDoctrine()->getRepository('PaymentBundle:DetailJetonPack')->findOneBy(array('packs' => $pack,'jetons'=>$jeton) );
        $detail->setPrix($prix);
        //persister la BD
        $em->persist($jeton);
        $em->persist($detail);
        $em->flush();
        //génération du réponse
        $response=array(
            'code'=>0,
            'message'=>'jeton updated',
            'error'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="retourner list des  jetons à partir d'un pack donné",
     *     section="jetons"
     * )
     * @Route("/api/jeton/{id_pack}", name="jeton_list")
     * @Method({"GET"})
     */

    public function listJetonformPack($id_pack)

    {
        $pack=$this->getDoctrine()->getRepository('PaymentBundle:Pack')->find($id_pack);
        $jeton=$this->getDoctrine()->getRepository('PaymentBundle:DetailJetonPack')->findBypacks($pack);
        $data=$this->get('jms_serializer')->serialize($jeton,'json');
        $response=array(
            'code'=>0,
            'message'=>'jeton trouvé',
            'error'=>null,
            'result'=>json_decode($data)
        );

        return new JsonResponse($response,200);
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="modifier un  jeton",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The jeton unique identifier."
     *         }
     *     },
     *     section="jetons"
     * )
     * @param Request $request
     * @param $id
     * @param Validate $validate
     * @return JsonResponse
     * @Route("/api/onejeton/{id}",name="update_one_jeton")
     * @Method({"PUT"})
     */
    public function updatethisJeton(Request $request,$id)
    {
        $jeton =new Jeton();
        $jeton=$this->getDoctrine()->getRepository('PaymentBundle:Jeton')->find($id);
        //verifier l'existence du jeton
        if (empty($jeton))
        {
            $response=array(
                'code'=>1,
                'message'=>'jeton introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        $body=$request->getContent();
        $data=$this->get('jms_serializer')->deserialize($body,'PaymentBundle\Entity\Jeton','json');
        if (!empty($reponse))
        {
            return new JsonResponse($reponse, 401);

        }
        $jeton->setNbreProf($data->getNbreProf());
        $jeton->setNom($data->getNom());
        //persister la BD
        $em=$this->getDoctrine()->getManager();
        $em->persist($jeton);
        $em->flush();
        //générer la réponse
        $response=array(
            'code'=>0,
            'message'=>'jeton modifié!',
            'errors'=>null,
            'result'=>null
        );

        return new JsonResponse($response,200);

    }
}
