<?php

namespace App\Controller;

use App\Entity\AssoChatonProprio;
use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Form\ChatonSupprimerType;
use App\Form\ChatonType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ChatonsController extends AbstractController
{
    #[Route('/chatons/{idCategorie}', name: 'voir_chaton_categorie')]
    public function voirChatonCategorie($idCategorie, ManagerRegistry $doctrine): Response
    {
        $categorie = $doctrine->getRepository(Categorie::class)->find($idCategorie);
        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $idCategorie");
        }

        return $this->render('chatons/indexChaton.html.twig', [
            'categorie' => $categorie,
            'chatons' => $categorie->getChaton()
        ]);
    }

    #[Route('/chaton/ajouter', name: 'chaton_ajouter')]
    public function ajouterChaton(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger)
    {
        $chaton = new Chaton();
        $form = $this->createForm(ChatonType::class, $chaton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Implementation la table chaton
            $image = $form->get('Photo')->getData();
            $originalPhotoName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safePhotoName = $slugger->slug($originalPhotoName);
            $fichier = $safePhotoName . '-' . uniqid() . '.' . $image->guessExtension(); // donne un nom à la photo
            $image->move('Photos/', $fichier); // déplace la photo dans le dossier '/Photos/'
            $chaton->setPhoto($fichier); // met la photo dans la BDD
            $em = $doctrine->getManager();
            $em->persist($chaton);
            $em->flush();

            $pro = $form->get('Proprietaire')->getData();
            foreach ($pro as $p) {
                $asso = new AssoChatonProprio();
                // Implementation la table d'association qui stocke les ids chatons et proprietaires
                $id = $chaton->getId(); // récupère l'id du chaton que l'on vient d'ajouter
                $chatonId = $doctrine->getRepository(Chaton::class)->find($id);
                $asso->setChatonId($chatonId);
                $asso->setProprietaireId($p);
                $emProprio = $doctrine->getManager();
                $emProprio->persist($asso);
                $emProprio->flush();
            }
            return $this->redirectToRoute("voir_chaton_categorie", ["idCategorie" => $chaton->getCategorie()->getId()]);
        }

        return $this->render("chatons/ajouterChaton.html.twig", ['formulaire' => $form->createView()]);
    }

    #[Route('/chaton/modifier/{id}', name: 'chaton_modifier')]
    public function modifierChaton($id, ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger)
    {
        $chaton = $doctrine->getRepository(Chaton::class)->find($id);
        $oldProprietaire = $doctrine->getRepository(AssoChatonProprio::class)->findBy(array('chatons' => $id));
        $form = $this->createForm(ChatonType::class, $chaton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('Photo')->getData();
            $originalPhotoName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $safePhotoName = $slugger->slug($originalPhotoName);
            $fichier = $safePhotoName . '-' . uniqid() . '.' . $image->guessExtension();
            $image->move('Photos/', $fichier);
            $chaton->setPhoto($fichier);
            $em = $doctrine->getManager();
            $em->persist($chaton);
            $em->flush();

            // Suppression des anciens propriétaires
            foreach ($oldProprietaire as $o) {
                $em = $doctrine->getManager();
                $em->remove($o);
                $em->flush();
            }

            // ajout des nouveaux propriétaires
            $pro = $form->get('Proprietaire')->getData();
            foreach ($pro as $p) {
                $asso = new AssoChatonProprio();
                $id_chaton = $chaton->getId();
                $chatonId = $doctrine->getRepository(Chaton::class)->find($id_chaton);
                $asso->setChatonId($chatonId);
                $asso->setProprietaireId($p);
                $emProprio = $doctrine->getManager();
                $emProprio->persist($asso);
                $emProprio->flush();
            }
            return $this->redirectToRoute("voir_chaton_categorie", ["idCategorie" => $chaton->getCategorie()->getId()]);
        }

        return $this->render("chatons/modifierChaton.html.twig", [
            'chaton' => $chaton,
            'formulaire' => $form->createView()
        ]);
    }

    #[Route('/chaton/supprimer/{id}', name: 'chaton_supprimer')]
    public function supprimerChaton($id, ManagerRegistry $doctrine, Request $request)
    {
        $chaton = $doctrine->getRepository(Chaton::class)->find($id);

        if (!$chaton) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }

        $form = $this->createForm(ChatonSupprimerType::class, $chaton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->remove($chaton);
            $em->flush();
            return $this->redirectToRoute("voir_chaton_categorie", ["idCategorie" => $chaton->getCategorie()->getId()]);
        }

        return $this->render("chatons/supprimerChaton.html.twig", [
            'chaton' => $chaton,
            'formulaire' => $form->createView()
        ]);
    }
}