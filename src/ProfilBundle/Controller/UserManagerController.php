<?php

namespace ProfilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
class UserManagerController extends Controller
{
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="récuperer la  liste des utilisateurs",
     *     section="users"
     * )
     * @Route("/api/users", name="userss_list")
     * @Method({"GET"})
     */

    public function listAction()

    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        $data = $this->get('jms_serializer')->serialize($users, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="récuperer la  liste des utilisateurs",
     *     section="users"
     * )
     * @Route("/api/number/users", name="number_list_user")
     * @Method({"GET"})
     */

    public function CountAction()

    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
       $i=0;
        $dt = new DateTime();
        foreach ($users as $user){
        $date2=$user->getLast_login();
        if (abs($dt-$date2)>1)
            $i=$i+1;
        }
        $response = new Response($i);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
