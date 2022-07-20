<?php

namespace App\Controller\AdminController;

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


class AdminBookController extends AbstractController
{

//    Affichage d'un livre de ma bdd
    /**
     * @Route("/admin/book/{id}", name="admin_book")
     */
    public function showBook(BookRepository $bookRepository, $id)
    {
//        La méthode "find" me permet de récupérer un élément par la valeur que je lui passe en attribut)
        $book = $bookRepository->find($id);
        return $this->render('admin/showbook.html.twig', [
            "book" => $book
        ]);
    }


//    Affichage de l'ensemble des auteurs de ma bdd

    /**
     * @Route("/admin/books", name="admin_books")
     */
    public function showBooks(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();
        return $this->render('admin/showbooks.html.twig', [
            "books" => $books,
        ]);
    }



    //On supprime un author à l'aide de son id
    //Mélange de ArticleRepository pour le sélectionner puis EntityManager pour le supprimer.
    /**
     * @Route ("/admin/book/delete/{id}", name="admin_delete_book")
     */
    #[NoReturn] public function deleteBook(BookRepository $bookRepository, $id, EntityManagerInterface $entityManager)
    {
        $book = $bookRepository->find($id);

        if (!is_null($book)) {
            $entityManager->remove($book);
            $entityManager->flush();
            $this->addFlash('success', "Your book's card has been well deleted!");
            return $this->redirectToRoute('admin_books');

        } else {
            $this->addFlash('success', "This book's card had already been deleted!");
            return $this->redirectToRoute('admin_books');
        }
    }


    /**
     * @Route ("/admin/create-book", name="admin_create_book")
     */
    public function createBooks(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $book = new Book();
        $form = $this->createform(BookType::class, $book);

//        On donne à la variable qui contient le formulaire une instance de la classe Request pour que le formulaire
//        puisse récupérer toutes les données des inputs et faire les setters sur les articles automatiquement
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cover_img = $form->get('coverImg')->getData();

            if ($cover_img) {
                $originalFilename = pathinfo($cover_img->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cover_img->guessExtension();

                try {
                    $cover_img->move(
                        $this->getParameter('uploadimg'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $book->setCoverImg($newFilename);
            }

            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', "Book's card created!");
        }

        return $this->render("admin/create_book.html.twig", [
            'form' => $form->createview()

        ]);
    }

    //On modifie (update) un author à l'aide de son id
    //Mélange de ArticleRepository pour le sélectionner puis EntityManager pour le modifier.
    /**
     * @Route ("/admin/book/update/{id}", name="admin_book_update")
     */
    public function updateBook(BookRepository $bookRepository, $id, EntityManagerInterface $entityManager, Request $request,SluggerInterface $slugger)
    {
        $book = $bookRepository->find($id);
        $form = $this->createform(BookType::class, $book);

//        On donne à la variable qui contient le formulaire une instance de la classe Request pour que le formulaire
//        puisse récupérer toutes les données des inputs et faire les setters sur les articles automatiquement
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cover_img = $form->get('coverImg')->getData();

            if ($cover_img) {
                $originalFilename = pathinfo($cover_img->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cover_img->guessExtension();

                try {
                    $cover_img->move(
                        $this->getParameter('uploadimg'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $book->setCoverImg($newFilename);
            }

            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', "Book's card updated!");
        }

        return $this->render("admin/create_book.html.twig", [
            'form' => $form->createview()

        ]);
    }


    /**
     * @Route("/admin/books/search", name="admin_search_books")
     */
    public function searchBooks(Request $request, BookRepository $bookRepository)
    {
        // je récupère les valeurs de mon form de recherche
        $search = $request->query->get('search');

        // je crée dans ArticleRepo ma méthode pour rechercher d'après un mot
        $books = $bookRepository->searchByWord($search);

        // on refait passer ces éléments dans un nouveau fichier twig qui les affichera
        return $this->render('admin/search_books.html.twig', [
            'books' => $books
        ]);
    }



}