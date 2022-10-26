<?php

namespace App\Controller;

use App\Entity\Chauffeur;
use App\Entity\Voiture;
use App\Form\ChauffeurType;
use App\Repository\VoitureRepository;
use App\Repository\ChauffeurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoitureConrollerController extends AbstractController
{
    #[Route("/listvoiture", name: "voiture_listO")]
    public function listvoiture(VoitureRepository $repo ):Response {
        $voiture= $repo->findAll();
        return $this->render('voiture_conroller/listvoiture.html.twig',['voiture'=> $voiture]);
    }

    #[Route('/delete/{id}', name: 'delete_voiture')]
    public function delete(VoitureRepository $repo, Voiture $v): Response
    {
        $repo->remove($v, true);
        return $this->redirectToRoute('voiture_listO');
    }

    #[Route('/louer/{id}', name: 'louer_voiture')]
    public function louer(Request $req, ManagerRegistry $doctrine, Voiture $v): Response
    {
        $chauffeur = new Chauffeur();
        $form = $this->createForm(ChauffeurType::class, $chauffeur);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $v->setChauffeur($chauffeur);
            $em = $doctrine->getManager();
            $em->persist($chauffeur);
            $em->flush();
            return $this->redirectToRoute('chauf_listO');
        }
        return $this->renderForm('voiture_conroller/addChauffeur.html.twig', [
            'form' => $form,
            'idV' => $v->getId(),
            'marque' => $v->getMarque(),
        ]);
    }
}
