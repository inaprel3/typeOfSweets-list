<?php 
    namespace Model; 

    class Sweet {
        private $id;
        private $sweetName; 
        private $sweetQuantity;
        private $sweetShop;

        public function getId() {
            return $this->id;
        }
        public function setId($id) {
            $this->id = $id;
            return $this;
        }
        public function getSweetName() {
            return $this->sweetName;
        }
        public function setSweetName($sweetName) {
            $this->sweetName = $sweetName;
            return $this;
        }
        public function getSweetQuantity() {
            return $this->sweetQuantity;
        }
        public function setSweetQuantity($sweetQuantity) {
            $this->sweetQuantity = $sweetQuantity;
            return $this;
        }
        public function getSweetShop() {
            return $this->sweetShop;
        }
        public function setSweetShop($sweetShop) {
            $this->sweetShop = $sweetShop;
            return $this;
        }
    }
?>