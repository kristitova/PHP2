<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\Interfaces;

use GeekBrains\LevelTwo\Blog\CommentLike;

use GeekBrains\LevelTwo\Blog\UUID;

interface CommentsLikeRepositoryInterface
{
    public function save(CommentLike $commentlike): void;
    public function checkUserLikeForCommentExists(UUID $commentUuid, UUID $postUuid, UUID $userUuid): void;
}
