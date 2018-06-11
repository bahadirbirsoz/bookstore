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
        $this->view->books = Book::findByMemberId($this->auth->getMember()->member_id);

    }

    public function addAction()
    {
        $this->book = new Book();
        if ($this->request->isPost() && $this->csrf->check($this->request->getPost('csrf'))) {
            $this->book->author = $this->request->getPost("author");
            $this->book->title = $this->request->getPost("title");
            $this->book->isbn = $this->request->getPost("isbn");
            $this->book->member_id = $this->auth->getMember()->member_id;
            $this->book->save();
            $this->response->redirect("/book");
            $this->view->successMessage = true;
        }
        $this->view->csrf = $this->csrf->register();
        $this->view->setAction("book", "edit");
        $this->view->book = $this->book;
    }

    public function editAction($bookId)
    {
        $this->book = Book::findById($bookId);
        if (!$this->book) {
            return Route::_("")->setNamespace("\Bookstore\Controller")->setController("error")->setAction("main")->setParams([404]);
        }

        if (!$this->canEdit($bookId)) {
            return Route::_("")->setController("error")->setAction("main")->setParams([401]);
        }

        if ($this->request->isPost() && $this->csrf->check($this->request->getPost('csrf'))) {
            $this->book->author = $this->request->getPost("author");
            $this->book->title = $this->request->getPost("title");
            $this->book->isbn = $this->request->getPost("isbn");
            $this->book->save();
            $this->response->redirect("/book");
        }
        $this->view->csrf = $this->csrf->register();
        $this->view->book = $this->book;

    }

    public function deleteAction($bookId)
    {
        if ($this->request->isPost() && $this->csrf->check($this->request->getPost('csrf'), $bookId . "book")) {
            $book = Book::delete($bookId);
            $this->response->redirect("/book");
        }
        $this->view->csrf = $this->csrf->register($bookId . "book");

    }

    public function canEdit($bookId)
    {

        return $this->auth->isMember() && ($this->auth->getMember()->member_id == $this->book->member_id);
    }

}
