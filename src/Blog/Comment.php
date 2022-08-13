<?php

namespace Php\App\Blog;

use Php\App\Blog\UUID;

class Comment
{
    public function __construct(
        private UUID $uuid,
        private UUID $post_uuid,
        private UUID $author_uuid,
        private string $text
    
    )
    {
    }
    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function author_uuid(): UUID
    {
        return $this->author_uuid;
    }

    public function post_uuid(): UUID
    {
        return $this->post_uuid;
    }

    public function __toString()
    {
        return 'Комментарий ' . $this->text . ' автора: ' . $this->author_uuid . ' к статье ' . $this->post_uuid;
    }


    /**
     * @return string
     */
    public function text(): string
    {
        return $this->text;
    }
}
