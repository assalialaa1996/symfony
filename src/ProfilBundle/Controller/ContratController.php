<?php

namespace ProfilBundle\Controller;
use ProfilBundle\Entity\Contrat;
use ProfilBundle\Entity\Prof;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProfilBundle\Service\Validate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ContratController extends Controller
{
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="récuperer un contrat",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The Contrat unique identifier."
     *         }
     *     },
     *     section="Contrats"
     * )
     * @Route("/api/Contrats/{id}",name="show_Contrat")
     * @Method({"GET"})
     */
    public function showcont($id)
    {
        $cont=$this->getDoctrine()->getRepository('ProfilBundle:Contrat')->find($id);
        if (empty($cont)){
            $response=array(
                'code'=>1,
                'message'=>'Contrat introuvable',
                'error'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        $data=$this->get('jms_serializer')->serialize($cont,'json');
        $response=array(
            'code'=>0,
            'message'=>'recuperé avec succés',
            'errors'=>null,
            'result'=>json_decode($data)
        );
        return new JsonResponse($response,200);
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="créer un Contrat",
     *     section="Contrats"
     * )
     * @Route("/api/Contrats", name="Contrat_create")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $data = $request->getContent();
        $cont = $this->get('jms_serializer')->deserialize($data, 'ProfilBundle\Entity\Contrat', 'json');
        $contrat=$this->getDoctrine()->getRepository('ProfilBundle:Contrat')->findOneBy(array('type' => $cont->getType()));
      if ($contrat != null){
          return new Response('existe déja', 201);
       }
               else{
        $em = $this->getDoctrine()->getManager();
        $em->persist($cont);
        $em->flush();
        $this->get('validator')->validate($cont);
        return new Response('ajoute', 200);
}}

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="récuperer la liste des Contrats",
     *     section="Contrats"
     * )
     * @Route("/api/Contrats", name="Contrats_list")
     * @Method({"GET"})
     */
    public function listAction()
    {
        $cont = $this->getDoctrine()->getRepository('ProfilBundle:Contrat')->findAll();
        $data = $this->get('jms_serializer')->serialize($cont, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="supprimer un Contrat",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The Contrat unique identifier."
     *         }
     *     },
     *     section="Contrats"
     * )
     * @Route("/api/Contrats/{id}",name="delete_contrat")
     * @Method({"DELETE"})
     */

    public function deletePost($id)
    {
        $cont=$this->getDoctrine()->getRepository('ProfilBundle:Contrat')->find($id);
        $profiles=$this->getDoctrine()->getRepository('ProfilBundle:Prof')->findAll();
        if (empty($cont)) {
            $response=array(
                'code'=>2,
                'message'=>'Contrat introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        $test=true;
        foreach ($profiles as $prof)
        {
            foreach ($prof->getListContrats() as $listContrat) {
                if ($cont==$listContrat){
                    $test=false;
                }
            }
        }
        if($test==false){
            $response=array(
                'code'=>1,
                'message'=>'impossible de supprimer un contrat attaché à un profil !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        $em=$this->getDoctrine()->getManager();
        $em->remove($cont);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'Contrat supprimé !',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="modifier un  Contrat",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="l'identifiant unoque du contrat."
     *         }
     *     },
     *     section="Contrats"
     * )
     * @param Request $request
     * @param $id
     * @param Validate $validate
     * @return JsonResponse
     * @Route("/api/Contrats/{id}",name="update_Contrat")
     * @Method({"PUT"})
     */

    public function updatePost(Request $request,$id)
    {
        $cont =new Contrat();
        $cont=$this->getDoctrine()->getRepository('ProfilBundle:Contrat')->find($id);
        if (empty($cont))
        {
            $response=array(
                'code'=>1,
                'message'=>'contrat introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        $body=$request->getContent();
        $data=$this->get('jms_serializer')->deserialize($body,'ProfilBundle\Entity\Contrat','json');
        if (!empty($reponse))
        {
            return new JsonResponse($reponse, Response::HTTP_BAD_REQUEST);
        }
        $cont->setType($data->getType());
        $em=$this->getDoctrine()->getManager();
        $em->persist($cont);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'contrat modifié!',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }
}
