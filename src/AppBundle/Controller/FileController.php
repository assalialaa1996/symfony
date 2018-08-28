<?php
namespace AppBundle\Controller;
use AppBundle\Entity\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use ProfilBundle\Service\Validate;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
class FileController extends Controller
{
    /**
     *@ApiDoc(
     *      resource=true,
     *     description="upload one file",

     *     section="file"
     * )
     * @Route("/api/file", name="file_upload")

     * @Method({"POST"})

     */

    public function uploadImage(Request $request)
    {
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
        $em->persist($file);
        $em->flush();
        $response=array(
            'code'=>0,
            'message'=>'File Uploaded with success!',
            'errors'=>null,
            'result'=>null
        );
        return new JsonResponse($response,Response::HTTP_CREATED);
    }
    }
