<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Entity\Stock;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\CategorieType;
use App\Form\StockType;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="display_categorie")
     */
    public function index(): Response
    {
        $categories=$this->getDoctrine()->getManager()->getRepository(Categorie::class)->findAll();
        return $this->render('categorie/index.html.twig', [
            'c'=>$categories
        ]);
    }


        /**
     * @Route("/addCategorie", name="add_categorie")
     */
    public function addCategorie(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this ->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        if($form ->isSubmitted()&& $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($categorie);//Add
            $em->flush();
            return $this -> redirectToRoute(route:'display_categorie');
        }
        return $this -> render('categorie/ajoutCategorie.html.twig' , ['f'=>$form->createView()]);
    }


        /**
     * @Route("/removeCategorie", name="supp_categorie")
     */
    public function suppClassroom($id,CategorieRepository $r, ManagerRegistry $doctrine): Response
    {
        //récupérer la classroom à supprimer
        $classroom=$r->find($id);
        //Action suppression
        $em =$doctrine->getManager();
        $em->remove($classroom);
        $em->flush();
        return $this->redirectToRoute('display_categorie',);
    }


            /**
     * @Route("/modifCategorie/{id}", name="modif_categorie")
     */
    public function modifCategorie(Request $request,$id): Response
    {
        //récupérer le categorie à modifier
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $form = $this ->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        if($form ->isSubmitted()){
            $em= $this->getDoctrine()->getManager();
            $em->flush();
            return $this -> redirectToRoute(route:'display_categorie');}
        return $this -> render('categorie/ajoutCategorie.html.twig' , array(["f"=>$form]));

    }


    
}
