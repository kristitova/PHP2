<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsLikeRepository;

use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\PostsLikeRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Exceptions\LikeNotFoundException;
use GeekBrains\LevelTwo\Exceptions\LikeIsAlreadyExists;
use GeekBrains\LevelTwo\Person\Name;
use Psr\Log\LoggerInterface;


class SqlitePostsLikeRepository implements PostsLikeRepositoryInterface
{
    private \PDO $connection;
    private LoggerInterface $logger;

    public function __construct(\PDO $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public function save(Like $like): void
    {

        $statement = $this->connection->prepare(
            'INSERT INTO likes (uuid, post_uuid, author_uuid) VALUES (:uuid, :post_uuid, :author_uuid)'
        );


        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => (string)$like->getUuid(),
            ':post_uuid' => $like->getPost()->getUuid(),
            ':author_uuid' => $like->getUser()->uuid()
        ]);

        $this->logger->info("PostLike created {$like->getUuid()}");
    }

    public function getByPostUuid(UUID $uuid): array
    {
        $statement = $this->connection->prepare(
            'SELECT *
            FROM likes 
            WHERE post_uuid=:uuid'
        );

        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);

        $result = $statement->fetchAll();

        if ($result === false) {
            throw new LikeNotFoundException(
                "No likes to this post: $uuid"
            );
        }

        $this->logger->warning("Cannot find likes to this post: $uuid");

        return $result;
    }

    public function checkUserLikeForPostExists($postUuid, $userUuid): void
    {

        $statement = $this->connection->prepare(
            'SELECT *
            FROM likes 
            WHERE post_uuid=:post_uuid AND author_uuid=:author_uuid'
        );

        $statement->execute([
            ':post_uuid' => $postUuid,
            ':author_uuid' => $userUuid
        ]);

        $isExisted = $statement->fetch();

        if ($isExisted) {
            throw new LikeIsAlreadyExists(
                'User like for this post is already exists'
            );
        }
    }
}
