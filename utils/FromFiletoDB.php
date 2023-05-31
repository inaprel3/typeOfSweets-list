<?php 
    require_once '../controller/autorun.php';
    require_once '../data/config.php';
    
    $db=new \Model\MySQLdb();
    //зв'язуємось з БД
    $db->connect();

    //створюємо екземляр моделі для роботи з файлами і встановлюємо поточним користувачем адміністратора
    $fileModel=\Model\Data::makeModel(\Model\Data::FILE);
    $fileModel->setCurrentUser('admin');
    //зчитати дані про користувачів з файлу
    $users=$fileModel->readUsers();
    //занести інформацію про кожного в БД
    foreach($users as $user) {
        $db->runQuery("INSERT INTO Users (username, passwd, rights) VALUES ('".$user->getUsername()."', '".$user->getPassword()."', '".$user->getRights()."')");
    }
    //створюємо екземпляр моделі для роботи з базами даних і встановлюємо поточним користувачем адміна
    $dbModel=\Model\Data::makeModel(\Model\Data::DB);
    $dbModel->setCurrentUser('admin');
    
    //зчитуємо дані про солодощі з файлів
    $sweets=$fileModel->readSweets();
    foreach($sweets as $sweet) {
        //вносимо дані про кожду з них у БД
        $sql="INSERT INTO Sweets (sweetName, sweetQuantity, sweetShop) VALUES('".$sweet->getSweetName()."', '".$sweet->getSweetQuantity()."', '".$sweet->getSweetShop()."')";
        $db->runQuery($sql);
        //Зчитуємо найбільший id солодощів для додавання
        $res=$db->getArrFromQuery("SELECT max(id) id FROM Sweets");
        $id_swt=$res[0]['id'];
        //Зчитуємо з файлів види солодощів
        $typesOfSweets=$fileModel->readTypesOfSweets($sweet->getId());
        foreach($typesOfSweets as $typeOfSweets) {
            //Встановлюємо айді цих солодощів в БД для видів солодощів додаємо його до БД
            $typeOfSweets->setSweetId($id_swt);
            $dbModel->addTypeOfSweets($typeOfSweets);
        }
    }
    //від'єднуємось від БД
    $db->disconnect();
?>