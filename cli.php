<?php

use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Commands\CreateUserCommand;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Psr\Log\LoggerInterface;



$container = require(__DIR__ . '/bootstrap.php');
$logger = $container->get(LoggerInterface::class);

try {
      $command = $container->get(CreateUserCommand::class);

      $command->handle(Arguments::fromArgv($argv));
} catch (Exception $exception) {
      $logger->error($exception->getMessage(), ['exception' => $exception]);
}
