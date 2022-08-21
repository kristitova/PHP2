<?php

use GeekBrains\LevelTwo\Blog\Container\DIContainer;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\PostsLikeRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsLikeRepository\SqlitePostsLikeRepository;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\CommentsLikeRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsLikeRepository\SqliteCommentsLikeRepository;

require_once __DIR__ . '/vendor/autoload.php';

$container = new DIContainer();

$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
);

$container->bind(
    PostsRepositoryInterface::class,
    SqlitePostsRepository::class
);

$container->bind(
    UsersRepositoryInterface::class,
    SqliteUsersRepository::class
);

$container->bind(
    CommentsRepositoryInterface::class,
    SqliteCommentsRepository::class
);

$container->bind(
    PostsLikeRepositoryInterface::class,
    SqlitePostsLikeRepository::class
);

$container->bind(
    CommentsLikeRepositoryInterface::class,
    SqliteCommentsLikeRepository::class
);

return $container;
