<?php 
    namespace Controller; 
    
    use Model\Data;
    use View\SweetListView;

    class SweetListApp {
        private $model;
        private $view;

        public function __construct($modelType, $viewType) {
            session_start();
            $this->model = Data::makeModel($modelType);
            $this->view = SweetListView::makeView($viewType);
        }

        public function checkSwt() {
            if ($_SESSION['user']) {
                $this->model->setCurrentUser($_SESSION['user']);
                $this->view->setCurrentUser($this->model->getCurrentUser());
            } else {
                header('Location: ?action=login');
            }
        }
        
        public function run() {
            if(!in_array($_GET['action'], array('login','checkLogin'))) {
                $this->checkSwt();
            }
            if ($_GET['action']) {
                switch ($_GET['action']) {
                    case 'login':
                        $this->showLoginForm();
                        break;
                    case 'checkLogin':
                        $this->checkLogin();
                        break;
                    case 'logout':
                        $this->logout();
                        break;
                    case 'create-sweet':
                        $this->createSweet();
                        break;
                    case 'edit-sweet-form':
                        $this->showEditSweetForm();
                        break;
                    case 'edit-sweet':
                        $this->editSweet();
                        break;
                    case 'delete-sweet':
                        $this->deleteSweet();
                        break;
                    case 'create-typeOfSweets-form':
                        $this->showCreateTypeOfSweetsForm();
                        break;
                    case 'create-typeOfSweets':
                        $this->createTypeOfSweets();
                        break;
                    case 'edit-typeOfSweets-form':
                        $this->showEditTypeOfSweetsForm();
                        break;
                    case 'edit-typeOfSweets':
                        $this->editTypeOfSweets();
                        break;
                    case 'delete-typeOfSweets':
                        $this->deleteTypeOfSweets();
                        break;
                    case 'admin':
                        $this->adminUsers();
                        break;
                    case 'edit-user-form':
                        $this->showEditUserForm();
                        break;
                    case 'edit-user':
                        $this->editUser();
                        break;
                    default:
                        $this->showMainForm();
                }
            } else {
                $this->showMainForm();
            }
        }

        private function showLoginForm() {
            $this->view->showLoginForm();
        }

        private function checkLogin() {
            if ($user=$this->model->readUser($_POST['username'])) {
                if ($user->checkPassWord($_POST['password'])) { 
                    session_start();
                    $_SESSION['user'] = $user->getUserName();
                    header('Location: index.php');
                }
            }
        }

        private function logout() {
            unset($_SESSION['user']);
            header('Location: ?action=login');
        }

        private function showMainForm() {
            $sweets = array();
            if($this->model->checkRight('sweet','view')) {
                $sweets=$this->model->readSweets();
            }
            $sweet = new \Model\Sweet();
            if($_GET['sweet'] && $this->model->checkRight('sweet', 'view')) {
                $sweet=$this->model->readSweet($_GET['sweet']);
            }
            $typesOfSweets = array();
            if($_GET['sweet'] && $this->model->checkRight('typeOfSweets', 'view')) {
                $typesOfSweets=$this->model->readTypesOfSweets($_GET['sweet']);
            }
            $this->view->showMainForm($sweets, $sweet, $typesOfSweets);
        }

        private function createSweet() {
            if(!$this->model->addSweet()) {
                die($this->model->getError());
            } else {
                header('Location: index.php');
            }
        } 
        
        private function showEditSweetForm() {
            if(!$sweet=$this->model->readSweet($_GET['sweet'])) {
                die($this->model->getError());
            }
            $this->view->showSweetEditForm($sweet);
        }

        private function editSweet() {
            if (!$this->model->writeSweet((new \Model\Sweet())
                ->setId(trim($_GET['sweet']))
                ->setSweetName(trim($_POST['sweetName']))
                ->setSweetQuantity(trim($_POST['sweetQuantity']))
                ->setSweetShop(trim($_POST['sweetShop']))
            )) {
                die ($this->model->getError());
            } else {
                header('Location: index.php?sweet=' . trim($_GET['sweet']));
            }
        }

        private function deleteSweet() {
            if (!$this->model->removeSweet($_GET['sweet'])) {
                die ($this->model->getError());
            } else {
                header('Location: index.php');
            }
        }

        private function showEditTypeOfSweetsForm() {
            $typeOfSweets = $this->model->readTypeOfSweets($_GET['sweet'], $_GET['file']);
            $this->view->showTypeOfSweetsEditForm($typeOfSweets);
        }
        
        private function editTypeOfSweets() {
            $typeOfSweets = (new \Model\TypeOfSweets())
              ->setId($_GET['file'])
              ->setSweetId($_GET['id_swt'])
              ->setTypeOfSweetsType($_POST['typeOfSweetsType'])
              ->setTypeOfSweetsDateCooked(new \DateTime($_POST['typeOfSweetsDateCooked']))
              ->setOrderStatus($_POST['orderStatus'])
              ->setSizeSmall();
            if ($_POST['typeOfSweetsSize'] == 'великий') {
                $typeOfSweets->setIsSizeBig();
            }
            if (!$this->model->writeTypeOfSweets($typeOfSweets)) {
                die ($this->model->getError());
            } else {
                header('Location: index.php?sweet=' . $_GET['sweet']);
            }
        }

        private function showCreateTypeOfSweetsForm() {
            $this->view->showTypeOfSweetsCreateForm(); 
        }

        private function createTypeOfSweets() {
            $typeOfSweets = (new \Model\TypeOfSweets())
            ->setSweetId($_GET['sweet'])
            ->setTypeOfSweetsType($_POST['typeOfSweetsType'])
            ->setTypeOfSweetsDateCooked(new \DateTime($_POST['typeOfSweetsDateCooked']))
            ->setOrderStatus($_POST['orderStatus'])
            ->setIsSizeSmall();
            if ($_POST['typeOfSweetsSize'] == 'великий') {
                $typeOfSweets->setIsSizeBig();
            }
            if (!$this->model->addTypeOfSweets($typeOfSweets)) {
                die ($this->model->getError());
            } else {
                    header('Location: index.php?sweet=' . $_GET['sweet']);
            }
        }

        private function deleteTypeOfSweets() {
            $typeOfSweets = (new \Model\TypeOfSweets())->setId($_GET['file'])->setSweetId($_GET['id_swt']);
            if(!$this->model->removeTypeOfSweets($typeOfSweets)) {
                die($this->model->getError());
            } else {
                header('Location: index.php?sweet=' . $_GET['sweet']);
            }
        }

        private function adminUsers() {
            $users = $this->model->readUsers();
            $this->view->showAdminForm($users);
        }

        private function showEditUserForm() {
            if(!$user=$this->model->readUser($_GET['username'])) {
                die($this->model->getError());
            }
            $this->view->showUserEditForm($user);
        }

        private function editUser() {
            $rights="";
            for($i=0; $i<9; $i++) {
                if ($_POST['right' . $i]) {
                    $rights .= "1";
                } else {
                    $rights .= "0";
                }
            }
            $user=(new \Model\User())
            ->setUserName($_POST['user_name'])
            ->setPassword($_POST['pwd'])
            ->setRights($rights);
            if(!$this->model->writeUser($user)) {
                die($this->model->getError());
            } else {
                header('Location: ?action=admin ');
            }
        }
    }
?>