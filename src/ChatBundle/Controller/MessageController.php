<?php

namespace ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProfilBundle\Service\Validate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class MessageController extends Controller
{
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="send a Message to an other user",
     *     section="Chat"
     * )
     * @Route("/api/message/{id_sender}/{id_receiver}", name="Message_send")
     * @Method({"POST"})

     */

    public function createAction(Request $request,$id_sender,$id_receiver)

    {
        $data = $request->getContent();
        $message = $this->get('jms_serializer')->deserialize($data, 'ChatBundle\Entity\Message', 'json');
        $sender=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_sender);
        $receiver=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_receiver);
        $message->setRecepters($receiver);
        $message->setSenders($sender);
        $message->setDate(new \DateTime('now'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();
        return new JsonResponse('message envoyé', 200);
    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="recupérer la discussion ",
     *     section="Chat"
     * )
     * @Route("/api/message/{cuurent_user}/{friend}", name="Show_")
     * @Method({"GET"})

     */

    public function Show_discussion($cuurent_user,$friend)

    {
        $em = $this->getDoctrine()->getManager();
        $cuurent=$this->getDoctrine()->getRepository('AppBundle:User')->find($cuurent_user);
        $other=$this->getDoctrine()->getRepository('AppBundle:User')->find($friend);
         $message=$this->getDoctrine()->getRepository('ChatBundle:Message')->findBy(array('senders' => $cuurent,'recepters' => $other));
        $data=$this->get('jms_serializer')->serialize($message,'json');
        $response=json_decode($data);
        return new JsonResponse($response);

    }

}
