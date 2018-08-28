<?php

namespace EntrepriseBundle\Controller;
use AppBundle\Entity\File;
use PaymentBundle\Entity\Charge;
use phpDocumentor\Reflection\Types\Boolean;
use ProfilBundle\Entity\Competence;
use ProfilBundle\Entity\Contrat;
use ProfilBundle\Entity\Salaire;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EntrepriseBundle\Service\Validate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Filesystem\Filesystem;
class CompteController extends Controller

{

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="Retourner les informations consernant le grand compte",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="l'identifiant unique du grand compte."
     *         }
     *     },
     *     section="comptes"
     * )
     * @Route("/api/comptes/{id}",name="show_compte")
     * @Method({"GET"})
     */
    public function showCompte($id)
    {

        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $compte=$user->getCompte();
        $customer=$compte->getStripeId();
        if (empty($compte)){
            $response=array(
                'code'=>1,
                'message'=>'compte introuvable',
                'error'=>null,
                'result'=>null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        //get card information from stripe
        \Stripe\Stripe::setApiKey("sk_test_R6f3tuAsgbkH45pSm6tcr4s7");
        $customer = \Stripe\Customer::retrieve((string)$customer);
        $card = $customer->sources->retrieve((string)$customer->default_source);

        $data=$this->get('jms_serializer')->serialize($compte,'json');
        $response=array(

            'code'=>0,
            'message'=>'succés',
            'errors'=>null,
            'result'=>json_decode($data),

           'card_detail' =>$card

        );

        return new JsonResponse($response,200);


    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="créer un nouvaeu compte",
     *     section="comptes"
     * )
     * @Route("/api/comptes", name="compte_create")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $data = $request->getContent();
        $compte = $this->get('jms_serializer')->deserialize($data, 'EntrepriseBundle\Entity\Compte', 'json');
        $em = $this->getDoctrine()->getManager();
        $em->persist($compte);
        $em->flush();
        $this->get('validator')->validate($compte);
        return new Response('', Response::HTTP_CREATED);
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="Retourner la liste des comptes",
     *
     *     section="comptes"
     * )
     * @Route("/api/compte", name="compte_list")
     * @Method({"GET"})
     */

    public function listAction()

    {
        $compte = $this->getDoctrine()->getRepository('EntrepriseBundle:Compte')->findAll();
        $data = $this->get('jms_serializer')->serialize($compte, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="supprimer un  compte",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="l'identifiant unique du compte."
     *         }
     *     },
     *     section="comptes"
     * )
     * @Route("/api/comptes/{id}",name="delete_compte")
     * @Method({"DELETE"})
     */

    public function deleteCompte($id)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $Compte=$user->getCompte();
        //verifier l'existance du compte
        if (empty($Compte)) {
            $response=array(
                'code'=>1,
                'message'=>'Compte Not found !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        //valider la BD
        $em=$this->getDoctrine()->getManager();
        $em->remove($Compte);
        $em->flush();
        //générer la réponse
        $response=array(
            'code'=>0,
            'message'=>'Compte deleted !',
            'errors'=>null,
            'result'=>null
        );

        return new JsonResponse($response,200);

    }
    /**
     * @ApiDoc(
     *      resource=true,
     *     description="add one single city to one grand compte",
     *     section="comptes"
     * )
     * @param $id_GC
     * @param $id_city
     * @Route("/api/compte/{id_GC}/city/{id_city}", name="add_city_compte")
     * @Method({"PUT"})
     * @return Response
     */

    public function addCityToCompte($id_GC,$id_city)

    {
        $em = $this->getDoctrine()
            ->getManager();
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_GC);
        $compte=$user->getCompte();
        $city = $em->getRepository('ProfilBundle:City')
            ->find($id_city);
        if (empty($city)) {
            $response=array(
                'code'=>1,
                'message'=>'Compte or City Not found !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $compte->setCity($city);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'City Added to Compte !',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response, Response::HTTP_NOT_FOUND);
    }


    /**
     *@ApiDoc(
     *      resource=true,
     *     description="modifier les infos du compte",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="L'identifiant du compte."
     *         }
     *     },
     *     section="comptes"
     * )
     * @param Request $request
     * @param $id
     * @param Validate $validate
     * @return JsonResponse
     * @Route("/api/comptes/{id}",name="update_compte")
     * @Method({"PUT"})
     */
    public function updatePost(Request $request,$id)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $compte=$user->getCompte();

        //bérifier l'existance du compte
        if (empty($compte))
        {
            $response=array(
                'code'=>1,
                'message'=>'compte introuvable !',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }
        $body=$request->getContent();
        $data=$this->get('jms_serializer')->deserialize($body,'EntrepriseBundle\Entity\Compte','json');
        if (!empty($reponse))
        {
            return new JsonResponse($reponse, Response::HTTP_BAD_REQUEST);
        }
        // modifier la compte
        $compte->setSecteur($data->getSecteur())
            ->setNom($data->getNom())
            ->setPresentation($data->getPresentation())
            ->setNumTel($data->getNumTel())
            ->setNumSiret($data->getNumSiret())
            ->setNSalair($data->getNSalair())
            ->setFax($data->getFax())
            ->setAdresse($data->getAdresse());

        //màj du BD
        $em=$this->getDoctrine()->getManager();
        $em->persist($compte);
        $em->flush();

        //générer la réponse
        $response=array(
            'code'=>0,
            'message'=>'company updated!',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,200);
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="rechercher des profiles selon le filtre sélectionné",
     *     section="comptes"
     * )
     * @Route("/api/comptes/search/{id}", name="search_profiles")
     * @Method({"POST"})
     */

    public function SearchByFilter(Request $request,$id)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $compte=$user->getCompte();
        $data = $request->getContent();
        //liste des compétences à chercher
        $filtre = $this->get('jms_serializer')->deserialize($data,'AppBundle\Entity\Search', 'json');
        $result=$this->getDoctrine()->getRepository('ProfilBundle:Prof')->findAll();
        if ($filtre->getComp()==false && $filtre->getContrat()==false && $filtre->getSalaire()==false  && $filtre->getCountry()==null )
        {
            $result=null;
            //générer la réponse
            $response=array(
                'code'=>0,
                'message'=>'aucun profil trouvé',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response,201);
        }

       if ($filtre->getComp()){
        //liste de tous les profils
        $array=array();
        //resultat finale du recherche
        //on parcours la liste des profils
        foreach ($result as $prof){
            $test=true;
            //on parcours les compétences dans le filtre
                foreach ($filtre->getListComp() as $comp){
                    $existe=null;
            //on verifie l'existence du compéetence pour le profil
               $existe=$this->getDoctrine()->getRepository('ProfilBundle:niveau')->findOneBy(array('profils'=> $prof,'competences'=> $comp));
                if ($existe==null){
                    $test=false;
                }
            }
            if($test){
                $array[]=$prof;
            }
        }
            $result=$array;
        }
        if ($filtre->getLang()){

            $array1=array()  ;

            foreach ($result as $prof){
                $test=true;
                $h=$filtre->getLanguage();
                foreach ($h as $cont){
                    $exist=false;
                    if ($prof->getProfil()){

                        $k=$prof->getProfil();
                        foreach ($k as $hisContrat){
                            if($this->get('jms_serializer')->serialize($hisContrat,'json')==$this->get('jms_serializer')->serialize($cont,'json')){
                                $exist=true;
                            }
                        }
                        if ($exist==false){
                            $test=false;
                        }}
                    else{
                        $test=false;
                    }
                }
                if($test ==true){
                    $array1[]=$prof;
                }

            }
            $result=$array1;
        }
        if ($filtre->getContrat()) {

                    $array1=array()  ;

                    foreach ($result as $prof){
                        $test=true;
                        $h=$filtre->getLisContrat();
                        foreach ($h as $cont){
                           $exist=false;
                           if ($prof->getListContrats()){

                            $k=$prof->getListContrats();
                            foreach ($k as $hisContrat){
                                if($this->get('jms_serializer')->serialize($hisContrat,'json')==$this->get('jms_serializer')->serialize($cont,'json')){
                                    $exist=true;
                                }
                            }
                            if ($exist==false){
                                $test=false;
                            }}
                            else{
                               $test=false;
                            }
                        }
                if($test ==true){
                    $array1[]=$prof;
               }

           }
            $result=$array1;
                    }

        if ($filtre->getSalaire()){
            $array3=array();
            //on parcours la liste des profils
            foreach ($result as $profile){

                $salaire=$profile->getSalMin();
                $searched=$filtre->getSal();

                if ($this->get('jms_serializer')->serialize($salaire,'json')<$this->get('jms_serializer')->serialize($searched,'json')){
                    //    if($profile->getSalaire()==$filtre->getSal()){
                    $array3[]=$profile;
                }}
            //}
            $result=$array3;
        }   if ($filtre->getCountry()!=null){
            $array3=array();
            //on parcours la liste des profils
            foreach ($result as $profile){

                $city=$profile->getPays();
                $searched=$filtre->getCountry();

                if ($this->get('jms_serializer')->serialize($city,'json')==$this->get('jms_serializer')->serialize($searched,'json')){
                    //    if($profile->getSalaire()==$filtre->getSal()){
                    $array3[]=$profile;
                }}

            $result=$array3;
        }
        if (empty($result)){
            //générer la réponse
     /*       $response=array(
                'code'=>0,
                'message'=>'aucun profil trouvé!',
                'errors'=>null,
                'result'=>null
            );
            return new JsonResponse($response,201);
            */
     
            $data=$this->get('jms_serializer')->serialize($result,'json',SerializationContext::create()->setGroups(array('all')));
            $response = new Response($data);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $result1=$result;
        $result=null;

            foreach ($result1 as $item) {
                $test=$this->getDoctrine()->getRepository('EntrepriseBundle:Invitation')->findOneBy(array('profils'=> $item,'grand_comptes'=> $compte));
                if (!$test){
                    $result[]=$item;
                }
            }

        $data=$this->get('jms_serializer')->serialize($result,'json',SerializationContext::create()->setGroups(array('all')));
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     *@ApiDoc(
     *      resource=true,
     *     description="mettre à jour la carte par défaut du compte",
     *     section="comptes"
     * )
     * @Route("/api/comptes/card/{id}", name="update_card")
     * @Method({"POST"})
     */

    public function AddCardToCustomer(Request $request,$id)
    {

        $data = $request->getContent();
        $source = $this->get('jms_serializer')->deserialize($data, 'PaymentBundle\Entity\Charge', 'json')->getSource();
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $compte=$user->getCompte();

        //update card on stripe
        \Stripe\Stripe::setApiKey("sk_test_R6f3tuAsgbkH45pSm6tcr4s7");
        $cu = \Stripe\Customer::retrieve((string)$compte->getStripeId());
        $cu->source = (string)$source; // obtained with Stripe.js
        $cu->save();
    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="abonner à un plan",
     *     section="comptes"
     * )
     * @Route("/api/comptes/plan/{id_gc}/{id_pl}", name="upgrade_plan")
     * @Method({"POST"})
     */

    public function AddPlanToCustomer($id_gc,$id_pl)
    {

        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id_gc);
        $compte=$user->getCompte();
        $plan=$this->getDoctrine()->getRepository('PaymentBundle:Plan')->find($id_pl);
        //abonner au plan

        \Stripe\Stripe::setApiKey("sk_test_R6f3tuAsgbkH45pSm6tcr4s7");

        \Stripe\Subscription::create(array(
            "customer" => ((string)$compte->getStripeId()),
            "items" => array(
                array(
                    "plan" => ((string)$plan->getStripeId()),
                ),
            )
        ));

        $compte->setPlans($plan);


        $em = $this->getDoctrine()->getManager();
        $em->persist($compte);
        $em->flush();
        //générer la réponse
        $response=array(
            'code'=>0,
            'message'=>'vous etes premium !',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,201);

    }
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="upload one file",
     *     section="compte_upload"
     * )
     * @Route("/api/compte/upload/{id}", name="file_upload_compte")
     * @Method({"POST"})
     */

    public function uploadImageToCompte(Request $request,$id)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $compte= $user->getCompte();

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
        $current=$compte->getImage();
        $filesystem=new Filesystem();

        $compte->setImage($file);
        $file->addCompte($compte);
        $em->persist($file);
        $em->persist($compte);
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
     *     section="compte_upload"
     * )
     * @Route("api/image/compte/{id}",name="show_image_compte")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getImage($id)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $compte= $user->getCompte();
        $imageName=$compte->getImage()->getImage();
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
     *     description="désactiver le plan",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="l'identifiant unique du compte."
     *         }
     *     },
     *     section="comptes"
     * )
     * @Route("/api/plandisable/{id}",name="disable_plan")
     * @Method({"DELETE"})
     */

    public function disablePlan($id)
    {
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        $Compte=$user->getCompte();
        $Compte->setPlans(null);
        $em=$this->getDoctrine()->getManager();
        $em->persist($Compte);
        $em->flush();
        //générer la réponse
        $response=array(
            'code'=>0,
            'message'=>'plan disabled !',
            'errors'=>null,
            'result'=>null
        );

        return new JsonResponse($response,200);

    }
}
