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
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\AuthTokensRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\AuthTokensRepository\SqliteAuthTokensRepository;
use GeekBrains\LevelTwo\http\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\http\Auth\JsonBodyUuidIdentification;
use GeekBrains\LevelTwo\http\Auth\AuthenticationInterface;
use GeekBrains\LevelTwo\http\Auth\PasswordAuthentication;
use GeekBrains\LevelTwo\http\Auth\PasswordAuthenticationInterface;
use GeekBrains\LevelTwo\http\Auth\TokenAuthenticationInterface;
use GeekBrains\LevelTwo\http\Auth\BearerTokenAuthentication;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Dotenv\Dotenv;
use Faker\Generator;
use Faker\Provider\Lorem;
use Faker\Provider\ru_RU\Internet;
use Faker\Provider\ru_RU\Person;
use Faker\Provider\ru_RU\Text;

require_once __DIR__ . '/vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->safeLoad();


$container = new DIContainer();

$faker = new \Faker\Generator();

$faker->addProvider(new Person($faker));
$faker->addProvider(new Text($faker));
$faker->addProvider(new Internet($faker));
$faker->addProvider(new Lorem($faker));




$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/' . $_SERVER['SQLITE_DB_PATH'])

);

$logger = (new Logger('blog'));
if ('yes' === $_SERVER['LOG_TO_FILES']) {
    $logger
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.log'
        ))
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.error.log',
            level: Logger::ERROR,
            bubble: false,
        ));
}

if ('yes' === $_SERVER['LOG_TO_CONSOLE']) {
    $logger
        ->pushHandler(
            new StreamHandler("php://stdout")
        );
}
$container->bind(
    \Faker\Generator::class,
    $faker
);


$container->bind(
    AuthenticationInterface::class,
    PasswordAuthentication::class
);

$container->bind(
    IdentificationInterface::class,
    JsonBodyUuidIdentification::class
);

$container->bind(
    PasswordAuthenticationInterface::class,
    PasswordAuthentication::class
);


$container->bind(
    TokenAuthenticationInterface::class,
    BearerTokenAuthentication::class
);

$container->bind(
    AuthTokensRepositoryInterface::class,
    SqliteAuthTokensRepository::class
);


$container->bind(
    LoggerInterface::class,
    $logger
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
