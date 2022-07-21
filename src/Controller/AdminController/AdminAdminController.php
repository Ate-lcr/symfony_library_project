<?php

namespace App\Controller\AdminController;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class AdminAdminController extends AbstractController
{

    /**
     * @Route("/admin/admins", name="admin_admins")
     */
    public function listAdmins (UserRepository $userRepository){
        $admins = $userRepository->findAll();

        return $this -> render('admin/admins.html.twig', [
            "admins" => $admins
        ]);

    }

    /**
     * @Route("/admin/create-admin", name="admin_create_admin")
     */
    public function createAdmin (Request $request, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher){
        $user = new User();
        $user->setRoles(["ROLE_ADMIN"]);

        $form=$this->createform(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $plainPassword = $form->get('password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($user,$plainPassword);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "Admin user created!");
        }

        return $this -> render('admin/create_admin.html.twig', [
            'form'=> $form->createview()
        ]);
    }
}