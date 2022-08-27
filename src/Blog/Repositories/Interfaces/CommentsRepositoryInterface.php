<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\Interfaces;

use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\UUID;

interface CommentsRepositoryInterface
{
    public function save(Comment $comment): void;
    public function get(UUID $uuid): Comment;
    public function delete(UUID $uuid): void;
}