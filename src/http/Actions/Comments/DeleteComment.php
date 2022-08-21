<?php

namespace GeekBrains\LevelTwo\http\Actions\Comments;


use GeekBrains\LevelTwo\http\Actions\ActionInterface;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\http\Response;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\http\SuccessfulResponse;

class DeleteComment implements ActionInterface
{

    public function __construct(
        private CommentsRepositoryInterface $commentsRepository,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {

            $commentUuid = $request->query('uuid');
            $this->commentsRepository->get(new UUID($commentUuid));
        } catch (CommentNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->commentsRepository->delete(new UUID($commentUuid));
        return new SuccessfulResponse([
            'uuid' => (string)$commentUuid,
        ]);
    }
}
