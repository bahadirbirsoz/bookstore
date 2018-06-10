<?php

namespace Bookstore\Model;

use Bookstore\Util\Mvc\Model;

class Book extends Model
{
    public $book_id;
    public $member_id;
    public $title;
    public $author;
    public $isbn;
}