<?php

namespace PaymentBundle\Controller;

use PaymentBundle\Entity\DetailJetonPack;
use PaymentBundle\Entity\Jeton;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProfilBundle\Service\Validate;

class PackJetonController extends Controller
{
    /**
     * @ApiDoc(
     *      resource=true,
     *     description="ajouter un jeton à un pack",
     *     section="pack_jeton"
     * )
     * @param $id_pack
     * @param $id_jeton
     * @Route("/api/pack/{id_pack}/jeton/{id_jeton}", name="add_jeton_pack")
     * @Method({"PUT"})
     * @return Response
     */

    public function addJetonToPack($id_pack,$id_jeton,Request $request)

    {
        $body=$request->getContent();
        $data=$this->get('jms_serializer')->deserialize($body,'PaymentBundle\Entity\DetailJetonPack','json');
        $em = $this->getDoctrine()
            ->getManager();
        $jeton = $em->getRepository('PaymentBundle:Jeton')
            ->find($id_jeton);
        //verifier l'existence du Jeton
        if (empty($jeton)){
            $response=array(
                'code'=>1,
                'message'=>'Jeton introuvable',
                'error'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 201);
        }
        $pack = $em->getRepository('PaymentBundle:Pack')
            ->find($id_pack);
        //verifier l'existence du pack
        if (empty($pack)){
            $response=array(
                'code'=>1,
                'message'=>'Pack introuvable',
                'error'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 201);
        }
        $jeton_pack =new DetailJetonPack();
        $jeton_pack->setPacks($pack)
            ->setJetons($jeton)
            ->setPrix($data->getPrix());

        //persister la BD

        $em->persist($jeton_pack);
        $em->flush();

        $response=array(
            'code'=>0,
            'message'=>'Jeton Ajouté au Pack',
            'error'=>null,
            'result'=>null
        );

        return new JsonResponse($response,200);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *     description="supprimer un jeton d' un pack",
     *     section="pack_jeton"
     * )
     * @param $id_pack
     * @param $id_jeton
     * @Route("/api/pack/{id_pack}/jeton/{id_jeton}", name="delete_jeton_pack")
     * @Method({"DELETE"})
     * @return Response
     */

    public function deleteJetonFromPack($id_pack,$id_jeton)

    {
        $em = $this->getDoctrine()
            ->getManager();
        $jeton = $em->getRepository('PaymentBundle:Jeton')
            ->find($id_jeton);
     //verifier l'existence du jeton
        if (empty($jeton)){
            $response=array(
                'code'=>1,
                'message'=>'Jeton not found',
                'error'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 201);
        }
        $pack = $em->getRepository('PaymentBundle:Pack')
            ->find($id_pack);
        //verifier l'existence du pack
        if (empty($pack)){
            $response=array(
                'code'=>1,
                'message'=>'Pack not found',
                'error'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 201);
        }
        $jeton_pack =$this->getDoctrine()->getRepository('PaymentBundle:DetailJetonPack')->findOneBy(array('jetons' => $jeton,'packs' => $pack));
$jeton_pack->setJetons(null);
$jeton_pack->setPacks(null);
$jeton->removeDetail($jeton_pack);
$pack->removeDetail($jeton_pack);
        //màj la BD
        $em->remove($jeton_pack);
        $em->flush();
        //générer la réponse
        $response=array(
            'code'=>0,
            'message'=>'Jeton supprimé du Pack',
            'error'=>null,
            'result'=>null
        );
        return new JsonResponse($response, 200);
    }


    /**
     *@ApiDoc(
     *      resource=true,
     *     description="retourner list des  jetons que ne sont pas associé au pack donné",
     *     section="jetons"
     * )
     * @Route("/api/jeton/list/{id_pack}", name="jeton_list_possible")
     * @Method({"GET"})
     */

    public function listJetonToAdd($id_pack)
    {
    $all_jetons=$this->getDoctrine()->getRepository('PaymentBundle:Jeton')->findAll();
        $pack=$this->getDoctrine()->getRepository('PaymentBundle:Pack')->find($id_pack);
        $jeton1=$this->getDoctrine()->getRepository('PaymentBundle:DetailJetonPack')->findBypacks($pack);
      $array=array();
      $array1=array();
    foreach ($jeton1 as $c){
    $array1[]=$c->getJetons();
}
        foreach ($all_jetons as $A) {
            $test=true;
            foreach ($array1 as $B) {
                if ($A == $B){
                    $test=false;
                }
}
if ($test==true){
    $array[]=$A;
}
}
$data=$this->get('jms_serializer')->serialize($array,'json');
$response=json_decode($data);
return new JsonResponse($response,200);
}



}
