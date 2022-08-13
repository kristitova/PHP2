<?php

namespace Php\App\Blog;

use Php\App\Blog\UUID;

class Post
{


    public function __construct(
        private UUID $uuid,
        private UUID $author_uuid,
        private string $title,
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

    public function __toString()
    {
        return $this->author_uuid . ' пишет: ' . $this->text;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function text(): string
    {
        return $this->text;
    }
}
