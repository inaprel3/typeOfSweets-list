<?php 
    namespace View;

    abstract class SweetListView {
        const SIMPLEVIEW = 0;
        const BOOTSTRAPVIEW = 1;
        private $user;

        public function setCurrentUser(\Model\User $user) {
            $this->user=$user;
        }
        public function checkRight($object, $right) {
            return $this->user->checkRight($object, $right); 
        }

        public abstract function showMainForm($sweets, \Model\Sweet $sweet, $typesOfSweets);
        public abstract function showSweetEditForm(\Model\Sweet $sweet);
        public abstract function showTypeOfSweetsEditForm(\Model\TypeOfSweets $typeOfSweets);
        public abstract function showTypeOfSweetsCreateForm();
        public abstract function showLoginForm();
        public abstract function showAdminForm($users);
        public abstract function showUserEditForm(\Model\User $user);

        public static function makeView($type) {
            if ($type == self::SIMPLEVIEW) {
                return new MyView();
            }
            elseif ($type == self::BOOTSTRAPVIEW) {
                return new BootstrapView();
            }
            return new MyView;
        }
    }
?>