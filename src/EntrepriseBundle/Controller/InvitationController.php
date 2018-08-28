<?php

namespace EntrepriseBundle\Controller;

use EntrepriseBundle\Entity\Invitation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
class InvitationController extends Controller
{

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="envoyer une invitation à un profil",
     *     section="invitations"
     * )
     * @Route("/api/invi/{id_gc}/{id_pro}", name="invitation_send")
     * @Method({"PUT"})
     */

    public function sendInvitation($id_gc,$id_pro)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_gc);
        $Compte=$user->getCompte();
        if ($Compte->getSolde()==0){
            return new JsonResponse('solde insuffisant ', 201);
        }
        $Profil=$this->getDoctrine()->getRepository('ProfilBundle:Prof')->find($id_pro);
        $test=$this->getDoctrine()->getRepository('EntrepriseBundle:Invitation')->findOneBy(array('profils'=> $Profil,'grand_comptes'=> $Compte));
        if (!empty($test)){
            return new JsonResponse('exixte deja ', 201);
        }
        $invi=new Invitation();
        $invi->setProfils($Profil)
            ->setGrandComptes($Compte)
            ->setDate(new \DateTime());
        $em = $this->getDoctrine()->getManager();
        $Compte->setSolde($Compte->getSolde()-1);
        $em->persist($Compte);
        $em->persist($invi);
        $em->flush();
        $this->get('validator')->validate($invi);
        return new  JsonResponse('profile débloqué', 200);

    }

//

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="accepter l' invitation",

     *     section="invitations"
     * )
     * @Route("/api/invi/{id_gc}/{id_pro}/acc", name="invitation_accept")

     * @Method({"POST"})

     */

    public function acceptInvitation( $id_gc,$id_pro)

    {



        $Compte=$this->getDoctrine()->getRepository('EntrepriseBundle:Compte')->find($id_gc);
        $Profil=$this->getDoctrine()->getRepository('ProfilBundle:Prof')->find($id_pro);
        $invi=$this->getDoctrine()->getRepository('EntrepriseBundle:Invitation')->findOneBy(array('profils' => $Profil,'grand_comptes' => $Compte));
        $invi->setEtat(true);

        $em = $this->getDoctrine()->getManager();

        $em->persist($invi);

        $em->flush();


        return new Response('', Response::HTTP_CREATED);

    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="refuser l' invitation",

     *     section="invitations"
     * )
     * @Route("/api/reject/{id_gc}/{id_pro}", name="invitation_Reject")

     * @Method({"DELETE"})

     */

    public function RejectInvitation( $id_gc,$id_pro)

    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_gc);
        $compte=$user->getCompte();
        $Profil=$this->getDoctrine()->getRepository('ProfilBundle:Prof')->find($id_pro);
        $invi=$this->getDoctrine()->getRepository('EntrepriseBundle:Invitation')->findOneBy(array('profils' => $Profil,'grand_comptes' => $compte));
        $em = $this->getDoctrine()->getManager();
        $em->remove($invi);
        $em->flush();
        return new JsonResponse('ok', 200);

    }

}
