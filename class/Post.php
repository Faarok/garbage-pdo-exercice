<?php

namespace App;

use DateTime;
use Parsedown;

class Post
{
    public $id;
    public $name;
    public $content;
    public $created_at;

    public function __construct()
    {
        if(is_numeric($this->created_at))
        {
            $this->created_at = new DateTime('@' . $this->created_at);
        }
    }

    public function getExcerpt():string
    {
        return substr($this->content, 0, 150);
    }

    public function getBody():string
    {
        $parsedown = (new Parsedown())->setSafeMode(true);

        return $parsedown->text($this->content);
    }
}