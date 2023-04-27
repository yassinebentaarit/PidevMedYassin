<?php

namespace App\Controller;
use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(Request $request,PaginatorInterface $paginator): Response
    {
        $categorie=$this->getDoctrine()->getManager()->getRepository(Categorie::class)->findAll();
        $categorie = $paginator->paginate(
            $categorie, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('tablesCategorie.html.twig', [
            'k'=>$categorie
        ]);
    }




    #[Route('/addCategorie', name: 'addCategorie')]
    public function addCategorie(Request $request): Response
    {
        $categorie = new Categorie();

        $form = $this ->createForm(CategorieType::class,$categorie);
        
        $form->handleRequest($request);

        if($form ->isSubmitted()&& $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($categorie);//Add
            $em->flush();

            return $this -> redirectToRoute(route:'app_categorie');
        }
        return $this -> render('ajouterCat.html.twig' , ['f'=>$form->createView()]);
    }



    #[Route('/suppCategorie/{id}', name: 'suprimerCategorie')]
    public function Supprimer($id,CategorienRepository $rep){
        $categorie=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        return $this->redirectToRoute('app_categorie');
    }


    #[Route('/updateCategorie/{id}', name: 'updateCategorie')]
    public function updateC(CategorieRepository $repository,ManagerRegistry $doctrine,Request $request,int $id)
    {
        $categorie =$repository->find($id);
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$doctrine->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute("app_categorie");
        }
        return $this->renderForm("ajouterCat.html.twig",array("f"=>$form));

    }
}
