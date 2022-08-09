<?php

namespace Php\App\Blog\Repositories\PostsRepository;

use Php\App\Blog\Post;
use Php\App\Blog\UUID;

interface PostsRepositoryInterface
{
    public function save(Post $post): void;
    public function get(UUID $uuid): Post;
}