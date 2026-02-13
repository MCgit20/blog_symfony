<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Comment;
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

        // Créer un autre utilisateur
        $user2 = new User();
        $user2->setEmail('marie@blog.com');
        $user2->setFistName('Marie');
        $user2->setLastName('Martin');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'user123'));
        $user2->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($user2);

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
        $posts = [];
        $images = ['techno.png', 'voyage_tourisme.png', 'cuisine.png'];
        for ($i = 1; $i <= 5; $i++) {
            $post = new Post();
            $post->setTitle('Article numéro ' . $i);
            $post->setContent('Ceci est le contenu de l\'article numéro ' . $i . '. Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
            $post->setAuthor($admin);
            $post->setCategory($categoryEntities[($i - 1) % count($categoryEntities)]);
            $post->setPublishedAt(new \DateTimeImmutable());
            $post->setPicture($images[($i - 1) % count($images)]);
            $manager->persist($post);
            $posts[] = $post;
        }

        // Créer des commentaires avec un mix d'approuvés et non approuvés
        $comments_data = [
            [
                'content' => 'Très bon article, bien détaillé !',
                'author' => $user,
                'post' => $posts[0],
                'approved' => true,
            ],
            [
                'content' => 'Je suis pas d\'accord avec cet avis...',
                'author' => $user2,
                'post' => $posts[0],
                'approved' => false,
            ],
            [
                'content' => 'Cela m\'a vraiment aidé, merci !',
                'author' => $user,
                'post' => $posts[1],
                'approved' => true,
            ],
            [
                'content' => 'Comment avez-vous fait cette partie ?',
                'author' => $user2,
                'post' => $posts[1],
                'approved' => false,
            ],
            [
                'content' => 'Excellent contenu !',
                'author' => $user,
                'post' => $posts[2],
                'approved' => true,
            ],
            [
                'content' => 'À tester absolument !',
                'author' => $user2,
                'post' => $posts[2],
                'approved' => true,
            ],
        ];

        foreach ($comments_data as $data) {
            $comment = new Comment();
            $comment->setContent($data['content']);
            $comment->setAuthor($data['author']);
            $comment->setPost($data['post']);
            $comment->setIsApproved($data['approved']);
            $comment->setCreatedAt(new \DateTimeImmutable('-' . rand(1, 30) . ' days'));
            $manager->persist($comment);
        }

        $manager->flush();
    }
}