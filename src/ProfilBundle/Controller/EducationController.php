<?php

namespace ProfilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProfilBundle\Service\Validate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ProfilBundle\Entity\Education;
class EducationController extends Controller
{
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="créer un education pour le profil",
     *     section="Educations"
     * )
     * @Route("/api/Educations/{iduser}", name="Education_create")
     * @Method({"POST"})
     */

    public function createAction(Request $request,$iduser)
    {
        $data = $request->getContent();
        $educ = $this->get('jms_serializer')->deserialize($data, 'ProfilBundle\Entity\Education', 'json');
            $em = $this->getDoctrine()->getManager();
            $em->persist($educ);
            $this->get('validator')->validate($educ);
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($iduser);
        $profile= $user->getProf();
        $profile->addEducation($educ);
        $em->persist($profile);
        $em->flush();
            return new JsonResponse('ajoute', 200);
        }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="delete one single Education from the profile and from entire DB",
     *     section="Educations"
     * )
     * @Route("/api/Educations/{id}/{iduser}",name="delete_Education")
     * @Method({"DELETE"})
     */

    public function deletePost($id,$iduser)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($iduser);
        $profile= $user->getProf();
        $educ=$this->getDoctrine()->getRepository('ProfilBundle:Education')->find($id);
        if (empty($educ)) {
            $response=array(
                'code'=>1,
                'message'=>'Education introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
$profile->removeEducation($educ);
$em=$this->getDoctrine()->getManager();
$em->persist($profile);
$em->remove($educ);
$em->flush();
$response=array(
            'code'=>0,
            'message'=>'Education supprimé !',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);

    }


    /**
     *@ApiDoc(
     *      resource=true,
     *     description="modifier un education",
     *     section="Educations"
     * )
     * @param Request $request
     * @param $id
     * @param Validate $validate
     * @return JsonResponse
     * @Route("/api/Educations/{id}",name="update_Education")
     * @Method({"PUT"})
     */

    public function updatePost(Request $request,$id)
    {

        $educ =new Education();
        $educ=$this->getDoctrine()->getRepository('ProfilBundle:Education')->find($id);
        if (empty($educ))
        {
            $response=array(
                'code'=>1,
                'message'=>'Education introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        $body=$request->getContent();
        $data=$this->get('jms_serializer')->deserialize($body,'ProfilBundle\Entity\Education','json');
        if (!empty($reponse))
        {
            return new JsonResponse($reponse, Response::HTTP_BAD_REQUEST);
        }
        $educ->setPlace($data->getPlace());
        $educ->setDegree($data->getDegree());
        $educ->setStart($data->getStart());
        $educ->setEnd($data->getEnd());
        $educ->setUniversity($data->getUniversity());
        $em=$this->getDoctrine()->getManager();
        $em->persist($educ);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'Education modifié!',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }
}
