<?php

namespace App\Controller\FrontController;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FrontAuthorController extends AbstractController
{

//    Affichage d'un auteur de ma bdd
    /**
     * @Route("/author/{id}", name="author")
     */
    public function showAuthor(AuthorRepository $authorRepository, $id){
//        La méthode "find" me permet de récupérer un élément par la valeur que je lui passe en attribut)
        $author = $authorRepository->find($id);
        return $this->render('front/showauthor.html.twig', [
            "author" => $author
        ]);    }


//    Affichage de l'ensemble des auteurs de ma bdd
    /**
     * @Route("/authors", name="authors")
     */
    public function showAuthors (AuthorRepository $authorRepository){
        $authors = $authorRepository->findAll();
        return $this->render('front/showauthors.html.twig', [
            "authors" => $authors,
        ]);
    }

}