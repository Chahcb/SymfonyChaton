<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Form\ProprietaireSupprimerType;
use App\Form\ProprietaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProprietaireController extends AbstractController
{
    #[Route('/proprietaire/ajouter', name: 'proprietaire_ajouter')]
    public function ajouterProprietaire(ManagerRegistry $doctrine, Request $request)
    {
        $proprietaire = new Proprietaire();
        $form = $this->createForm(ProprietaireType::class, $proprietaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($proprietaire);
            $em->flush();
            return $this->redirectToRoute("app_home");
        }

        return $this->render("proprietaires/ajouterProprietaire.html.twig", [
            'formulaire' => $form->createView()
        ]);
    }

    #[Route('/proprietaire/modifier/{id}', name: 'proprietaire_modifier')]
    public function modifierProprietaire($id, ManagerRegistry $doctrine, Request $request)
    {
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);
        $form = $this->createForm(ProprietaireType::class, $proprietaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($proprietaire);
            $em->flush();
            return $this->redirectToRoute("app_home");
        }

        return $this->render("proprietaires/modifierProprietaire.html.twig", [
            'proprietaire' => $proprietaire,
            'formulaire' => $form->createView()
        ]);
    }

    #[Route('/proprietaire/supprimer/{id}', name: 'proprietaire_supprimer')]
    public function supprimerProprietaire($id, ManagerRegistry $doctrine, Request $request)
    {
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);

        if (!$proprietaire) {
            throw $this->createNotFoundException("Aucun propri??taire avec l'id $id");
        }

        $form = $this->createForm(ProprietaireSupprimerType::class, $proprietaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->remove($proprietaire);
            $em->flush();
            return $this->redirectToRoute("app_home");
        }

        return $this->render("proprietaires/supprimerProprietaire.html.twig", [
            'proprietaire' => $proprietaire,
            'formulaire' => $form->createView()
        ]);
    }
}
