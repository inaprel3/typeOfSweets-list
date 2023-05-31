<?php 
    namespace Model;

    class User {
        private $username;
        private $password;
        private $rights;

        public function getUserName() {
            return $this->username; 
        }
        public function setUserName($username) {
            $this->username = $username;
            return $this;
        }
        public function getPassword() {
            return $this->password;
        }
        public function setPassword($password) {
            $this->password = $password;
            return $this;
        }
        public function checkPassword($password) {
            if ($this->password==$password) {
                return true;
            }
            return false;
        }
        public function getRights() {
            return $this->rights;
        }
        public function getRight($id) {
            return $this->rights[$id];
        }
        public function setRights($rights) {
            $this->rights = $rights;
            return $this;
        }
        public function checkRight($object, $right) { 
            if ($object == 'sweet' && $right == 'view' && $this->getRight(0)) {
                return true;
            }
            if ($object == 'sweet' && $right == 'create' && $this->getRight(1)) {
                return true;
            }
            if ($object == 'sweet' && $right == 'edit' && $this->getRight(2)) {
                return true;
            }
            if ($object == 'sweet' && $right == 'delete' && $this->getRight(3)) {
                return true;
            }
            if ($object == 'typeOfSweets' && $right == 'view' && $this->getRight(4)) {
                return true;
            }
            if ($object == 'typeOfSweets' && $right == 'create' && $this->getRight(5)) {
                return true;
            }
            if ($object == 'typeOfSweets' && $right == 'edit' && $this->getRight(6)) {
                return true;
            }
            if ($object == 'typeOfSweets' && $right == 'delete' && $this->getRight(7)) {
                return true;
            }
            if ($object == 'user' && $right == 'admin' && $this->getRight(8)) {
                return true;
            }
            return false;
        }
    }
?>