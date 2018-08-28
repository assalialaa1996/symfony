<?php

namespace EntrepriseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
class FavorisController extends Controller
{
    /**
     * @ApiDoc(
     *      resource=true,
     *     description="ajouter un profil aux favoris du grand compte",
     *     section="comptes_favoris"
     * )
     * @param $id_GC
     * @param $id_pro
     * @Route("/api/compte/{id_GC}/prof/{id_pro}", name="add_profile_compte_favoris")
     * @Method({"PUT"})
     * @return Response
     */
    public function addProfilToFavourite($id_GC,$id_pro)
    {
        $em = $this->getDoctrine()
            ->getManager();
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_GC);
        $compte=$user->getCompte();
        //tester l'existence du compte
        if (empty($compte)){
            $response=array(
                'code'=>1,
                'message'=>'compte introuvable',
                'error'=>null,
                'result'=>null
            );

            return new JsonResponse($response, 201);
        }
        //récuperer le profil avec son ID pas avec l'ID du USER !!!
        $profil = $em->getRepository('ProfilBundle:Prof')
            ->find($id_pro);
        if (empty($profil)){
            $response=array(
                'code'=>1,
                'message'=>'profil introuvable !',
                'error'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        foreach ($compte->getProfils() as $one){
            if($one==$profil ){
                $response=array(
                    'code'=>1,
                    'message'=>'profil déja au favoris !',
                    'error'=>null,
                    'result'=>null
                );
                return new JsonResponse($response, 201);
            }
        }
        $compte->addProfil($profil);
        $em->persist($compte);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'profil ajouté au favoris ',
            'error'=>null,
            'result'=>null
        );
        return new JsonResponse($response, 200);
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *     description="remove one single profile from one grand compte favourite",
     *     section="comptes_favoris"
     * )
     * @param $id_GC
     * @param $id_pro
     * @Route("/api/compte/{id_GC}/prof/{id_pro}", name="delete_profile_compte_favoris")
     * @Method({"DELETE"})
     * @return Response
     */

    public function removeProfilFromFavourite($id_GC,$id_pro)

    {
        $em = $this->getDoctrine()
            ->getManager();
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_GC);
        $compte=$user->getCompte();
        //tester l'existence du compte
        if (empty($compte)){
            $response=array(
                'code'=>1,
                'message'=>'Compte introuvable',
                'error'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        //récupérer le profil par son ID !
        $profil = $em->getRepository('ProfilBundle:Prof')
            ->find($id_pro);
        //tester l'existence du profil
        if (empty($profil)){
            $response=array(
                'code'=>1,
                'message'=>'profil introuvable',
                'error'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 201);
        }
        $compte->removeProfil($profil);
        $em->flush();
            $response=array(
                'code'=>0,
                'message'=>'profil supprimé du  Favoris',
                'error'=>null,
                'result'=>null
            );
            return new JsonResponse($response, 200);
    }
}
