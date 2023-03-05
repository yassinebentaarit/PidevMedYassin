<?php

namespace App\Controller;

use App\Repository\ReponseRepository;
use App\Repository\ReclamationRepository;
use App\Entity\Reponse;
use App\Entity\Reclamation;
use App\Form\ReponseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;

class ReponseController extends AbstractController
{
    #[Route('/reponse', name: 'app_reponse')]
    public function index(Request $request,PaginatorInterface $paginator): Response
    {
        $reponse=$this->getDoctrine()->getManager()->getRepository(Reponse::class)->findAll();
        $reponse = $paginator->paginate(
            $reponse, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('reponse/reponse.html.twig', [
            'k'=>$reponse
        ]);}
       
   #[Route('/suppReponse/{id}', name: 'suprimerReponse')]
        public function Supprimer($id,ReponseRepository $rep){
            $reponse=$rep->find($id);
            $em=$this->getDoctrine()->getManager();
            $em->remove($reponse);
            $em->flush();
    
            return $this->redirectToRoute('app_reponse');
              }
       
        #[Route('/addReponse/{id}', name: 'addReponse')]
        public function addReponse(Request $request, ReponseRepository $reponseRepository,$id , ReclamationRepository $rep): Response
    {
        $rec = new Reclamation() ;
         $rec = $rep->find($id);
         $rec = $rec->setEtat(true) ;
        $reponse = new Reponse();
        

        $form = $this ->createForm(ReponseType::class,$reponse);
        
        $form->handleRequest($request);

        if($form ->isSubmitted()&& $form->isValid()){
            $reponse=$reponse->setRelationReclamation($rec);
            $em= $this->getDoctrine()->getManager();
            $em->persist($reponse);//Add
            
            $em->flush();

            return $this -> redirectToRoute(route:'app_reponse');
        }
        return $this -> render('reponse/index.html.twig' , ['f'=>$form->createView()]);
   
   
    }

}
