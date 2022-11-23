<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Proprietaire;
use App\Form\CategorieSupprimerType;
use App\Form\CategorieType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function ajouterCategorie(ManagerRegistry $doctrine, Request $request): Response
    {
        $categorie = new Categorie(); // création du formulaire d'ajout, on crée une catégorie vide
        $form = $this->createForm(CategorieType::class, $categorie); // on crée un formulaire à partir de la classe CategorieType et de notre objet vide

        /* Gestion du retour du formulaire
         * on ajoute Request dans les paramètres comme dans le projet précédent */
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* le handleRequest a rempli notre objet $categorie
             * qui n'est plus vide
             * pour sauvegarder, on va récupérer un entityManager de doctrine
             * qui comme son nom l'indique gère les entités */
            $em = $doctrine->getManager();
            $em->persist($categorie); // on lui dit de la ranger dans la BDD
            $em->flush(); //générer l'insert
        }

        /* pour aller chercher les catégories, je vais utiliser un repository
         * pour me servir de doctrine j'ajoute le paramètres $doctrine à la méthode */
        $repo = $doctrine->getRepository(Categorie::class);
        $categories = $repo->findAll();
        $repoProprietaire = $doctrine->getRepository(Proprietaire::class);
        $proprietaire = $repoProprietaire->findAll();

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
            'proprietaire' => $proprietaire,
            'formulaire' => $form->createView()
        ]);
    }

    #[Route('/categories/modifier/{id}', name: 'categorie_modifier')]
    public function modifierCategorie($id, ManagerRegistry $doctrine, Request $request)
    {
        //récupérer la catégorie dans la BDD
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);

        //si on n'a rien trouvé -> 404
        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }

        /* si on arrive là, c'est qu'on a trouvé une catégorie
         * on crée le formulaire avec (il sera rempli avec ses valeurs
         * Gestion du retour du formulaire
         * on ajoute Request dans les paramètres comme dans le projet précédent */
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute("app_home"); //retour à l'accueil
        }

        return $this->render("categories/modifierCategorie.html.twig", [
            'categorie' => $categorie,
            'formulaire' => $form->createView()
        ]);
    }

    #[Route('/categories/supprimer/{id}', name: 'categorie_supprimer')]
    public function supprimerCategorie($id, ManagerRegistry $doctrine, Request $request)
    {
        $categorie = $doctrine->getRepository(Categorie::class)->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }

        $form = $this->createForm(CategorieSupprimerType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->remove($categorie);
            $em->flush();
            return $this->redirectToRoute("app_home");
        }

        return $this->render("categories/supprimerCategorie.html.twig", [
            'categorie' => $categorie,
            'formulaire' => $form->createView()
        ]);
    }
}