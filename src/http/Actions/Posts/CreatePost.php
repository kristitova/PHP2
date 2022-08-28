<?php

namespace GeekBrains\LevelTwo\http\Actions\Posts;

use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Exceptions\HttpException;
use GeekBrains\LevelTwo\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\http\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\http\Auth\AuthenticationInterface;
use GeekBrains\LevelTwo\http\Auth\TokenAuthenticationInterface;
use GeekBrains\LevelTwo\Exceptions\AuthException;
use GeekBrains\LevelTwo\http\ErrorResponse;
use GeekBrains\LevelTwo\http\SuccessfulResponse;
use GeekBrains\LevelTwo\http\Response;
use Psr\Log\LoggerInterface;

class CreatePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private LoggerInterface $logger,
        private TokenAuthenticationInterface $authentication

    ) {
    }

    public function handle(Request $request): Response
    {
        $newPostUuid = UUID::random();

        try {
            $user = $this->authentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }


        try {


            $post = new Post(
                $newPostUuid,
                $user,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text')
            );
        } catch (HttpException $e) {

            return new ErrorResponse($e->getMessage());
        }

        $this->postsRepository->save($post);

        $this->logger->info("Post created: $newPostUuid");

        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }
}
