<?php 
    namespace Model;

    abstract class Data {
        const FILE = 0;
        const DB=1;

        private $error;
        private $user;  

        public function setCurrentUser($userName) {
            $this->user = $this->readUser($userName);
        }
        public function getCurrentUser() { 
            return $this->user;
        }

        public function checkRight($object, $right) {
            return $this->user->checkRight($object, $right); 
        }
        public function readTypesOfSweets($sweetId) {
            if ($this->user->checkRight('typeOfSweets','view')) { 
                $this->error = "";
                return $this->getTypesOfSweets($sweetId);
            } else {
                $this->error = "You have no permissions to view typesOfSweets";
                return false;
            }
        }
        protected abstract function getTypesOfSweets($sweetId);

        public function readTypeOfSweets($sweetId, $id) {
            if($this->checkRight('typeOfSweets', 'view')) {
                $this->error = "";
                return $this->getTypeOfSweets($sweetId, $id);
            } else {
                $this->error = "You have no permissions to view typeOfSweets";
                return false;
            }
        }
        protected abstract function getTypeOfSweets($sweetId, $id);

        public function readSweets() {
            if($this->checkRight('sweet', 'view')) {
                $this->error = "";
                return $this->getSweets();
            } else {
                $this->error = "You have no permissions to view sweets";
                return false;
            }
        }
        protected abstract function getSweets();

        public function readSweet($id) {
            if($this->checkRight('sweet', 'view')) {
                $this->error = "";
                return $this->getSweet($id);
            } else {
                $this->error = "You have no permissions to view sweet";
                return false;
            }
        }
        protected abstract function getSweet($id);

        public function readUsers() {
            if($this->checkRight('user', 'admin')) {
                $this->error = "";
                return $this->getUsers();
            } else {
                $this->error = "You have no permissions to view users";
                return false;
            }
        }
        protected abstract function getUsers();

        public function readUser($id) {
            $this->error = "";
            return $this->getUser($id);
        }
        protected abstract function getUser($id);

        public function writeTypeOfSweets(TypeOfSweets $typeOfSweets) {
            if($this->checkRight('typeOfSweets', 'edit')) {
                $this->error = "";
                $this->setTypeOfSweets($typeOfSweets);
                return true;
            } else {
                $this->error = "You have no permissions to edit typesOfSweets";
                return false;
            }
        }
        protected abstract function setTypeOfSweets(TypeOfSweets $typeOfSweets);
        
        public function writeSweet(Sweet $sweet) {
            if($this->checkRight('sweet', 'edit')) {
                $this->error = "";
                $this->setSweet($sweet);
                return true;
            } else {
                $this->error = "You have no permissions to edit sweets";
                return false;
            }
        }
        protected abstract function setSweet(Sweet $sweet);

        public function writeUser(User $user) {
            if($this->checkRight('user', 'admin')) {
                $this->error = "";
                $this->setUser($user);
                return true;
            } else {
                $this->error = "You have no permissions to administrate users";
                return false;
            }
        }
        protected abstract function setUser(User $user);

        public function removeTypeOfSweets(TypeOfSweets $typeOfSweets) {
            if($this->checkRight('typeOfSweets', 'delete')) {
                $this->error = "";
                $this->delTypeOfSweets($typeOfSweets);
                return true;
            } else {
                $this->error = "You have no permissions to delete typesOfSweets";
                return false;
            }
        }
        protected abstract function delTypeOfSweets(TypeOfSweets $typeOfSweets);

        public function addTypeOfSweets(TypeOfSweets $typeOfSweets) {
            if($this->checkRight('typeOfSweets', 'create')) {
                $this->error = "";
                $this->insTypeOfSweets($typeOfSweets);
                return true;
            } else {
                $this->error = "You have no permissions to create typesOfSweets";
                return false;
            }
        }
        protected abstract function insTypeOfSweets(TypeOfSweets $typeOfSweets);

        public function removeSweet($sweetId) {
            if($this->checkRight('sweet', 'delete')) {
                $this->error = "";
                $this->delSweet($sweetId);
                return true;
            } else {
                $this->error = "You have no permissions to delete sweets";
                return false;
            }
        }
        protected abstract function delSweet($sweetId);

        public function addSweet() {
            if($this->checkRight('sweet', 'create')) {
                $this->error = "";
                $this->insSweet();
                return true;
            } else {
                $this->error = "You have no permissions to create sweets";
                return false;
            }
        }
        protected abstract function insSweet();

        public function getError() {
            if ($this->error) {
                return $this->error;
            }
            return false;
        }

        public static function makeModel($type) {
            if ($type == self::FILE) {
                return new FileData();
            }
            elseif ($type==self::DB) {
                return new DBData(new MySQLdb());
            }
            return new FileData();
        } 
    }
?>