<?php 
    namespace Model;

    class DBData extends Data {
        private $db;

        public function __construct(MySQLdb $db) {
            $this->db=$db;
            $this->db->connect();
        }

        protected function getTypesOfSweets($sweetId) {
            $typesOfSweets = array();
            $typeOfSweets_arr = $this->db->getArrFromQuery("SELECT id, typeOfSweetsType, typeOfSweetsDateCooked, id_swt, orderStatus, typeOfSweetsSize FROM TypesOfSweets WHERE id_swt = $sweetId");
            if ($typeOfSweets_arr) {
                foreach ($typeOfSweets_arr as $typeOfSweets_row) {
                    $typeOfSweets = (new TypeOfSweets());
                    $typeOfSweets->setId($typeOfSweets_row['id']);
                    $typeOfSweets->setTypeOfSweetsType($typeOfSweets_row['typeOfSweetsType']);
                    $typeOfSweets->setTypeOfSweetsDateCooked(new \DateTime($typeOfSweets_row['typeOfSweetsDateCooked']));
                    $typeOfSweets->setSweetId($typeOfSweets_row['id_swt']);
                    $typeOfSweets->setOrderStatus($typeOfSweets_row['orderStatus']);
                    if ($typeOfSweets_row['typeOfSweetsSize'] == 'великий') {
                        $typeOfSweets->setIsSizeBig();
                    } else {
                        $typeOfSweets->setIsSizeSmall();
                    }
                    $typesOfSweets[] = $typeOfSweets;
                }
            }
            return $typesOfSweets;
        }

        protected function getTypeOfSweets($sweetId, $id) {
            $typeOfSweets = new TypeOfSweets();
            $typeOfSweets_arr = $this->db->getArrFromQuery("SELECT id, typeOfSweetsType, typeOfSweetsDateCooked, id_swt, orderStatus, typeOfSweetsSize FROM TypesOfSweets WHERE id = $id");
            if ($typeOfSweets_arr && count($typeOfSweets_arr) > 0) {
                $typeOfSweets_row = $typeOfSweets_arr[0];
                $typeOfSweets
                    ->setId($typeOfSweets_row['id'])
                    ->setTypeOfSweetsType($typeOfSweets_row['typeOfSweetsType'])
                    ->setTypeOfSweetsDateCooked(new \DateTime($typeOfSweets_row['typeOfSweetsDateCooked']))
                    ->setSweetId($typeOfSweets_row['id_swt']);
                    $typeOfSweets->setOrderStatus($typeOfSweets_row['orderStatus']);
                if ($typeOfSweets_row['typeOfSweetsSize'] == 'великий') {
                    $typeOfSweets->setIsSizeBig();
                } else {
                    $typeOfSweets->setIsSizeSmall();
                }
            }
            return $typeOfSweets;
        }

        protected function getSweets() {
            $sweets = array();
            $swt_arr = $this->db->getArrFromQuery("SELECT id_swt, sweetName, sweetQuantity, sweetShop FROM Sweets");
            if ($swt_arr) {
                foreach ($swt_arr as $swt_row) {
                    $sweet = (new Sweet())
                        ->setId($swt_row["id_swt"])
                        ->setSweetName($swt_row["sweetName"])
                        ->setSweetQuantity($swt_row["sweetQuantity"])
                        ->setSweetShop($swt_row["sweetShop"]);
                    $sweets[] = $sweet;
                }
            }
            return $sweets;
        }

        protected function getSweet($id) {
            $sweet = new Sweet();
            $swt_arr = $this->db->getArrFromQuery("SELECT id_swt, sweetName, sweetQuantity, sweetShop FROM Sweets WHERE id_swt = $id");
            if ($swt_arr && count($swt_arr) > 0) {
                $swt_row = $swt_arr[0];
                $sweet
                    ->setId($swt_row["id_swt"])
                    ->setSweetName($swt_row["sweetName"])
                    ->setSweetQuantity($swt_row["sweetQuantity"])
                    ->setSweetShop($swt_row["sweetShop"]);
            }
            return $sweet;
        }

        protected function getUsers() {
            $users=array();
            if($user_arr=$this->db->getArrFromQuery("SELECT id, username, passwd, rights FROM Users")) {
                foreach($user_arr as $user_row) {
                    $user=(new User())
                        ->setUsername($user_row["username"])
                        ->setPassword($user_row["passwd"])
                        ->setRights($user_row["rights"]);
                    $users[]=$user;
                }
            }
            return $users;
        }

        protected function getUser($id) {
            $user=new User();
            if($users=$this->db->getArrFromQuery("SELECT id, username, passwd, rights FROM Users WHERE username='".$id."'")) {
                if(count($users)>0) {
                    $user_row=$users[0];
                    $user
                        ->setUsername($user_row["username"])
                        ->setPassword($user_row["passwd"])
                        ->setRights($user_row["rights"]);
                }
            }
            return $user;
        }

        protected function setTypeOfSweets(TypeOfSweets $typeOfSweets) {
            $orderStatus=0;
            if($typeOfSweets->isOrderStatus()) {
                $orderStatus=1;
            }
            $typeOfSweetsSize="фентезі";
            if($typeOfSweets->isSizeBig()) {
                $typeOfSweetsSize="великий";
            }
            $sql="UPDATE TypesOfSweets SET typeOfSweetsType='".$typeOfSweets->getTypeOfSweetsType()."', typeOfSweetsDateCooked='".$typeOfSweets->getTypeOfSweetsDateCooked()->format("d.m.Y")."', id_swt=".$typeOfSweets->getSweetId().", orderStatus=".$orderStatus.", typeOfSweetsSize='".$typeOfSweetsSize."' WHERE id=".$typeOfSweets->getId();
            $this->db->runQuery($sql);
        }
    
        protected function delTypeOfSweets(TypeOfSweets $typeOfSweets) {
            $sql="DELETE FROM TypesOfSweets WHERE id=".$typeOfSweets->getId();
            $this->db->runQuery($sql);
        }
    
        protected function insTypeOfSweets(TypeOfSweets $typeOfSweets) {
            $orderStatus=0;
            if($typeOfSweets->isOrderStatus()) {
                $orderStatus=1;
            }
            $typeOfSweetsSize="маленький";
            if($typeOfSweets->isSizeBig()) {
                $typeOfSweetsSize="великий";
            }
            $sql="INSERT INTO TypesOfSweets(typeOfSweetsType, typeOfSweetsDateCooked, id_swt, orderStatus, typeOfSweetsSize) VALUES('".$typeOfSweets->getTypeOfSweetsType()."','".$typeOfSweets->getTypeOfSweetsDateCooked()->format("d.m.Y")."',".$typeOfSweets->getSweetId().",".$orderStatus.",'".$typeOfSweetsSize."')";
            $this->db->runQuery($sql);
        }
    
        protected function setSweet(Sweet $sweet) {
            $sql="UPDATE Sweets SET sweetName='".$sweet->getSweetName()."', sweetQuantity='".$sweet->getSweetQuantity()."', sweetShop='".str_replace("'","\'",$sweet->getSweetShop())."' WHERE id=".$sweet->getId();
            $this->db->runQuery($sql);
        }
    
        protected function delSweet($sweetId) {
            $sql="DELETE FROM Sweets WHERE id=".$sweetId;
            $this->db->runQuery($sql);
        }
    
        protected function setUser(User $user) {
            $sql="UPDATE Users SET rights='".$user->getRights()."', passwd='".$user->getPassword()."' WHERE username='".$user->getUsername()."'";
            $this->db->runQuery($sql);
        }
    
        protected function insSweet() {
            $sql="INSERT INTO Sweets (sweetName, sweetQuantity, sweetShop) VALUES('new','','')";
            $this->db->runQuery($sql);
        }
    }
?>