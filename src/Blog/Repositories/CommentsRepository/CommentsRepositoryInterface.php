<?php

namespace Php\App\Blog\Repositories\CommentsRepository;

use Php\App\Blog\Comment;
use Php\App\Blog\UUID;

interface CommentsRepositoryInterface
{
    public function save(Comment $comment): void;
    public function get(UUID $uuid): Comment;
}