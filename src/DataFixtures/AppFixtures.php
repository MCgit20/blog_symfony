<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Créer un administrateur
        $admin = new User();
        $admin->setEmail('admin@blog.com');
        $admin->setFistName('Admin');
        $admin->setLastName('Utilisateur');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($admin);

        // Créer un utilisateur normal
        $user = new User();
        $user->setEmail('user@blog.com');
        $user->setFistName('Jean');
        $user->setLastName('Dupont');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user123'));
        $user->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($user);

        // Créer des catégories
        $categories = [
            ['Technologie', 'Articles sur les nouvelles technologies'],
            ['Voyage', 'Récits et conseils de voyage'],
            ['Cuisine', 'Recettes et astuces culinaires'],
        ];

        $categoryEntities = [];
        foreach ($categories as [$name, $desc]) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription($desc);
            $manager->persist($category);
            $categoryEntities[] = $category;
        }

        // Créer des articles
        for ($i = 1; $i <= 5; $i++) {
            $post = new Post();
            $post->setTitle('Article numéro ' . $i);
            $post->setContent('Ceci est le contenu de l\'article numéro ' . $i . '. Lorem ipsum...');
            $post->setAuthor($admin);
            $post->setCategory($categoryEntities[array_rand($categoryEntities)]);
            $post->setPublishedAt(new \DateTimeImmutable());
            $manager->persist($post);
        }

        $manager->flush();
    }
}