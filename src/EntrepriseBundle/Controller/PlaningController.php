<?php

namespace EntrepriseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EntrepriseBundle\Service\Validate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
class PlaningController extends Controller
{
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="créer un planing",
     *     section="Planing"
     * )
     * @Route("/api/Planing/{iduser}", name="Planing_create")
     * @Method({"POST"})
     */

    public function createAction(Request $request,$iduser)
    {
        $data = $request->getContent();
        $plan = $this->get('jms_serializer')->deserialize($data, 'EntrepriseBundle\Entity\Planing', 'json');
        $em = $this->getDoctrine()->getManager();
        $em->persist($plan);
        $this->get('validator')->validate($plan);
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($iduser);
        $compte= $user->getCompte();
        $compte->addPlaning($plan);
        $em->persist($compte);
        $em->flush();
        return new JsonResponse('ajoute', 200);
    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="delete one single Planing from the compte and from entire DB",
     *     section="Planing"
     * )
     * @Route("/api/Planing/{id}/{iduser}",name="delete_planing")
     * @Method({"DELETE"})
     */

    public function deletePost($id,$iduser)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($iduser);
        $compte= $user->getCompte();
        $paln=$this->getDoctrine()->getRepository('EntrepriseBundle:Planing')->find($id);
        if (empty($paln)) {
            $response=array(
                'code'=>1,
                'message'=>'Planing  introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        $compte->removePlaning($paln);
        $em=$this->getDoctrine()->getManager();
        $em->persist($compte);
        $em->remove($paln);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'Planing  supprimé !',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);

    }

    /**
     * @ApiDoc(
     *      resource=true,
     *     description="supprimer un profil d'un planing",
     *     requirements={
     *         {
     *             "name"="id_pr",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The user unique identifier."
     *         },
     *         {
     *             "name"="id_pl",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The planing  unique identifier."
     *         }
     *     },
     *     section="Planing"
     * )
     * @param $id_p
     * @param $id_c
     * @return JsonResponse
     * @Route("/api/Planing1/{id_pr}/{id_pl}",name="delete_profile from planing")
     * @Method({"DELETE"})

     */

    public function deleteprof($id_pr,$id_pl)
    {
        $profile=$this->getDoctrine()->getRepository('ProfilBundle:Prof')->find($id_pr);
        $pl=$this->getDoctrine()->getRepository('EntrepriseBundle:Planing')->find($id_pl);
        $em=$this->getDoctrine()->getManager();
        $pl->removeProfile($profile);
        $em->persist($pl);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'profil  supprimé du planing !',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }
    /**
     * @ApiDoc(
     *      resource=true,
     *     description="supprimer un profil d'un planing",
     *     requirements={
     *         {
     *             "name"="id_pr",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The user unique identifier."
     *         },
     *         {
     *             "name"="id_pl",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The planing  unique identifier."
     *         }
     *     },
     *     section="Planing"
     * )
     * @param $id_p
     * @param $id_c
     * @return JsonResponse
     * @Route("/api/Planing2/{id_pr}/{id_pl}",name="add_profile to planing")
     * @Method({"DELETE"})

     */

    public function addprof($id_pr,$id_pl)
    {
        $profile=$this->getDoctrine()->getRepository('ProfilBundle:Prof')->find($id_pr);
        $pl=$this->getDoctrine()->getRepository('EntrepriseBundle:Planing')->find($id_pl);
        $em=$this->getDoctrine()->getManager();
        foreach ($pl->getProfiles() as $pr1){
            if ($pr1 == $profile){
                $response=array(
                    'code'=>0,
                    'message'=>'profil existe déja',
                    'errors'=>null,
                    'result'=>null
                );
                return new JsonResponse($response,201);
            }
        }
      $pl->addProfile($profile);
        $em->persist($pl);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'profil  ajouté  planing !',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }

}
