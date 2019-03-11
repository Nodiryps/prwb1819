<?php

require_once 'model/User.php';
require_once 'model/Book.php';
require_once 'model/Rental.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerBook extends Controller {

    public function index() {
        $user = $this->get_user_or_redirect();
        $books = Book::get_all_books();
        $getUserRental = $user->get_rental_join_book_join_user_by_user_not_rented();
        $members = User::get_all_user();
        $usertoAddRent = $user;
        $msg = " ";

        if (isset($_POST["search"])) {
            $value = $_POST["search"];
            $books = Book::get_book_by_critere($value);
            $msg = " ";
        }
        if (empty($_POST["search"]))
            $books = Book::get_all_books();

        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function add_book() {
        $user = $this->get_user_or_redirect();
        if ($user->is_admin()) {
            $isbn = "";
            $title = "";
            $author = "";
            $editor = "";
            $errors = [];
            $picture_path = "";
            $nbcopies = "";
            if (isset($_POST["isbn"]) && isset($_POST["author"]) && isset($_POST["title"]) && isset($_POST["editor"]) && isset($_POST["nbCopie"])) {
                $isbn = $_POST["isbn"];
                $title = $_POST["title"];
                $author = $_POST["author"];
                $editor = $_POST["editor"];
                $nbcopies = $_POST["nbCopie"];
                $errors = Book::rules_add_book($isbn, $title, $author, $editor);
                if (!Book::existIsbn($isbn))
                    $errors[] = "ISBN existe deja !";
                if (isset($_FILES['picture']) && isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
                    if ($_FILES['picture']['error'] == 0) {
                        $infosfichier = pathinfo($_FILES['picture']['name']);
                        $extension_upload = $infosfichier['extension'];
                        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                        if (in_array($extension_upload, $extensions_autorisees)) {
                            $picture_path = $title . "." . $extension_upload;
                            move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $picture_path);
                        }
                    }
                }

                if (empty($errors)) {
                    $book = new Book(0, $isbn, $title, $author, $editor, $picture_path, $nbcopies);
                    $book->create();
                    $this->redirect("book", "index");
                }
            }
        }
        (new View("add_book"))->show(array("errors" => $errors, "isbn" => $isbn, "title" => $title, "author" => $author, "editor" => $editor, "nbCopie" => $nbcopies));
    }

    private function add_picture($title, $picture_path) {
        if (isset($_FILES['picture']) && isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
            if ($_FILES['picture']['error'] == 0) {
                $infosfichier = pathinfo($_FILES['picture']['name']);
                $extension_upload = $infosfichier['extension'];
                $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                if (in_array($extension_upload, $extensions_autorisees)) {
                    $titleOk = preg_replace('~[\\\\/.,;:*!?"<>|]~', '', $title); // remplace les char par '' dans $title
                    $picture_path = $titleOk . "." . $extension_upload;
                    move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $picture_path);
                }
            }
        } return $picture_path;
    }

    
    public function delete_book() {
        $user = Controller::get_user_or_redirect();
        if ($user->is_admin()) {
            $usertoAddRent = $user;
            $getUserRental = $user->get_rental_join_book_join_user_by_user_not_rented();
            $books = Book::get_all_books();
            $members = User::get_all_user();
            if (isset($_POST["delbook"])) {
                $delbook = Book::get_book_by_id($_POST["delbook"]);
            }
            if (isset($_POST["conf"])) {
                $delbook = Book::get_book_by_id($_POST["conf"]);
                unlink("uploads/" . $delbook->picture);
                $delbook->delete_book();
                $this->redirect("book", "index");
            }

            (new View("delete_confirm"))->show(array("book" => $delbook));
        }
    }

    public function book_detail() {
        $user = $this->get_user_or_redirect();
        if (isset($_POST["idbook"])) {
            $value = $_POST["idbook"];
            $book = Book::get_book_by_id($value);
        } (new View("book_detail"))->show(array("book" => $book, "profile" => $user));
    }

    public function edit_book() {
        $user = $this->get_user_or_redirect();
        if ($user->is_admin()) {
            $book = "";
            $errors = [];
            $pathToDel = "";
            $oldpath = "";
            $bookpicToDel = "";
            $nbCopie="";
            if (isset($_POST['editbook'])) {
                $book = Book::get_book_by_id($_POST['editbook']);
                $oldpath = $book->picture;
            }
            if (isset($_POST["delimageH"])) { // bouton effacer img
                $edit = $_POST["delimageH"];
                $bookpicToDel = Book::get_book_by_id($edit);
                if ($bookpicToDel->picture !== NULL) {
                    unlink("uploads/" . $bookpicToDel->picture);
                    $bookpicToDel->delete_image();
                } else
                    $errors[] = "Pas d'image à effacer...";
                $book = Book::get_book_by_id($edit);
                (new View("edit_book"))->show(array("book" => $book, "errors" => $errors, "profile" => $user,"nbCopie"=>$nbCopie)); // pour "refresh" l'img suppr
            }

            if (isset($_POST["cancel"])) { // boutton annuler
                $this->redirect("book", "index");
            }

            if (isset($_POST['idbook']) && isset($_POST['isbn']) || isset($_POST['title']) || isset($_POST['editor']) || isset($_POST['author']) || isset($_POST['nbCopie'])) {
                if (!empty($_POST['idbook']))
                    $book = Book::get_book_by_id($_POST['idbook']);
                if (isset($_POST['isbn']) && isset($_POST['isbn']) !== "")
                    $book->isbn = $this->isbn_format_string($_POST['isbn']);
                if (isset($_POST['title']) && isset($_POST['title']) !== "")
                    $book->title = $_POST['title'];
                if (isset($_POST['author']) && isset($_POST['author']) !== "")
                    $book->author = $_POST['author'];
                if (isset($_POST['editor']) && isset($_POST['editor']) !== "")
                    $book->editor = $_POST['editor'];
                 if (empty($_POST['nbCopie']))
                     $book->nbCopies = $_POST["nbCopie"];
                $errors = Book::rules_add_book($book->isbn, $book->title, $book->author, $book->editor);
                $picture_path = "";
                if (isset($_FILES['picture']) && isset($_FILES['picture']['name']) && $_FILES['picture']['name'] != '') {
                    if ($_FILES['picture']['error'] == 0) {
                        $infosfichier = pathinfo($_FILES['picture']['name']);
                        $extension_upload = $infosfichier['extension'];
                        $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
                        if (in_array($extension_upload, $extensions_autorisees)) {
//                            $titleOk = $this->titleOk($book->title); // remplace les char par '' dans $book->title
                            $picture_path = $book->title . "." . $extension_upload;
                            move_uploaded_file($_FILES['picture']['tmp_name'], 'uploads/' . $picture_path);
                            $book->picture = $picture_path;
                        }
                    }
                }

                if (empty($errors)) {
                    $book->update();
                    $this->redirect("book", "index");
                }
            }
            if (!isset($_POST["delimageH"]))  // sinon 2 views qd on "refresh" avec le boutton effacer img
                (new View("edit_book"))->show(array("book" => $book, "errors" => $errors, "profile" => $user,"nbCopie"=>$nbCopie));
        } else
            $this->redirect("book", "index");
    }

    private function delete_img($id) {
        $book = Book::get_book_by_id($id);
        $bookpicToDel = $book;
        $bookpicToDel->delete_image();
        (new View("edit_book"))->show(array("book" => $book, "errors" => $errors, "profile" => $user)); // pour "refresh" l'img suppr
    }


    public static function isbn_format_EAN_13($isbn) {
        return substr($isbn, 0, 3) . "-" . substr($isbn, 3, 1) . "-"
                . substr($isbn, 4, 4) . "-" . substr($isbn, 8, 4) . "-" . substr($isbn, 12, 1);
    }

    public static function isbn_format_string($isbn) {
        return str_replace('-', '', $isbn);
    }

}
