<?php

namespace Php\App\Blog\Repositories\PostsRepository;

use Php\App\Blog\Exceptions\InvalidArgumentException;
use Php\App\Blog\Exceptions\PostNotFoundException;
use Php\App\Blog\Post;
use Php\App\Blog\UUID;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    private \PDO $connection;

    public function __construct(\PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * @throws PostNotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);


        return $this->getPost($statement, $uuid);
    }


    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text)
                VALUES (:uuid, :author_uuid, :title, :text)'
        );

       
        var_dump($statement);
// Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => (string)$post->uuid(),
            ':author_uuid' => (string)$post->author_uuid(),
            ':title' => $post->title(),
            ':text' => $post->text()
        ]);
    } 
    
     /**
     * @throws PostNotFoundException
     * @throws InvalidArgumentException
     */
    private function getPost(\PDOStatement $statement, string $postUuid): Post
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new PostNotFoundException(
                "Cannot find post: $postUuid"
            );
        }

        return new Post(
            new UUID($result['uuid']),
            new UUID($result['author_uuid']),
            $result['title'],
            $result['text']
        );
    }
}