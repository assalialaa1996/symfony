<?php

namespace ProfilBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use ProfilBundle\Entity\Experience;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProfilBundle\Service\Validate;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExperienceController extends Controller
{
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="créer un experience et l'ajouter au profile",
     *     section="Experiences"
     * )
     * @Route("/api/Experiences/{iduser}", name="Experience_create")
     * @Method({"POST"})
     */
    public function createAction(Request $request,$iduser)
    {
        $data = $request->getContent();
        $exp = $this->get('jms_serializer')->deserialize($data, 'ProfilBundle\Entity\Experience', 'json');
        $em = $this->getDoctrine()->getManager();
        $em->persist($exp);
        $this->get('validator')->validate($exp);
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($iduser);
        $profile= $user->getProf();
        $profile->addExperience($exp);
        $em->persist($profile);
        $em->flush();
        return new JsonResponse('ajoute', 200);
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="suprimer l'experience ",
     *     section="Experiences"
     * )
     * @Route("/api/Experiences/{id}/{iduser}",name="delete_Experience")
     * @Method({"DELETE"})
     */

    public function deletePost($id,$iduser)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($iduser);
        $profile= $user->getProf();
        $exp=$this->getDoctrine()->getRepository('ProfilBundle:Experience')->find($id);
        if (empty($exp)) {
            $response=array(
                'code'=>1,
                'message'=>'Experience introuvable',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }
        $profile->removeExperience($exp);
        $em=$this->getDoctrine()->getManager();
        $em->persist($profile);
        $em->remove($exp);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'Experience supprimé du profil !',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="modifier un  Experience",
     *     section="Experiences"
     * )
     * @param Request $request
     * @param $id
     * @param Validate $validate
     * @return JsonResponse
     * @Route("/api/Experiences/{id}",name="update_Experience")
     * @Method({"PUT"})
     */

    public function updatePost(Request $request,$id)
    {

        $exp =new Experience();
        $exp=$this->getDoctrine()->getRepository('ProfilBundle:Experience')->find($id);
        if (empty($exp))
        {
            $response=array(
                'code'=>1,
                'message'=>'Experience introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response,200);
        }
        $body=$request->getContent();
        $data=$this->get('jms_serializer')->deserialize($body,'ProfilBundle\Entity\Experience','json');
        if (!empty($reponse))
        {
            return new JsonResponse($reponse, Response::HTTP_BAD_REQUEST);
        }
        $exp->setTitle($data->getTitle());
        $exp->setCompany($data->getCompany());
        $exp->setStart($data->getStart());
        $exp->setEnd($data->getEnd());
        $exp->setDescription($data->getDescription());
        $em=$this->getDoctrine()->getManager();
        $em->persist($exp);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'Experience modifié!',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }
}
