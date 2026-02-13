<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/user')]
#[IsGranted('ROLE_ADMIN')]
final class UserController extends AbstractController
{
    #[Route('', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id}/toggle', name: 'app_user_toggle', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function toggle(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('toggle' . $user->getId(), $request->getPayload()->get('_token'))) {
            // Ne pas pouvoir se désactiver soi-même
            if ($user === $this->getUser()) {
                $this->addFlash('error', 'Vous ne pouvez pas désactiver votre propre compte');
                return $this->redirectToRoute('app_user_index');
            }

            $user->setEnabled(!$user->isEnabled());
            $em->flush();

            $status = $user->isEnabled() ? 'activé' : 'désactivé';
            $this->addFlash('success', "Compte de {$user->getEmail()} {$status}");
        }

        return $this->redirectToRoute('app_user_index');
    }

    #[Route('/{id}/delete', name: 'app_user_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->get('_token'))) {
            // Ne pas pouvoir se supprimer soi-même
            if ($user === $this->getUser()) {
                $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte');
                return $this->redirectToRoute('app_user_index');
            }

            $email = $user->getEmail();
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', "Utilisateur {$email} supprimé");
        }

        return $this->redirectToRoute('app_user_index');
    }
}
