<?php

namespace GeekBrains\LevelTwo\http\Actions\CommentsLike;

use Exception;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\CommentsLikeRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\CommentLike;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Exceptions\HttpException;
use GeekBrains\LevelTwo\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Exceptions\CommentLikeIsAlreadyExists;
use GeekBrains\LevelTwo\http\ErrorResponse;
use GeekBrains\LevelTwo\http\SuccessfulResponse;
use GeekBrains\LevelTwo\http\Response;
use GeekBrains\LevelTwo\http\Auth\TokenAuthenticationInterface;

class CreateCommentLike implements ActionInterface
{
    public function __construct(
        private CommentsLikeRepositoryInterface $commentslikeRepository,
        private CommentsRepositoryInterface $commentsRepository,
        private PostsRepositoryInterface $postsRepository,
        private TokenAuthenticationInterface $authentication
        // private UsersRepositoryInterface $usersRepository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $commentUuid = $request->jsonBodyField('comment_uuid');
            $postUuid = $request->jsonBodyField('post_uuid');
            $userUuid = $this->authentication->user($request)->uuid();
            //  $userUuid = $request->jsonBodyField('author_uuid');
        } catch (HttpException $e) {

            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->commentslikeRepository->checkUserLikeForCommentExists($commentUuid, $postUuid, $userUuid);
        } catch (CommentLikeIsAlreadyExists $e) {

            return new ErrorResponse($e->getMessage());
        }


        try {

            $commentlikeUuid = UUID::random();
            $comment = $this->commentsRepository->get(new UUID($request->jsonBodyField('comment_uuid')));
            $post = $this->postsRepository->get(new UUID($request->jsonBodyField('post_uuid')));
            //  $user = $this->usersRepository->get(new UUID($request->jsonBodyField('author_uuid')));
            $user = $this->authentication->user($request);
        } catch (Exception $e) {

            return new ErrorResponse($e->getMessage());
        }

        $commentlike = new CommentLike(
            $commentlikeUuid,
            $comment,
            $post,
            $user
        );



        $this->commentslikeRepository->save($commentlike);

        return new SuccessfulResponse([
            'uuid' => (string)$commentlikeUuid
        ]);
    }
}
