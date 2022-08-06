<?php

namespace Php\App;

class Comments
{
    protected ?int $id;
    protected ?int $idAuthor;
    protected ?int $idState;
    public ?string $text;



    public function __construct(int $id = null, int $idAuthor = null, int $idState = null, string $text = null)
    {
        $this->id = $id;
        $this->idAuthor = $idAuthor;
        $this->idState = $idState;
        $this->text = $text;
    }

    public function __toString()
    {
        return  $this->idAuthor . ' :  ' . $this->text;
    }
}
