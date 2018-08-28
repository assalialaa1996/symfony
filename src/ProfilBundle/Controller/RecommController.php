<?php

namespace ProfilBundle\Controller;

use ProfilBundle\Entity\Recomm;
use ProfilBundle\Entity\Prof;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProfilBundle\Service\Validate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class RecommController extends Controller
{

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="delete one single recommendation",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The recommendation unique identifier."
     *         }
     *     },
     *     section="recommendations"
     * )
     * @Route("/api/recommendations/{id}",name="delete_recommendation")
     * @Method({"DELETE"})
     */

    public function deletePost($id)
    {
        $comp=$this->getDoctrine()->getRepository('ProfilBundle:Recomm')->find($id);

        if (empty($comp)) {

            $response=array(

                'code'=>1,
                'message'=>'recommendation Not found !',
                'errors'=>null,
                'result'=>null

            );


            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $em=$this->getDoctrine()->getManager();
        $em->remove($comp);
        $em->flush();
        $response=array(

            'code'=>0,
            'message'=>'recommendation deleted !',
            'errors'=>null,
            'result'=>null

        );


        return new JsonResponse($response,200);



    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="Get one single recommendation",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The recommendation unique identifier."
     *         }
     *     },
     *     section="recommendations"
     * )
     * @Route("/api/recommendations/{id}",name="show_recommendation")
     * @Method({"GET"})
     */
    public function showComp($id)
    {
        $comp=$this->getDoctrine()->getRepository('ProfilBundle:Recomm')->find($id);


        if (empty($comp)){
            $response=array(
                'code'=>1,
                'message'=>'recommendation not found',
                'error'=>null,
                'result'=>null
            );

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data=$this->get('jms_serializer')->serialize($comp,'json');


        $response=array(

            'code'=>0,
            'message'=>'success',
            'errors'=>null,
            'result'=>json_decode($data)

        );

        return new JsonResponse($response,200);


    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="create a recommendation",
     *     section="recommendations"
     * )
     * @Route("/api/recommendations", name="recommendation_create")
     * @Method({"POST"})

     */

    public function createAction(Request $request)

    {

        $data = $request->getContent();

        $comp = $this->get('jms_serializer')->deserialize($data, 'ProfilBundle\Entity\Recomm', 'json');


        $em = $this->getDoctrine()->getManager();

        $em->persist($comp);

        $em->flush();
        $this->get('validator')->validate($comp);

        return new Response('', Response::HTTP_CREATED);

    }


    /**
     *@ApiDoc(
     *      resource=true,
     *     description="list des recommendations",
     *     section="recommendations"
     * )
     * @Route("/api/recommendations", name="recommendations_list")
     * @Method({"GET"})
     */

    public function listAction()

    {

        $comp = $this->getDoctrine()->getRepository('ProfilBundle:Recomm')->findAll();

        $data = $this->get('jms_serializer')->serialize($comp, 'json');


        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');


        return $response;

    }



    /**
     *@ApiDoc(
     *      resource=true,
     *     description="update one single recommendation",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The recommendation unique identifier."
     *         }
     *     },
     *     section="recommendations"
     * )
     * @param Request $request
     * @param $id
     * @param Validate $validate
     * @return JsonResponse
     * @Route("/api/recommendations/{id}",name="update_recommendation")
     * @Method({"PUT"})
     */
    public function updatePost(Request $request,$id)
    {
        $comp =new Recomm();
        $comp=$this->getDoctrine()->getRepository('ProfilBundle:Recomm')->find($id);

        if (empty($comp))
        {
            $response=array(

                'code'=>1,
                'message'=>'recommendation Not found !',
                'errors'=>null,
                'result'=>null

            );

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $body=$request->getContent();


        $data=$this->get('jms_serializer')->deserialize($body,'ProfilBundle\Entity\Recomm','json');


        //  $reponse= $validate->validateRequest($data);

        if (!empty($reponse))
        {
            return new JsonResponse($reponse, Response::HTTP_BAD_REQUEST);

        }
        $comp->setDescription($data->getDescription())
        ->setDate($data->getDate())
            ->setTitre($data->getTitre());



        $em=$this->getDoctrine()->getManager();
        $em->persist($comp);
        $em->flush();

        $response=array(

            'code'=>0,
            'message'=>'recommendation updated!',
            'errors'=>null,
            'result'=>null

        );

        return new JsonResponse($response,200);

    }

 

}
