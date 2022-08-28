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
use Symfony\Component\Console\Application;
use GeekBrains\LevelTwo\Blog\Commands\Users\CreateUser;
use GeekBrains\LevelTwo\Blog\Commands\Posts\DeletePost;
use GeekBrains\LevelTwo\Blog\Commands\Users\UpdateUser;
use GeekBrains\LevelTwo\Blog\Commands\FakeData\PopulateDB;

$container = require(__DIR__ . '/bootstrap.php');
$logger = $container->get(LoggerInterface::class);

$application = new Application();
// Перечисляем классы команд
$commandsClasses = [
      CreateUser::class,
      DeletePost::class,
      UpdateUser::class,
      PopulateDB::class
];

foreach ($commandsClasses as $commandClass) {
      // Посредством контейнера
      // создаём объект команды
      $command = $container->get($commandClass);
      // Добавляем команду к приложению
      $application->add($command);
}
// Запускаем приложение
$application->run();

try {
      $command = $container->get(CreateUserCommand::class);

      $command->handle(Arguments::fromArgv($argv));
} catch (Exception $exception) {
      $logger->error($exception->getMessage(), ['exception' => $exception]);
}
