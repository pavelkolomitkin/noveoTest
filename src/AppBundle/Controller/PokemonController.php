<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 20.05.16
 * Time: 15:18
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\Type\PokemonImportType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;


class PokemonController extends Controller
{
    /**
     * @Route("/", name="pokemon_upload")
     */
    public function uploadAction(Request $request)
    {
        $form = $this->createForm(PokemonImportType::class);

        if ($request->isMethod('POST'))
        {
            $files = $request->files->all();
            if (count($files) > 0)
            {
                /** @var UploadedFile $file */
                $file = $files['pokemon_import']['file'];

                $filePath = $file->getRealPath();

                $this->get('app.pokemon_processor')->importFile($filePath, true);
            }


            return $this->redirectToRoute('pokemon_report');
        }


        return $this->render(':pokemon:upload.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/report", name="pokemon_report")
     */
    public function reportAction(Request $request)
    {
        $items = $this->getDoctrine()->getRepository('AppBundle:Pokemon')->findAll();

        return $this->render('pokemon/report.html.twig', [
            'items' => $items
        ]);
    }
}