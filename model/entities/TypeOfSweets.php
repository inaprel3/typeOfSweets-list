<?php 
    namespace Model;

    class TypeOfSweets {
        const BIG = 0;
        const SMALL = 1;

        private $id;
        private $typeOfSweetsType;
        private $typeOfSweetsDateCooked; 
        private $typeOfSweetsSize;
        private $orderStatus; 
        private $sweetId;

        public function getId() {
            return $this->id;
        }
        public function setId($id) {
            $this->id = $id;
            return $this;
        }
        public function getTypeOfSweetsType() {
            return $this->typeOfSweetsType;
        }
        public function setTypeOfSweetsType($typeOfSweetsType) {
            $this->typeOfSweetsType = $typeOfSweetsType;
            return $this;
        }
        public function getTypeOfSweetsDateCooked() {
            return $this->typeOfSweetsDateCooked;
        }
        public function setTypeOfSweetsDateCooked($typeOfSweetsDateCooked) {
            $this->typeOfSweetsDateCooked = $typeOfSweetsDateCooked;
            return $this;
        }
        public function isSizeBig() {
            return ($this->typeOfSweetsSize == self::BIG);
        }
        public function isSizeSmall() {
            return !($this->typeOfSweetsSize == self::SMALL);
        }
        public function setIsSizeBig() {
            $this->typeOfSweetsSize = self::BIG;
            return $this;
        }
        public function setIsSizeSmall() {
            $this->typeOfSweetsSize = self::SMALL;
            return $this;
        }
        public function isOrderStatus() {
            return $this->orderStatus;
        }
        public function setOrderStatus($orderStatus) {
            $this->orderStatus = $orderStatus;
            return $this;
        }
        public function getSweetId() {
            return $this->sweetId;
        }
        public function setSweetId($sweetId) {
            $this->sweetId = $sweetId;
            return $sweetId;
        }
    }
?>