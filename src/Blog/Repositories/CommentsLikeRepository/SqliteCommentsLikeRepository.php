<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsLikeRepository;

use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\CommentsLikeRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\CommentLike;
use GeekBrains\LevelTwo\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Exceptions\CommentLikeNotFoundException;
use GeekBrains\LevelTwo\Exceptions\CommentLikeIsAlreadyExists;
use GeekBrains\LevelTwo\Person\Name;
use Psr\Log\LoggerInterface;


class SqliteCommentsLikeRepository implements CommentsLikeRepositoryInterface
{
    private \PDO $connection;
    private LoggerInterface $logger;

    public function __construct(\PDO $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public function save(CommentLike $commentlike): void
    {

        $statement = $this->connection->prepare(
            'INSERT INTO commentslikes (uuid, comment_uuid, post_uuid, author_uuid) VALUES (:uuid, :comment_uuid, :post_uuid, :author_uuid)'
        );


        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => (string)$commentlike->getUuid(),
            ':comment_uuid' => $commentlike->getComment()->uuid(),
            ':post_uuid' => $commentlike->getPost()->getUuid(),
            ':author_uuid' => $commentlike->getUser()->uuid()
        ]);

        $this->logger->info("CommentLike created {$commentlike->getUuid()}");
    }

    public function getByCommentUuid(UUID $uuid): array
    {
        $statement = $this->connection->prepare(
            'SELECT *
            FROM commentslikes 
            WHERE comment_uuid=:uuid'
        );

        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);

        $result = $statement->fetchAll();

        if ($result === false) {
            throw new CommentLikeNotFoundException(
                "No likes to this comment: $uuid"
            );
        }
        $this->logger->warning("Cannot find likes to this comment: $uuid");

        return $result;
    }

    public function checkUserLikeForCommentExists($commentUuid, $postUuid, $userUuid): void
    {

        $statement = $this->connection->prepare(
            'SELECT *
            FROM commentslikes 
            WHERE comment_uuid=:comment_uuid AND post_uuid=:post_uuid AND author_uuid=:author_uuid'
        );

        $statement->execute([
            ':comment_uuid' => $commentUuid,
            ':post_uuid' => $postUuid,
            ':author_uuid' => $userUuid
        ]);

        $isExisted = $statement->fetch();

        if ($isExisted) {
            throw new CommentLikeIsAlreadyExists(
                'User like for this comment is already exists'
            );
        }
    }
}
