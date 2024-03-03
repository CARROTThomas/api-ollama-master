<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register' ,methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SerializerInterface $serializer,UserRepository $userRepository): Response
    {
        $json = $request->getContent();
        $user = $serializer->deserialize($json,User::class,'json');

        if ($user->getUsername() == null || $user->getPassword() == null) {
            return $this->json("Données JSON invalides", Response::HTTP_BAD_REQUEST);
        }

        $checkUser= $userRepository->findBy(["username"=>$user->getUsername()]);
        if($checkUser){
            return $this->json("Le nom d'utilisateur " . $user->getUsername() . " est déjà pris", Response::HTTP_BAD_REQUEST);
        }

        $user->setPassword(
            $userPasswordHasher->hashPassword($user,$user->getPassword())
        );

        $profile= new Profile();
        $profile->setOfUser($user);
        $entityManager->persist($profile);
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json("Utilisateur ajouté avec succès", Response::HTTP_OK);

    }
}
