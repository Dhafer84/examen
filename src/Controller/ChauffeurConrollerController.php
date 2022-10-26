<?php

namespace App\Controller;

use App\Entity\Chauffeur;
use App\Form\ChauffeurType;
use App\Repository\ChauffeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChauffeurConrollerController extends AbstractController
{
    #[Route("/listChauf", name: "chauf_listO")]
    public function listchauf(ChauffeurRepository $repo ):Response {
        $chauffeur= $repo->findAll();
        return $this->render('chauffeur_conroller/listchauf.html.twig',['chauffeur'=> $chauffeur]);
    }

    #[Route('/update/{id}', name: 'update_chauffeur')]
    public function update(Request $req, ChauffeurRepository $repo, Chauffeur $c): Response
    {
        $form = $this->createForm(ChauffeurType::class, $c);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($c, true);
            return $this->redirectToRoute('chauf_listO');
        }
        return $this->renderForm('chauffeur_conroller/form.html.twig', [
            'form' => $form,
        ]);
    }
}
