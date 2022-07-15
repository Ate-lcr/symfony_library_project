<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\ArticleRepository;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminAuthorController extends AbstractController
{
    /**
     * @Route("/admin/insert-author", name="admin_insert_author")
     */
    //On crée un nouvel enregistrement dans la table authors
    public function insertAuthor(EntityManagerInterface $entityManager){
        $author = new Author();

//            J'utilise les setters pour en définir les attributs
        $author->setFirstName("William");
        $author->setLastName("Shakespeare");
        $author->setBirthDate(new \DateTime("1564-04-26"));
        $author->setDeathDate(new \DateTime("1616-04-23"));

//            On fait une sauvegarde(bdd) avant de faire l'inscription en bdd'
        $entityManager->persist($author);
        $entityManager->flush();

        dump($author); die;
    }


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

}