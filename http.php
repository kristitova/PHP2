<?php

use GeekBrains\LevelTwo\Blog\Container\DIContainer;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\http\Actions\Users\CreateUser;
use GeekBrains\LevelTwo\http\Actions\Posts\CreatePost;
use GeekBrains\LevelTwo\http\Actions\Posts\DeletePost;
use GeekBrains\LevelTwo\http\Actions\PostsLike\CreatePostLike;
use GeekBrains\LevelTwo\http\Actions\CommentsLike\CreateCommentLike;
use GeekBrains\LevelTwo\http\Actions\Comments\DeleteComment;
use GeekBrains\LevelTwo\http\Actions\Comments\CreateComment;
use GeekBrains\LevelTwo\http\ErrorResponse;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\http\Actions\Users\FindByUsername;
use GeekBrains\LevelTwo\Exceptions\HttpException;
use Psr\Log\LoggerInterface;

$container = require(__DIR__ . '/bootstrap.php');
$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input')
);
$logger = $container->get(LoggerInterface::class);

try {

    $path = $request->path();
} catch (HttpException) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();

    return;
}

try {

    $method = $request->method();
} catch (HttpException) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}



$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class
    ],
    'POST' => [
        '/users/create' =>  CreateUser::class,
        '/posts/create' =>  CreatePost::class,
        '/comments/create' =>  CreateComment::class,
        '/postslike/create' => CreatePostLike::class,
        '/commentslike/create' => CreateCommentLike::class

    ],
    'DELETE' => [
        '/posts' => DeletePost::class,
        '/comments' =>  DeleteComment::class,

    ]


];

if (!array_key_exists($method, $routes) || !array_key_exists($path, $routes[$method])) {
    $logger->notice($message);

    (new ErrorResponse('Not found route'))->send();
    return;
}


$actionClassName = $routes[$method][$path];
$action = $container->get($actionClassName);


try {

    $response = $action->handle($request);

    $response->send();
} catch (Exception $e) {

    $logger->error($e->getMessage(), ['exception' => $e]);

    (new ErrorResponse($e->getMessage()))->send();
}
