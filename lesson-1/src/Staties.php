<?php

namespace Php\App;

class Staties
{
    protected ?int $id;
    protected ?int $idAuthor;
    public ?string $title;
    public ?string $text;


    public function __construct(int $id = null, int $idAuthor = null,  string $title = null, string $text = null)
    {
        $this->id = $id;
        $this->idAuthor = $idAuthor;
        $this->title = $title;
        $this->text = $text;
    }

    public function __toString()
    {
        return ' Статья ' . $this->title . ' : ' . $this->text;
    }
}
