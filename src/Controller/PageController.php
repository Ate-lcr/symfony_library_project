<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{

    /**
     * @Route("/",name="home")
     */
        public function showBooks(BookRepository $bookRepository)
    {
        $books = $bookRepository->findAll();
        return $this->render('front/showbooks.html.twig', [
            "books" => $books
        ]);
    }
}