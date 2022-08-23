<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\Interfaces;

use GeekBrains\LevelTwo\Blog\Like;

use GeekBrains\LevelTwo\Blog\UUID;

interface PostsLikeRepositoryInterface
{
    public function save(Like $like): void;
    public function checkUserLikeForPostExists(UUID $postUuid, UUID $userUuid): void;
}
