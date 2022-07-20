<?php

namespace App\Controller\AdminController;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminAuthorController extends AbstractController
{

//    Affichage d'un auteur de ma bdd
    /**
     * @Route("/admin/author/{id}", name="admin_author")
     */
    public function showAuthor(AuthorRepository $authorRepository, $id){
//        La méthode "find" me permet de récupérer un élément par la valeur que je lui passe en attribut)
        $author = $authorRepository->find($id);
        return $this->render('admin/showauthor.html.twig', [
            "author" => $author
        ]);    }


//    Affichage de l'ensemble des auteurs de ma bdd
    /**
     * @Route("/admin/authors", name="admin_authors")
     */
    public function showAuthors (AuthorRepository $authorRepository){
        $authors = $authorRepository->findAll();
        return $this->render('admin/showauthors.html.twig', [
            "authors" => $authors,
        ]);
    }



    //On supprime un author à l'aide de son id
    //Mélange de ArticleRepository pour le sélectionner puis EntityManager pour le supprimer.
    /**
     * @Route ("/admin/author/delete/{id}", name="admin_delete_author")
     */
    #[NoReturn] public function deleteAuthor(AuthorRepository $authorRepository, $id, EntityManagerInterface $entityManager){
        $author = $authorRepository->find($id);

        if (!is_null($author)) {
            $entityManager->remove($author);
            $entityManager->flush();
            $this->addFlash('success', "Your author card has been well deleted!");
            return $this->redirectToRoute('admin_authors');

        } else {
            $this->addFlash('success', "This author card had already been deleted!");
            return $this->redirectToRoute('admin_authors');
        }
    }


    /**
     * @Route ("/admin/create-author", name="admin_create_author")
     */
    public function createAuthors (Request $request, EntityManagerInterface $entityManager)
    {
        $author = new author();
        $form=$this->createform(AuthorType::class, $author);

//        On donne à la variable qui contient le formulaire une instance de la classe Request pour que le formulaire
//        puisse récupérer toutes les données des inputs et faire les setters sur les articles automatiquement
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', "Author card created!");
        }

        return $this->render("admin/create_author.html.twig", [
            'form'=> $form->createview()

        ]);
    }

    //On modifie (update) un author à l'aide de son id
    //Mélange de ArticleRepository pour le sélectionner puis EntityManager pour le modifier.
    /**
     * @Route ("/admin/author/update/{id}", name="admin_author_update")
     */
    public function updateAuthor(AuthorRepository$authorRepository, $id, EntityManagerInterface $entityManager, Request $request)
    {
        $author = $authorRepository->find($id);
        $form = $this->createform(AuthorType::class, $author);

//        On donne à la variable qui contient le formulaire une instance de la classe Request pour que le formulaire
//        puisse récupérer toutes les données des inputs et faire les setters sur les articles automatiquement
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($author);
            $entityManager->flush();

            $this->addFlash('success', "Author's card updated!");
        }

        return $this->render("admin/create_author.html.twig", [
            'form' => $form->createview()

        ]);
    }
}