<?php

require_once 'model/User.php';
require_once 'model/Book.php';
require_once 'ControllerBook.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';
require_once 'framework/Configuration.php';

class ControllerRental extends Controller {

    public function index() {

        $this->redirect('rental', 'returns');
    }

    public function search_book() {
        $profile = $this->get_user_or_redirect();
        if ($profile->is_admin() || $profile->is_manager()) {
            $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
            $title = "";
            $author = "";
            $date = "";
            $filter = "";
            $filters = [];

            if (isset($_GET['param1']) && $_GET['param1'] != "") {
                $filters = Tools::url_safe_decode($_GET['param1']);

                if (!$filters)
                    Tools::abort("bad url parameter");
            }

            if (isset($_POST["title"]) || isset($_POST["author"]) || isset($_POST["date"]) || isset($_POST["filtre"])) {
                $filters["title"] = $_POST["title"];
                $filters["author"] = $_POST["author"];
                $filters["date"] = $_POST["date"];
                $filters["filtre"] = $_POST["filtre"];
                $this->redirect("rental", "search_book", Tools::url_safe_encode($filters));
            }
            $title = $filters["title"];
            $author = $filters["author"];
            $date = $filters["date"];
            $filter = $filters["filtre"];
            $books = Rental::get_rental_by_critere($title, $author, $filter, $date);

            (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
        } else
            $this->redirect();
    }

    public function add_rental_in_basket() {
        $user = $this->get_user_or_redirect();
        $users = User::get_user_by_username($user->username);
        $books = Rental::get_rental_join_book_join_user_rentdate($user->id);
        $id = 0;
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
        $msg = " ";
        $members = User::get_all_user();
        $usertoAddRent = "";

        if (isset($_POST["idbook"]) && isset($_POST["panierof"])) {
            $usertoAddRent = User::get_user_by_id($_POST["panierof"]);
            $rent = Book::get_book_by_id($_POST["idbook"]);
            if (!Rental::cpt_basket_ok($usertoAddRent->id)) {
                $msg = "Vous ne pouvez pas louer plus de 5 livres a la fois!";
            } else {

                Rental::add_to_basket($user, $usertoAddRent, $rent, $id, $users);
                $books = Rental::get_rental_join_book_join_user_rentdate($usertoAddRent->id);
                $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user();
                $this->redirect("book", "index");
            }
        } (new View("book_manager"))->show(array("books" => $books, "profile" => $users, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function rent_books_in_basket() {
        $user = $this->get_user_or_redirect();
        $books = Book::get_all_books($user->id);
        $msg = " ";
        $usertoAddRent = "";
        $members = User::get_all_user();

        if (isset($_POST["panierof"])) {
            $usertoAddRent = User::get_user_by_id($_POST["panierof"]);
            $allrentofUser = Rental::get_this_rental_not_validate($usertoAddRent->id);
            Rental::rent_basket($user, $usertoAddRent, $allrentofUser, $msg);
        }
        if (isset($_POST["annuler"]) && isset($_POST["panierof"])) {
            Rental::clear_basket($allrentofUser);
            $books = Rental::get_rental_join_book_join_user_rentdate($usertoAddRent->id);
            $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user();
            $allrentofUser = Rental::get_this_rental_not_validate($usertoAddRent->id);
            (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
        }
        if (isset($_POST["solo"])) {
            $msg = Rental::rent_own_basket($usertoAddRent, $allrentofUser);
            $allrentofUser = Rental::get_this_rental_not_validate($usertoAddRent->id);
        }
        $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user_not_rented();
        if (!isset($_POST["annuler"]))
            (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function get_basket() {
        $user = $this->get_user_or_redirect();
        $books = Rental::get_rental_join_book_join_user_rentdate($user->id);
        $msg = " ";
        $usertoAddRent = "";
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
        $members = User::get_all_user();
        $filter = [];
        $value = "";
        if (isset($_GET["param1"])) {
            $filter = Tools::url_safe_decode($_GET["param1"]);
            if (!$filter) {
                Tools::abort("bad url");
            }
        }

        if (isset($_POST["member_rents"])) {
            $filter ["member_rents"] = $_POST["member_rents"];
            $this->redirect("rental/get_basket", Tools::url_safe_encode($filter));
        }
        $usertoAddRent = User::get_user_by_id($filter["member_rents"]);
        $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user_not_rented();
        $books = Rental::get_rental_join_book_join_user_rentdate($usertoAddRent->id);

        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function del_one_rental_in_basket() {
        $user = $this->get_user_or_redirect();
        $books = Book::get_all_books($user->id);
        $getUserRental = $user->get_rental_join_book_join_user_by_user();
        $msg = " ";
        $members = User::get_all_user();
        $usertoAddRent = "";

        if (isset($_POST["delrent"]) && isset($_POST["panierof"])) {
            $usertoAddRent = User::get_user_by_id($_POST["panierof"]);
            Rental::delete_rentals(Rental::get_rental_by_id_book($_POST["delrent"]));
            $getUserRental = $usertoAddRent->get_rental_join_book_join_user_by_user();
            $this->redirect("rental", "index");
        }
        $books = Rental::get_rental_join_book_join_user_rentdate($usertoAddRent->id);
        (new View("book_manager"))->show(array("books" => $books, "profile" => $user, "UserRentals" => $getUserRental, "msg" => $msg, "members" => $members, "actualpanier" => $usertoAddRent));
    }

    public function returns() {
        $profile = $this->get_user_or_redirect();
        $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
        $title = "";
        $author = "";
        $date = "";
        $filter = "";
        (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
    }

    public function delete_rental() {
        $profile = $this->get_user_or_redirect();
        if ($profile->is_admin()) {
            $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
            $title = "";
            $author = "";
            $date = "";
            $filter = "";
            if (isset($_POST["delrent"])) {
                Rental::delete_rentals(Rental::get_rentals_by_id($_POST["delrent"]));
                $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
            }
            (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
        } else
            $this->redirect();
    }

    public function update_rental_returndate() {
        $profile = $this->get_user_or_redirect();
        if ($profile->is_admin() || $profile->is_manager()) {
            $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
            $title = "";
            $author = "";
            $date = "";
            $filter = "";
            $datetime = date("Y-m-d H:i:s");
            if (isset($_POST["idbook"])) {
                $returnRental = Rental::get_rentals_by_id($_POST["idbook"]);
                foreach ($returnRental as $return)
                    $return->update_rental_returndate($datetime);
                $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
                $this->redirect("rental", "index");
            } (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
        } else
            $this->redirect();
    }

    public function cancel_rental_returndate() {
        $profile = $this->get_user_or_redirect();
        if ($profile->is_admin() || $profile->is_manager()) {
            $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
            $title = "";
            $author = "";
            $date = "";
            $filter = "";

            if (isset($_POST["idcancel"])) {
                $returnRental = Rental::get_rentals_by_id($_POST["idcancel"]);
                foreach ($returnRental as $return) {
                    $return->cancel_rental_returndate();
                    $this->redirect("rental", "index");
                }
                $books = Rental::get_rental_join_book_join_user_rentdates($profile->id);
            } (new View("returns"))->show(array("profile" => $profile, "books" => $books, "title" => $title, "author" => $author, "date" => $date, "filter" => $filter));
        } else
            $this->redirect();
    }

    public function getRentalsRessources() {
        $rentaldate = null;
        $title = "";
        $author = "";
        $select = "tous";
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
        }
        if (isset($_POST['author'])) {
            $author = $_POST['author'];
        }
        if (isset($_POST['rentaldate'])) {
            $rentaldate = $_POST['rentaldate'];
        }
        if (isset($_POST['select']) && $_POST['select'] === "") {
            $select = $_POST['select'];
        }
        $this->eventsColorMngmt($title, $author, $rentaldate, $select);
    }

    private function eventsColorMngmt($title, $author, $rentaldate, $select) {
        $rentals = Rental::get_rental_join_book_join_user_rentdatesFilterJs($title, $author, $rentaldate, $select);

        if ($rentals) {
            foreach ($rentals as $rental) {
                if ($rental->returndate == null) {
                    if ($this->is_not_late($rental->rentaldate)) {
                        $rental->eventColor = 'green';
                    } else {
                        $rental->eventColor = 'red';
                    }
                } else {
                    if ($this->is_not_late($rental->rentaldate)) {
                        $rental->eventColor = 'yellow';
                    } else {
                        $rental->eventColor = 'purple';
                    }
                }
                if ($rental->returndate == null) {
                    $rental->end = " pas encore retourné";
                } else {
                    $rental->end = $rental->returndate;
                }
            }
        }
        echo json_encode($rentals);
    }

    private function is_not_late($rentaldate) {
        return date('Y-m-d', strtotime('1 month', strtotime($rentaldate))) >= date('Y-m-d');
    }

    public function getRentalsEvents() {
        $rentals = Rental::get_rental_join_book_join_user_rentdatesJs();
        foreach ($rentals as $rental) {
            $rental->resourceId = $rental->id;
            $rental->start = $rental->rentaldate;
            if ($rental->returndate == NULL) {
                if (date('Y-m-d h:i:s', strtotime('1 month', strtotime($rental->rentaldate))) >= date('Y-m-d h:i:s')) {
                    $rental->end = date('Y-m-d h:i:s', strtotime('1 month', strtotime($rental->rentaldate)));
                } else {
                    $rental->end = date('Y-m-d h:i:s');
                }
            } else {
                $rental->end = $rental->returndate;
            }
        }
        echo json_encode($rentals);
    }

    public function return_date() {
        if (isset($_POST['rentId'])) {
            $rent = Rental::get_rentals_by_id($_POST['rentId']);
            $rent[0]->update_rental_returndate(date('Y-m-d h:i:s'));
        }
    }

    public function delete_RentalJS() {
        if (isset($_POST['rentdel'])) {
            $rent = Rental::get_rentals_by_id($_POST['rentdel']);
            $rent[0]->delete_rental();
        }
    }

}
