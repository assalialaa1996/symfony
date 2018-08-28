<?php

namespace ProfilBundle\Controller;

use ProfilBundle\Entity\Competence;
use ProfilBundle\Entity\Prof;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProfilBundle\Service\Validate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
class CompetenceController extends Controller
{
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="supprimer un competence",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="l'identifiant unique du compétence."
     *         }
     *     },
     *     section="competences"
     * )
     * @Route("/api/competences/{id}",name="delete_commpetence")
     * @Method({"DELETE"})
     */

    public function deleteCompetence($id)
    {
        $comp=$this->getDoctrine()->getRepository('ProfilBundle:Competence')->find($id);
        $test=$this->getDoctrine()->getRepository('ProfilBundle:niveau')->findOneBycompetences($comp);
        if(!empty($test)){
            $response=array(
                'code'=>1,
                'message'=>'impossible de supprimé un compétence attaché à un profil !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }

        if (empty($comp)) {
            $response=array(
                'code'=>2,
                'message'=>'competence introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        $em=$this->getDoctrine()->getManager();
        $em->remove($comp);
        $em->flush();
        $response=array(

            'code'=>0,
            'message'=>'competence supprimé !',
            'errors'=>null,
            'result'=>null

        );
        return new JsonResponse($response,200);
    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="Retourner le competence avec l'ID donné",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="l'identifiant unique du compétence."
     *         }
     *     },
     *     section="competences"
     * )
     * @Route("/api/competences/{id}",name="show_competence")
     * @Method({"GET"})
     */
    public function showComp($id)
    {
        $comp=$this->getDoctrine()->getRepository('ProfilBundle:Competence')->find($id);
        if (empty($comp)){
            $response=array(
                'code'=>1,
                'message'=>'competence introuvable',
                'error'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 201);
        }
        $data=$this->get('jms_serializer')->serialize($comp,'json');
        $response=array(
            'code'=>0,
            'message'=>'compétence trouvé',
            'errors'=>null,
            'result'=>json_decode($data)
        );
        return new JsonResponse($response,200);
    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="créer un  competence",
     *     section="competences"
     * )
     * @Route("/api/competences", name="competence_create")
     * @Method({"POST"})

     */
    public function createAction(Request $request)
    {
        $data = $request->getContent();
        $comp = $this->get('jms_serializer')->deserialize($data, 'ProfilBundle\Entity\Competence', 'json');
        $c=$this->getDoctrine()->getRepository('ProfilBundle:Competence')->findOneBy(array('libelle' => $comp->getLibelle()));
        if ($c != null){
            return new Response('existe déja', 201);
        }
        else{
        $em = $this->getDoctrine()->getManager();
        $em->persist($comp);
        $em->flush();
        $this->get('validator')->validate($comp);
        return new Response('ajoute', 200);
}}

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="récuperer la  liste des competences",
     *     section="competences"
     * )
     * @Route("/api/competences", name="competences_list")
     * @Method({"GET"})
     */

    public function listAction()

    {
        $comp = $this->getDoctrine()->getRepository('ProfilBundle:Competence')->findAll();
        $data = $this->get('jms_serializer')->serialize($comp, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="modifier un competence donné",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="l'identidiant unique du compétence."
     *         }
     *     },
     *     section="competences"
     * )
     * @param Request $request
     * @param $id
     * @param Validate $validate
     * @return JsonResponse
     * @Route("/api/competences/{id}",name="update_competence")
     * @Method({"PUT"})
     */
    public function updatePost(Request $request,$id)
    {
        $comp =new Competence();
        $comp=$this->getDoctrine()->getRepository('ProfilBundle:Competence')->find($id);
        if (empty($comp))
        {
            $response=array(
                'code'=>1,
                'message'=>'competence introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        $body=$request->getContent();
        $data=$this->get('jms_serializer')->deserialize($body,'ProfilBundle\Entity\Competence','json');
        if (!empty($reponse))
        {
            return new JsonResponse($reponse, Response::HTTP_BAD_REQUEST);
        }
        $comp->setLibelle($data->getLibelle());
        $em=$this->getDoctrine()->getManager();
        $em->persist($comp);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'competence modifié avec succés!',
            'errors'=>null,
            'result'=>null

        );
        return new JsonResponse($response,200);
    }

    //c'est un test pour le moment

    /**
     * @ApiDoc(
     *      resource=true,
     *     description="supprimer un compétence d'un profil",
     *     requirements={
     *         {
     *             "name"="id_p",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The user unique identifier."
     *         },
     *         {
     *             "name"="id_c",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The competence unique identifier."
     *         }
     *     },
     *     section="profiles"
     * )
     * @param $id_p
     * @param $id_c
     * @return JsonResponse
     * @Route("/api/profile/{id_p}/{id_c}",name="delete_commpetence from user")
     * @Method({"DELETE"})

     */

    public function deletecomp($id_p,$id_c)
    {
        $user=$this->getDoctrine()->getRepository('ProfilBundle:Competence')->find($id_p);
        $profile=$user->getProf();
        $comp=$this->getDoctrine()->getRepository('ProfilBundle:Competence')->find($id_c);
        $em=$this->getDoctrine()->getManager();
        $profile->remove($comp);
        $em->persist($profile);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'competence supprimé du profil !',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="liste des competences à ajouter au profile",
     *     section="competences"
     * )
     * @Route("/api/competences/add", name="competences_list_add")
     * @Method({"GET"})
     */

    public function listOnlyCompToAdd($id)

    {
        $user=$this->getDoctrine()->getRepository('ProfilBundle:Competence')->find($id);
        $profile=$user->getProf();
        $all_comps=$this->getDoctrine()->getRepository('ProfilBundle:Competence')->findAll();
        $comp=$this->getDoctrine()->getRepository('ProfilBundle:niveau')->findByprofils($profile);
        $array=array();
        $array1=array();
        foreach ($comp as $c){
            $array1[]=$c->getCompetences();
        }
        foreach ($all_comps as $A) {
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
        return new JsonResponse($response);
    }

}

