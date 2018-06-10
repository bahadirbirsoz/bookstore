<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1.06.2018
 * Time: 11:33
 */

namespace Bookstore\Controller;

use Bookstore\Model\Book;
use Bookstore\Util\App\Route;


class BookController extends ControllerBase
{

    /**
     * @var Book
     */
    protected $book;

    public function mainAction($page = 0, $paginationLimit = 50)
    {
        $this->view->books = Book::find();

    }

    public function addAction()
    {

    }

    public function editAction($bookId)
    {
        if (!$this->canEdit($bookId)) {
            $this->app->route(Route::_("")->setController("error")->setAction("unauthorized"));
        }
        $this->view->book = $this->book;

    }

    public function canEdit($bookId)
    {
        $this->book = Book::find($bookId);
        return $this->auth->getMember()->member_id == $this->book->member_id;
    }

}
