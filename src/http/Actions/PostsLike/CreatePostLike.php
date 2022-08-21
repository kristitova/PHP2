<?php

namespace GeekBrains\LevelTwo\http\Actions\PostsLike;

use Exception;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\PostsLikeRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Exceptions\HttpException;
use GeekBrains\LevelTwo\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Exceptions\LikeIsAlreadyExists;
use GeekBrains\LevelTwo\http\ErrorResponse;
use GeekBrains\LevelTwo\http\SuccessfulResponse;
use GeekBrains\LevelTwo\http\Response;

class CreatePostLike implements ActionInterface
{
    public function __construct(
        private PostsLikeRepositoryInterface $postslikeRepository,
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {

            $postUuid = $request->jsonBodyField('post_uuid');
            $userUuid = $request->jsonBodyField('author_uuid');
        } catch (HttpException $e) {

            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->postslikeRepository->checkUserLikeForPostExists($postUuid, $userUuid);
        } catch (LikeIsAlreadyExists $e) {

            return new ErrorResponse($e->getMessage());
        }


        try {

            $likeUuid = UUID::random();
            $post = $this->postsRepository->get(new UUID($request->jsonBodyField('post_uuid')));
            $user = $this->usersRepository->get(new UUID($request->jsonBodyField('author_uuid')));
        } catch (Exception $e) {

            return new ErrorResponse($e->getMessage());
        }

        $like = new Like(
            $likeUuid,
            $post,
            $user
        );



        $this->postslikeRepository->save($like);

        return new SuccessfulResponse([
            'uuid' => (string)$likeUuid
        ]);
    }
}
