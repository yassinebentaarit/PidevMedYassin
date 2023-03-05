<?php

namespace App\Controller;
use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReclamationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ReclamationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;



class ReclamationController extends AbstractController
{
    #[Route('/pdf', name: 'pdf')]
    public function pdf(ReclamationRepository $reclamationRepository,Request $request,PaginatorInterface $paginator): Response
    {
       
        $reclamations = $reclamationRepository->findAll();
        $reclamations = $paginator->paginate(
            $reclamations, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        
        $dompdf = new Dompdf($pdfOptions);
        
        
       
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('tables-data.html.twig', [
            'k'=>$reclamations
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("reclamation.pdf", [
            "Attachment" => true
        ]);
        
        
      
    }
   
   
   
   
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(ReclamationRepository $reclamationRepository,Request $request,PaginatorInterface $paginator): Response
    {
       
        $reclamations = $reclamationRepository->findAll();
        $reclamations = $paginator->paginate(
            $reclamations, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        
        
        
        return $this->render('tables-data.html.twig', [
            'k'=>$reclamations
        ]);
        
       
        
        
      
    }
    #[Route('/addReclamation', name: 'addReclamation')]
    public function addReclamation(Request $request): Response
    {
        $reclamation = new Reclamation();

        $form = $this ->createForm(ReclamationType::class,$reclamation);
        
        $form->handleRequest($request);

        if($form ->isSubmitted()&& $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($reclamation);//Add
            $em->flush();

            return $this -> redirectToRoute(route:'addReclamation');
        }
        return $this -> render('ajouterRec.html.twig' , ['f'=>$form->createView()]);
    }

    #[Route('/suppReclamation/{id}', name: 'suprimerReclamation')]
    public function Supprimer($id,ReclamationRepository $rep){
        $reclamation=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($reclamation);
        $em->flush();

        return $this->redirectToRoute('app_reclamation');
    }
    
    
    #[Route('/updateReclamation/{id}', name: 'updateReclamation')]
    public function updateC(ReclamationRepository $repository,ManagerRegistry $doctrine,Request $request,int $id)
    {
        $reclamation =$repository->find($id);
        $form=$this->createForm(ReclamationType::class,$reclamation);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute("app_reclamation");
        }
        return $this -> render('ajouterRec.html.twig' , ['f'=>$form->createView()]);
    }
}
