<?php

namespace GeekBrains\LevelTwo\http\Actions\Comments;

use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Exceptions\HttpException;
use GeekBrains\LevelTwo\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\http\ErrorResponse;
use GeekBrains\LevelTwo\http\SuccessfulResponse;
use GeekBrains\LevelTwo\http\Response;
use GeekBrains\LevelTwo\http\Auth\TokenAuthenticationInterface;

class CreateComment implements ActionInterface
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository,
        private PostsRepositoryInterface $postsRepository,
        // private UsersRepositoryInterface $usersRepository
        private TokenAuthenticationInterface $authentication

    ) {
    }

    public function handle(Request $request): Response
    {
        try {

            //$newAuthorUuid = new UUID($request->jsonBodyField('author_uuid'));
            // $user = $this->usersRepository->get($newAuthorUuid);
            $user = $this->authentication->user($request);

            $newPostUuid = new UUID($request->jsonBodyField('post_uuid'));
            $post = $this->postsRepository->get($newPostUuid);

            $newCommentUuid = UUID::random();

            $comment = new Comment(
                $newCommentUuid,
                $post,
                $user,
                $request->jsonBodyField('text')
            );
        } catch (HttpException $e) {

            return new ErrorResponse($e->getMessage());
        }

        $this->commentsRepository->save($comment);

        return new SuccessfulResponse([
            'uuid' => (string)$newCommentUuid
        ]);
    }
}
