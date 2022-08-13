<?php

namespace Php\App\Blog\Repositories\CommentsRepository;

use Php\App\Blog\Exceptions\InvalidArgumentException;
use Php\App\Blog\Exceptions\CommentNotFoundException;
use Php\App\Blog\Comment;
use Php\App\Blog\UUID;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    private \PDO $connection;

    public function __construct(\PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * @throws CommentNotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
      

        return $this->getComment($statement, $uuid);
    }


    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, post_uuid, author_uuid, text)
                VALUES (:uuid, :post_uuid, :author_uuid, :text)'
        );

       
        var_dump($statement);
// Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => (string)$comment->uuid(),
            ':post_uuid' => (string)$comment->post_uuid(),
            ':author_uuid' => (string)$comment->author_uuid(),
            ':text' => $comment->text()
        ]);
    }    

      /**
     * @throws CommentNotFoundException
     * @throws InvalidArgumentException
     */
    private function getComment(\PDOStatement $statement, UUID $uuid): Comment
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new CommentNotFoundException(
                "Cannot find comment: $uuid"
            );
        }

        return new Comment(
            new UUID($result['uuid']),
            new UUID($result['post_uuid']),
            new UUID($result['author_uuid']),
            $result['text']
        );
    }
}