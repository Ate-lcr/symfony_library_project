<?php

namespace App\Controller\FrontController;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class FrontBookController extends AbstractController
{

//    Affichage d'un livre de ma bdd
    /**
     * @Route("/book/{id}", name="book")
     */
    public function showBook(BookRepository $bookRepository, $id)
    {
//        La méthode "find" me permet de récupérer un élément par la valeur que je lui passe en attribut)
        $book = $bookRepository->find($id);
        return $this->render('front/showbook.html.twig', [
            "book" => $book
        ]);
    }


//    Affichage de l'ensemble des auteurs de ma bdd

    /**
     * @Route("/books", name="books")
     */
    public function showBooks(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();
        return $this->render('front/showbooks.html.twig', [
            "books" => $books,
        ]);
    }


    /**
     * @Route("/books/search", name="search_books")
     */
    public function searchBooks(Request $request, BookRepository $bookRepository)
    {
        // je récupère les valeurs de mon form de recherche
        $search = $request->query->get('search');

        // je crée dans ArticleRepo ma méthode pour rechercher d'après un mot
        $books = $bookRepository->searchByWord($search);

        // on refait passer ces éléments dans un nouveau fichier twig qui les affichera
        return $this->render('front/search_books.html.twig', [
            'books' => $books
        ]);
    }



}