<?php

use Php\App\Blog\Commands\Arguments;
use Php\App\Blog\Commands\CreateUserCommand;
use Php\App\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use Php\App\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Php\App\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;

use Php\App\Blog\UUID;
use Php\App\Blog\User;
use Php\App\Blog\Post;
use Php\App\Blog\Comment;

require_once __DIR__ . '/vendor/autoload.php';

    //Создаём объект подключения к SQLite
    $connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');
   
   // $usersRepository = new InMemoryUsersRepository();


try {
    $faker = Faker\Factory::create();
//User
        $usersRepository = new SqliteUsersRepository($connection);

       // usersRepository->save(new User(UUID::random(), 'admin', 'Ivan', 'Nikitin'));
        $user = $usersRepository->getByUsername('admin');
        print $user;


// Post
        $postsRepository = new SqlitePostsRepository($connection);
        
        //$postsRepository->save(new Post(UUID::random(), UUID::random(),  $faker->title(), $faker->text()));
        $post = $postsRepository->get(new UUID('7a973af1-9a35-4872-b711-84db819b6feb'));
        print $post;

// Comment
        $commentsRepository = new SqliteCommentsRepository($connection);
        
       // $commentsRepository->save(new Comment(UUID::random(),UUID::random(),UUID::random(), $faker->text()));
        $comment = $commentsRepository->get(new UUID('955a53ab-968f-4c69-a48a-497d1a918fd9'));
        print $comment;




} catch (Exception $exception) {
    echo $exception->getMessage();
}
