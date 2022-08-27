<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Post;

class Comment
{
    public function __construct(
        private UUID $uuid,
        private Post $post,
        private User $user,
        private string $text

    ) {
    }
    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function __toString()
    {
        return 'Комментарий ' . $this->text . ' автора: ' . $this->user->uuid() . ' к статье ' . $this->post->getUuid();
    }


    /**
     * @return string
     */
    public function text(): string
    {
        return $this->text;
    }
}
