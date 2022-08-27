<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;

use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Person\Name;
use Psr\Log\LoggerInterface;



class SqlitePostsRepository implements PostsRepositoryInterface
{
    private \PDO $connection;
    private LoggerInterface $logger;

    public function __construct(\PDO $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    /**
     * @throws PostNotFoundException
     * @throws InvalidArgumentException|UserNotFoundException
     */
    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT posts.*, users.username, users.password, users.first_name, users.last_name, users.uuid AS author_uuid
             FROM posts LEFT JOIN users
                    ON posts.author_uuid = users.uuid
                    WHERE posts.uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);


        return $this->getPost($statement, $uuid);
    }

    public function delete(UUID $uuid): void
    {
        $statement = $this->connection->prepare(
            'DELETE FROM posts where uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
    }


    public function save(Post $post): void
    {


        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid, :title, :text)'
        );

        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => $post->getUuid(),
            ':author_uuid' => $post->getUser()->uuid(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText()
        ]);

        $this->logger->info("Post created {$post->getUuid()}");
    }


    /**
     * @throws PostNotFoundException
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     */
    private function getPost(\PDOStatement $statement, string $postUuId): Post
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new PostNotFoundException(
                "Cannot find post: $postUuId"
            );
        }

        $this->logger->warning("Cannot find post: $postUuId");

        //$userRepository = new SqliteUsersRepository($this->connection);
        //$user = $userRepository->get(new UUID($result['author_uuid']));

        $user = new User(
            new UUID($result['author_uuid']),
            $result['username'],
            $result['password'],
            new Name($result['first_name'], $result['last_name'])
        );




        return new Post(
            new UUID($result['uuid']),
            $user,
            $result['title'],
            $result['text']
        );
    }
}
