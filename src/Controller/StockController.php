<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Form\CategorieType;
use App\Form\StockType;
use App\Repository\StockRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class StockController extends AbstractController
{
    /**
     * @Route("/stock", name="display_stock")
     */
    public function index(): Response
    {
        $stocks=$this->getDoctrine()->getManager()->getRepository(Stock::class)->findAll();
        foreach ($stocks as $st) {
            
            echo $st->__toString();

        }
        return $this->render('stock/index.html.twig', [
            's'=>$stocks
        ]);
    }



    /**
     * @Route("/admin", name="display_admin")
     */
    public function indexAdmin(): Response
    {
        return $this->render('Admin/index.html.twig', [
        ]);
    }


        /**
     * @Route("/addStock", name="add_stock")
     */
    public function addStock(Request $request): Response
    {
        $stock = new Stock();
        $form = $this ->createForm(StockType::class,$stock);
        $form->handleRequest ($request);
        if($form ->isSubmitted()&& $form->isValid()){
            $em= $this->getDoctrine()->getManager();
            $em->persist($stock);//Add
            $em->flush();
            return $this -> redirectToRoute(route:'display_stock');
        }
        return $this -> render('stock/ajoutStock.html.twig' , ['f'=>$form->createView()]);
    }
    
        /**
     * @Route("/removeStock/{id}", name="supp_stock")
     */
    public function suppressionStock(Stock $stock): Response
    {
        $em= $this->getDoctrine()->getManager();
        $em->remove($stock);
        $em->flush();
        return $this->redirectToRoute(route:'display_stock');
        
    }


        /** 
       * @Route("/updateStock/{id}", name="modif_stock")
     */
    public function modifStock(Request $request,$id,ManagerRegistry $doctrine,
    StockRepository $repository, EntityManagerInterface $em): Response
    {//récupérer le stock à modifier
        $stock = $repository->find($id);
        $form = $this ->createForm(StockType::class,$stock);
        $form->handleRequest($request);
        if($form ->isSubmitted() && $form->isValid()){
            $em= $doctrine->getManager();
            $em->flush();
            return $this -> redirectToRoute(route:'display_stock');}
        if(!$stock = $repository->find($id)){
            return $this -> redirectToRoute(route:'error');
        }
        return $this -> render('stock/updateStock.html.twig' , ['f' => $form->createView()]);
    }


       /** 
       * @Route("/Error", name="error")
     */
    public function erreur(): Response
    {
        return $this -> render('Error/erreur.html.twig',[] );
    }


           /** 
       * @Route("/choixCategorie", name="choix")
     */
    public function choix(Request $request): Response
    {
        $stock= new Stock();
        $form = $this ->createForm(CategorieType::class,$stock);
        $form->handleRequest($request);
        if($form ->isSubmitted()&& $form->isValid()){
            return $this -> redirectToRoute(route:'addStock');
        }
        return $this -> render('Stock/choixCateStock.html.twig',[] );
    }
}