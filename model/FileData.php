<?php 
    namespace Model;

    class FileData extends Data { 
        const DATA_PATH = __DIR__ . '/../data/';
        const TYPEOFSWEETS_FILE_TEMPLATE = '/^typeOfSweets-\d\d.txt\z/';
        const SWEET_FILE_TEMPLATE = '/^sweet-\d\d\z/'; 

        protected function getTypesOfSweets($sweetId) {
            $TypesOfSweets = array();
            $counts = scandir(self::DATA_PATH . trim($sweetId));
            foreach ($counts as $node) {
                if (preg_match(self::TYPEOFSWEETS_FILE_TEMPLATE, $node)) {
                    $TypesOfSweets[] = $this->getTypeOfSweets($sweetId, $node);
                }
            }
            return $TypesOfSweets;
        }
        protected function getTypeOfSweets($sweetId, $id) {
            $f = fopen(self::DATA_PATH . trim($sweetId) . "/" . $id, "r");
            $rowStr = fgets($f);
            $rowArr = explode(";", $rowStr);
            $TypeOfSweets = (new TypeOfSweets())
                ->setId(trim($id))
                ->setTypeOfSweetsType(trim($rowArr[0]))
                ->setTypeOfSweetsDateCooked (new \DateTime($rowArr[1]))
                ->setOrderStatus($rowArr[2]);
                if(trim($rowArr[3] == 'великий')) {
                    $TypeOfSweets->setIsSizeBig();
                } else {
                    $TypeOfSweets->setIsSizeSmall();
                }
            fclose($f);
            return $TypeOfSweets;
        }

        protected function getSweets() {
            $sweets = array();
            $counts = scandir(self::DATA_PATH);
            foreach ($counts as $node) {
                if (preg_match(self::SWEET_FILE_TEMPLATE, $node)) {
                    $sweets[] = $this->getSweet($node);
                }
            }
            return $sweets;
        }
        protected function getSweet($id) {
            $f = fopen(self::DATA_PATH . trim($id) . "/sweet.txt", "r"); 
            $swtStr = fgets($f); /*повертає рядок, прочитаний із файлу, на який вказує потік*/
            $swtArr = explode(";", $swtStr); /*повертає масив розділених рядків*/
            fclose($f); 										

            $sweet = (new Sweet())
                ->setId(trim($id))
                ->setSweetName(trim($swtArr[0]))
                ->setSweetQuantity(trim($swtArr[1]))
                ->setSweetShop(trim($swtArr[2]));
            return $sweet;
        }

        protected function getUsers() {
            $users = array();
            $f = fopen(self::DATA_PATH . "users.txt", "r");
            while(!feof($f)) {
                $rowStr = fgets($f);
                $rowArr = explode(";", $rowStr);
                if (count($rowArr) == 3) {
                    $user = (new User()) 
                        ->setUserName(trim($rowArr[0]))
                        ->setPassword(trim($rowArr[1]))
                        ->setRights(substr($rowArr[2],0,9));
                    $users[] = $user;
                }
            }
            fclose($f);
            return $users;
        }
        protected function getUser($id) {
            $users = $this->getUsers();
            foreach($users as $user) {
                if ($user->getUserName() == $id) {
                    return $user;
                }
            }
            return false;
        }
        protected function setTypeOfSweets(TypeOfSweets $typeOfSweets) {
            $f = fopen(self::DATA_PATH . $typeOfSweets->getSweetId() . "/" . $typeOfSweets->getId(), "w");
            $order = 0;
            if ($typeOfSweets->isOrderStatus()) {
                $order = 1;
            }
            $size = 'маленький';
            if ($typeOfSweets->isSizeBig()) {
                $size = 'великий';
            }
            $swtArr = array($typeOfSweets->getTypeOfSweetsType(), $size, $typeOfSweets->getTypeOfSweetsDateCooked()->format('d.m.Y'), $order,);
            $swtStr = implode(";", $swtArr);
            fwrite($f, $swtStr);
            fclose($f);
        }
        protected function delTypeOfSweets(TypeOfSweets $typeOfSweets) {
            unlink(self::DATA_PATH . $typeOfSweets->getSweetId() . "/" . $typeOfSweets->getId());
        }
        protected function insTypeOfSweets(TypeOfSweets $typeOfSweets) {
            //визначаємо останній файл виду солодощів
            $path = self::DATA_PATH . $typeOfSweets->getSweetId();
            $counts = scandir($path);
            $i = 0;
            foreach ($counts as $node) {
                if (preg_match(self::TYPEOFSWEETS_FILE_TEMPLATE, $node)) {
                    $last_file = $node;
                }
            }
            //отримуємо індекс останнього файлу та збільшуємо на 1
            $file_index = (String)(((int)substr($last_file, -6, 2)) + 1);
            if (strlen($file_index) == 1) {
                $file_index = "0" . $file_index;
            }
            //формуємо ім'я нового файлу
            $newFileName = "typeOfSweets-" . $file_index . ".txt";
            
            $typeOfSweets->setId($newFileName);
            $this->setTypeOfSweets($typeOfSweets);
        }
        protected function setSweet(Sweet $sweet) {
            $f = fopen(self::DATA_PATH . $sweet->getId() . "/sweet.txt", "w");
            $swtArr = array($sweet->getSweetName(), $sweet->getSweetQuantity(), $sweet->getSweetShop(),);
            $swtStr = implode(";", $swtArr);
            fwrite($f, $swtStr);
            fclose($f);
        }
        protected function setUser(User $user) {
            $users = $this->getUsers();
            $found = false;
            foreach ($users as $key => $oneUser) {
                if ($user->getUserName() == $oneUser->getUserName()) {
                    $found = true;
                    break;
                }
            }
            if ($found) {
                $users[$key] = $user;
                $f = fopen(self::DATA_PATH . "users.txt", "w");
                foreach($users as $oneUser) {
                    $swtArr = array($oneUser->getUserName(), $oneUser->getPassword(), $oneUser->getRights() . "\r\n",);
                    $swtStr = implode(";", $swtArr);
                    fwrite($f, $swtStr);
                }
                fclose($f);
            }
        }
        protected function delSweet($sweetId) {
            $dirName = self::DATA_PATH . $sweetId;
            $counts = scandir($dirName);
            $i = 0;
            foreach ($counts as $node) {
                @unlink($dirName . "/" . $node);
            }
            @rmdir($dirName);
        }
        protected function insSweet() {
            //визначаємо останню папку солодощів
            $path = self::DATA_PATH;
            $counts = scandir($path);
            foreach ($counts as $node) {
                if (preg_match(self::SWEET_FILE_TEMPLATE, $node)) {
                    $last_sweet = $node;
                }
            }
            //отримуємо індекс останньої папки та збільшуємо на 1
            $sweet_index = (String)(((int)substr($last_sweet, -1, 2)) + 1);
            if (strlen($sweet_index) == 1) {
                $sweet_index = "0" . $sweet_index;
            }
            //формуємо ім'я нової папки
            $newSweetName = "sweet-" . $sweet_index;

            mkdir($path . $newSweetName);
            $f = fopen($path . trim($newSweetName) . "/sweet.txt", "w");
            fwrite($f, "New; ; ");
            fclose($f);
        }
    }
?>