<?php

namespace ProfilBundle\Controller;

use AppBundle\Entity\File;
use AppBundle\Entity\User;
use ProfilBundle\Entity\niveau;
use ProfilBundle\Entity\Prof;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ProfilBundle\Service\Validate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class ProfController extends Controller
{

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="Get one single profile",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The profile unique identifier."
     *         }
     *     },
     *     section="profiles"
     * )
     * @Route("/api/profiles/{id}",name="show_profile")
     * @Method({"GET"})
     */
    public function showPost($id)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $profile= $user->getProf();

/*
        if (empty($post)){
            $response=array(
                'code'=>1,
                'message'=>'profil not found',
                'error'=>null,
                'result'=>null
            );

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }
*/
        $data=$this->get('jms_serializer')->serialize($profile,'json');


        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');


        return $response;


    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="Get one single profile from its ID",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The profile unique identifier."
     *         }
     *     },
     *     section="profiles"
     * )
     * @Route("/api/profiles/{id}/GC/{id_gc}",name="show_profile_from_grand_compte")
     * @Method({"GET"})
     */
    public function showProfileFromId($id,$id_gc)
    {
        $profile=$this->getDoctrine()->getRepository('ProfilBundle:Prof')->find($id);
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_gc);
        $compte= $user->getCompte();
        $test=false;
        foreach ($compte->getInvitations() as $prof)
        {
            if ($prof->getProfils()==$profile){
                $test=true;
            }
        }
      if($test==true){
            $data=$this->get('jms_serializer')->serialize($profile,'json');
            $response = new Response($data);
            $response->headers->set('Content-Type', 'application/json');
            return $response;

        }else{
            return new JsonResponse('non auth', Response::HTTP_CREATED);
            }


    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="create one single profile",

     *     section="profiles"
     * )
     * @Route("/api/profiles", name="profile_create")

     * @Method({"POST"})

     */

    public function createAction(Request $request)

    {
        $data = $request->getContent();
        $article = $this->get('jms_serializer')->deserialize($data, 'ProfilBundle\Entity\Prof', 'json');
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();
        $this->get('validator')->validate($article);

        return new Response('', Response::HTTP_CREATED);

    }


    /**
     *@ApiDoc(
     *      resource=true,
     *     description="Get list profile",
     *
     *     section="profiles"
     * )
     * @Route("/api/profiles", name="profile_list")
     * @Method({"GET"})
     */

    public function listAction()

    {

        $articles = $this->getDoctrine()->getRepository('ProfilBundle:Prof')->findAll();

        $data = $this->get('jms_serializer')->serialize($articles, 'json');


        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');


        return $response;

    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="delete one single profile",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The profile unique identifier."
     *         }
     *     },
     *     section="profiles"
     * )
     * @Route("/api/profiles/{id}",name="delete_profile")
     * @Method({"DELETE"})
     */

    public function deletePost($id)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $post= $user->getProf();


        if (empty($post)) {

            $response=array(

                'code'=>1,
                'message'=>'profile Not found !',
                'errors'=>null,
                'result'=>null

            );


            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $em=$this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        $response=array(

            'code'=>0,
            'message'=>'profile deleted !',
            'errors'=>null,
            'result'=>null

        );


        return new JsonResponse($response,200);



    }


    /**
     *@ApiDoc(
     *      resource=true,
     *     description="update one single profile",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The profile unique identifier."
     *         }
     *     },
     *     section="profiles"
     * )
     * @param Request $request
     * @param $id
     * @param Validate $validate
     * @return JsonResponse
     * @Route("/api/profiles/{id}",name="update_post")
     * @Method({"PUT"})
     */
    public function updatePost(Request $request,$id)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $profile= $user->getProf();



        if (empty($profile))
        {
            $response=array(

                'code'=>1,
                'message'=>'Profile Not found !',
                'errors'=>null,
                'result'=>null

            );

            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $body=$request->getContent();


        $data=$this->get('jms_serializer')->deserialize($body,'ProfilBundle\Entity\Prof','json');


      //  $reponse= $validate->validateRequest($data);

        if (!empty($reponse))
        {
            return new JsonResponse($reponse, Response::HTTP_BAD_REQUEST);

        }
if($data->getNom()!=null) $profile->setNom($data->getNom());
        if($data->getPrenom()!=null) $profile->setPrenom($data->getPrenom());
        if($data->getNumTel()!=null) $profile->setNumTel($data->getNumTel());
        if($data->getDatNaiss()!=null) $profile->setDatNaiss($data->getDatNaiss());
        if($data->getVerif()!=null) $profile->setVerif($data->getVerif());
        if($data->getSexe()!=null) $profile->setSexe($data->getSexe());
        if($data->getDescription()!=null) $profile->setDescription($data->getDescription());
        if($data->getPays()!=null) $profile->setPays($data->getPays());
        if($data->getSalMin()!=null) $profile->setSalMin($data->getSalMin());
        if($data->getAdresse()!=null) $profile->setAdresse($data->getAdresse());

        $em=$this->getDoctrine()->getManager();
        $em->persist($profile);
        $em->flush();

        $response=array(

            'code'=>0,
            'message'=>'Profile updated!',
            'errors'=>null,
            'result'=>null

        );

        return new JsonResponse($response,200);

    }

//*///

    /**
     * @ApiDoc(
     *      resource=true,
     *     description="ajouter un contrat au profile",
     *     section="profiles"
     * )
     * @param $id_pro
     * @param $id_cont
     * @Route("/api/profil/{id_pro}/contrat/{id_cont}", name="add_conrat_profile")
     * @Method({"PUT"})
     * @return Response
     */

    public function addContratToProfile($id_pro,$id_cont)

    {
        $em = $this->getDoctrine()
            ->getManager();


        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_pro);
        $profile= $user->getProf();
        $cont = $em->getRepository('ProfilBundle:Contrat')
            ->find($id_cont);

        $profile->addListContrat($cont);

        $em->flush();

        return new JsonResponse('OK',200);


    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="delete one single contrat from profile",
     *
     *     section="profiles"
     * )
     * @Route("/api/profil/{id}/cont/{id_cont}",name="delete_profile")
     * @Method({"DELETE"})
     */

    public function deleteContratFromProfile($id,$id_cont)
    {

        // On récupère l'EntityManager
        $em = $this->getDoctrine()
            ->getManager();
        //
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $profile= $user->getProf();
        $contrat = $em->getRepository('ProfilBundle:Contrat')
            ->find($id_cont);

        $profile->removeListContrat($contrat);
        $em->flush();

        return new Response('OK');
    }

    /**
     * @ApiDoc(
     *      resource=true,
     *     description="add one single competence to  profile",
     *     section="profiles"
     * )
     * @param $id_pro
     * @param $id_comp
     * @Route("/api/profil/{id_user}/comp/{id_comp}", name="add_comp_profile")
     * @Method({"POST"})
     * @return Response
     */

    public function addCompToProfile($id_user,$id_comp,Request  $request )

    {
        $data=$request->getContent();
        $niveau = $this->get('jms_serializer')->deserialize($data, 'ProfilBundle\Entity\niveau', 'json');
        $em = $this->getDoctrine()
            ->getManager();

        $user=$em->getRepository('AppBundle:User')
            ->find($id_user);
        $profile = $user->getProf();


        $comp=$em->getRepository('ProfilBundle:Competence')
            ->find($id_comp);
        $existe=$this->getDoctrine()->getRepository('ProfilBundle:niveau')->findOneBy(array('competences'=>$comp,'profils'=>$profile));
        if(!empty($existe)){
            $response=array(

                'code'=>1,
                'message'=>'Competence déja attribuée !',
                'errors'=>null,
                'result'=>null

            );

            return new JsonResponse($response,201);
        }
        $profcomp=new niveau();
        $profcomp->setCompetences($comp);
        $profcomp->setProfils($profile);
        $profcomp->setValeur($niveau->getValeur());
        $em->persist($profcomp);



        $em->flush();

        return new JsonResponse('OK',200);


    }

    /**
     * @ApiDoc(
     *      resource=true,
     *     description="add one single salary to one profile",
     *     section="profiles"
     * )
     * @param $id_pro
     * @param $id_cont
     * @Route("/api/profil/{id_pro}/salaire/{id_sal}", name="add_sal_profile")
     * @Method({"PUT"})
     * @return Response
     */

    public function addSalaireToProfile($id_pro,$id_sal)

    {
        $em = $this->getDoctrine()
            ->getManager();


        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_pro);
        $profile= $user->getProf();
        $sal = $em->getRepository('ProfilBundle:Salaire')
            ->find($id_sal);

        $profile->setSalaire($sal);

        $em->flush();

        return new JsonResponse('OK',200);


    }

    /**
     * @ApiDoc(
     *      resource=true,
     *     description="add one recomm to one profile",
     *     section="profiles"
     * )
     * @param $id_pro
     * @param $id_cont
     * @Route("/api/profil/{id_pro}/recomm/{id_recomm}", name="add_recomm_profile")
     * @Method({"PUT"})
     * @return Response
     */

    public function addRecommToProfile($id_pro,$id_recomm,Request $request)

    {
        $data = $request->getContent();

        $rec = $this->get('jms_serializer')->deserialize($data, 'ProfilBundle\Entity\Recomm', 'json');


        $em = $this->getDoctrine()->getManager();

        $em->persist($rec);

        $em->flush();
        $this->get('validator')->validate($rec);
        //

        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_pro);
        $profile= $user->getProf();
        $profile->addRecomm($rec);

        $em->flush();

        return new Response('OK');


    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="delete one single Recomm from profile",
     *
     *     section="profiles"
     * )
     * @Route("/api/profil/{id}/recomm/{id_rec}",name="delete_profile_recomm")
     * @Method({"DELETE"})
     */

    public function deleteRecommFromProfile($id,$id_rec)
    {

        // On récupère l'EntityManager
        $em = $this->getDoctrine()
            ->getManager();
        //
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $profile= $user->getProf();
        $recomm = $em->getRepository('ProfilBundle:Recomm')
            ->find($id_rec);

        $profile->removeRecomm($recomm);
        $em->remove($recomm);
        $em->flush();

        return new Response('OK');
    }



    /**
     *@ApiDoc(
     *      resource=true,
     *     description="delete one single city from profile",
     *
     *     section="profiles"
     * )
     * @Route("/api/profil/{id}/comp/{idc}",name="delete_profile_comp")
     * @Method({"DELETE"})
     */

    public function deleteCompFromProfile($id,$idc)
    {
        $em = $this->getDoctrine()
            ->getManager();
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $profil= $user->getProf();
        $comp = $em->getRepository('ProfilBundle:Competence')
            ->find($idc);
        $niveau =$this->getDoctrine()->getRepository('ProfilBundle:niveau')->findOneBy(array('profils' => $profil,'competences' => $comp));
        $niveau->setCompetences(null);
        $niveau->setProfils(null);
        $profil->removeListComp($niveau);
        $em->remove($niveau);
        $em->flush();
        return new Response('OK');
    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="upload one file",
     *     section="profile_upload"
     * )
     * @Route("/api/profile/upload/{id}", name="file_upload_profile")
     * @Method({"POST"})
     */

    public function uploadImageToProfile(Request $request,$id)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $profile= $user->getProf();

        $file= new File();
        $uploadedImage=$request->files->get('file');
        /**
         * @var UploadedFile $image
         */
        $image=$uploadedImage;
        $imageName=md5(uniqid()).'.'.$image->guessExtension();
        $image->move($this->getParameter('image_directory'),$imageName);
        $file->setImage($imageName);
        $em=$this->getDoctrine()->getManager();
        $current=$profile->getImage();
        $filesystem=new Filesystem();

        $profile->setImage($file);
        $file->addProfil($profile);
        $em->persist($file);
        $em->persist($profile);
        $em->flush();

      //$filesystem->remove( 'C:\xampp\htdocs\plateforme\web\uploads\images', $current->getImage());
        $response=array(
            'code'=>0,
            'message'=>'File Uploaded with success!',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,Response::HTTP_CREATED);
    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="get one image",
     *     section="profile_upload"
     * )
     * @Route("api/image/{id}",name="show_image")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getImage($id)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $profile= $user->getProf();
        $imageName=$profile->getImage()->getImage();
        $response=array(
            'code'=>0,
            'message'=>'get image with success!',
            'errors'=>null,
            'result'=>$imageName
        );
        return new JsonResponse($response,200);
    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="create one single language for user",

     *     section="profiles"
     * )
     * @Route("/api/languages/{id}", name="profile_languages")

     * @Method({"POST"})

     */

    public function AddLanguageToProfile(Request $request,$id)

    {
        $data = $request->getContent();
        $lang = $this->get('jms_serializer')->deserialize($data, 'ProfilBundle\Entity\Langue', 'json');
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $profile= $user->getProf();
        $em = $this->getDoctrine()->getManager();
        $lang->setProfile($profile);
        $em->persist($lang);
        $em->flush();
        $this->get('validator')->validate($lang);
        return new Response('', Response::HTTP_CREATED);

    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="delete one single contrat from profile",
     *
     *     section="profiles"
     * )
     * @Route("/api/profil/{id}/lang/{id_lang}",name="delete_profile_lang")
     * @Method({"DELETE"})
     */

    public function deleteLanguageFromProfile($id,$id_lang)
    {

        // On récupère l'EntityManager
        $em = $this->getDoctrine()
            ->getManager();
        //
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $profile= $user->getProf();
        $lang = $em->getRepository('ProfilBundle:Langue')
            ->find($id_lang);

        $profile->removeProfil($lang);
        $em->remove($lang);
        $em->persist($profile);
        $em->flush();

        return new Response('OK');
    }

}
