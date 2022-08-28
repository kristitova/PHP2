<?php

namespace GeekBrains\LevelTwo\http\Actions\Posts;


use GeekBrains\LevelTwo\http\Actions\ActionInterface;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\http\Response;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\http\SuccessfulResponse;

class DeletePost implements ActionInterface
{

    public function __construct(
        private PostsRepositoryInterface $postsRepository,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {

            $postUuid = $request->query('uuid');
            $this->postsRepository->get(new UUID($postUuid));
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->postsRepository->delete(new UUID($postUuid));
        return new SuccessfulResponse([
            'uuid' => (string)$postUuid,
        ]);
    }
}
