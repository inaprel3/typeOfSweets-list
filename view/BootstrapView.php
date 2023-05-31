<?php 
    namespace View;

    class BootstrapView extends SweetListView {
        const ASSETS_FOLDER = 'view/bootstrap/';

        private function showUserInfo() {
            ?>
            <div class="container user-info">
                <div class="row">
                    <div class="col-md-12 col-md-offset-12 text-center lead">
                        <p><span style="color: white; font-size: 18pt; background: rgba(48, 48, 48, 0.74)">Ласкаво просимо, <?php echo $_SESSION['user']; ?> !</span></p>
                        <?php if ($this->checkRight('user','admin')): ?>
                        <a class="btn btn-primary" href="?action=admin" style="font-size: 18pt">Адміністрування</a>
                        <?php endif; ?>
                        <a class="btn btn-info" href="?action=logout" style="font-size: 18pt">Вийти</a>
                    </div>
                </div>
            </div>
        <?php
        }

        private function showSweets($sweets) {
            ?>
            <div class="container sweet-list">
                <div class="row">
                    <form name='sweet-form' method='get' class="col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6">
                        <?php if($this->CheckRight('sweet', 'create')):?>
                        <p><a style="font-size: 18pt" class='btn btn-success' href="?action=create-sweet">Додати солодощі</a></p>
                        <?php endif; ?>
                        <div class="form-sweet">
                            <label for="sweet" style="color: white; font-size: 18pt; background: rgba(48, 48, 48, 0.74)">Солодощі: </label>
                            <select style="font-size: 18pt" name="sweet" class="form-control" onchange="document.forms['sweet-form'].submit();">
                                <option value=""></option>
                                <?php
                                    foreach ($sweets as $cursweet) {
                                        echo "<option " . (($cursweet->getId() == $_GET['sweet']) ? "selected":"") . "value='" . $cursweet->getId() . "''>" . $cursweet->getSweetName() . "</option>";
                                }?>
                            </select>
                        </div>
		            </form>
                </div>
            </div>
        <?php
        }

        private function showSweet(\Model\Sweet $sweet) {
            ?>
            <div class="container sweet-info">
                <div class="text-right">
                    <p><h3 class="col-xs-12">Назва: <span class='text-primary'><?php echo trim($sweet->getSweetName()); ?></span> 
		            </h3></p>
		            <h3 class="col-xs-12">Кількість: <span class='text-danger'><?php echo trim($sweet->getSweetQuantity()); ?></span> 
		            </h3>
		            <p><h3 class="col-xs-12">Магазин: <span class='text-success'><?php echo trim($sweet->getSweetShop()); ?></span> 
		            </h3></p>
		            <div class='control col-xs-12'>
		                <?php if($this->CheckRight('sweet', 'edit')):?>
			                <a style="font-size: 18pt" class="btn btn-primary" href="?action=edit-sweet-form&sweet=<?php echo trim($_GET['sweet']); ?>">Редагувати солодощі</a>
			            <?php endif; ?>
			            <?php if($this->CheckRight('sweet', 'delete')):?> 
			                <a style="font-size: 18pt" class="btn btn-danger" href="?action=delete-sweet&sweet=<?php echo $_GET['sweet']; ?>">Видалити солодощі</a>
			            <?php endif; ?>
                    </div>
                </div>
		    </div>
        <?php
        }

        private function showTypesOfSweets($typesOfSweets) {
            ?>
            <section class="container typesOfSweets">
                <div class="row text-center">
                    <?php if($_GET['sweet']): ?>
                    <?php if($this->CheckRight('typeOfSweets', 'create')):?> 
		            <div class="col-xs-12 col-md-2 col-md-offset-1 text-center add-typeOfSweets">
			            <p><a style="font-size: 18pt" class="btn btn-success" href="?action=create-typeOfSweets-form&sweet=<?php echo trim($_GET['sweet']); ?>">Додати вид солодощів</a></p>
		            </div>
                </div>
		        <?php endif; ?>
                <div class="text-left col-xs-offset-2 col-xs-8">
                    <form name='typesOfSweets-filter' method='post'>
                        <div class="col-xs-12">
			                <label style="color: white; font-size: 18pt; background: rgba(48, 48, 48, 0.74)" for ="typeOfSweetsTypeFilter">Фільтрувати за назвою:</label> <input type="submit" style="font-size: 18pt" value="Фільтрувати">
                            <input class="form-control" type="text" name="typeOfSweetsTypeFilter" value='<?php echo strip_tags(trim($_POST['typeOfSweetsTypeFilter']) ?? '');?>'>
                        </div>
		            </form>
                </div>
                <p> </p>
                <div class="row text-center table-typesOfSweets">
                    <table class="table col-xs-10">
			            <thead>
				            <tr style="font-size: 18pt; color: white; background: rgba(48, 48, 48, 0.74)"><label style="color: white; font-size: 18pt; background: rgba(48, 48, 48, 0.74)">Види солодощів:</label>
					            <th>№</th>
					            <th>Тип:</th>
                                <th>Розмір:</th>
					            <th>Дата виготовлення:</th>
					            <th></th>
				            </tr>
			            </thead>
			            <tbody>
                            <?php if (count($typesOfSweets) > 0): ?>
                            <?php foreach ($typesOfSweets as $key => $typeOfSweets): ?>
				            <?php if(!trim($_POST['typeOfSweetsTypeFilter']) || stristr($typeOfSweets->getTypeOfSweetsType(), trim($_POST['typeOfSweetsTypeFilter']))): ?>
				            <?php $row_class = '';
					        if ($typeOfSweets->isSizeBig()) {
						        $row_class = 'bg-info';
					        }
					        if ($typeOfSweets->isSizeSmall()) { 
						        $row_class = 'bg-dark';
					        }
				            ?>

				            <tr style="font-size: 18pt; color: white; background: rgba(48, 48, 48, 0.74)" class='<?php echo $row_class; ?>'>
					            <td><?php echo ($key + 1); ?></td>
					            <td><?php echo trim($typeOfSweets->getTypeOfSweetsType()); ?></td> 
					            <td><?php echo trim($typeOfSweets->isSizeBig())?'великий':'маленький'; ?></td>
					            <td>
                                    <?php echo date_format($typeOfSweets->getTypeOfSweetsDateCooked(), 'd.m.Y'); ?>
                                </td>
					            <td>
						            <?php if($this->CheckRight('typeOfSweets', 'edit')):?> 
						                <a style="font-size: 18pt" class="btn btn-primary btn-xs" href='?action=edit-typeOfSweets-form&sweet=<?php echo trim($_GET['sweet']); ?>&file=<?php echo $typeOfSweets->getId();?>'>Замовити</a>
						            <?php endif; ?>
						            <?php if($this->CheckRight('typeOfSweets', 'delete')):?> 
						                <a style="font-size: 18pt" class="btn btn-danger btn-xs" href='?action=delete-typeOfSweets&sweet=<?php echo trim($_GET['sweet']) ?>&file=<?php echo $typeOfSweets->getId();?>'>Видалити</a>
						            <?php endif; ?>
					            </td>
				            </tr>  
				        <?php endif; ?>
				        <?php endforeach; ?>
                        <?php endif; ?>
			            </tbody>
		            </table>
                    <?php endif; ?>
                </div>
            </section>
            <?php
        }

        public function showMainForm($sweets, \Model\Sweet $sweet, $typesOfSweets) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.css">
	            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
	            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/main.css">
	            <script src="<?php echo self::ASSETS_FOLDER; ?>js/jquery.min.js"></script>
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/bootstrap.min.js"></script>
                <title>Онлайн-кондитерська</title>
            </head>
            <body>
                <header>
		            <?php $this->showUserInfo();?>
                    <?php
                        if ($this->checkRight('sweet', 'view')) {
                            $this->showSweets($sweets);
                            if ($_GET['sweet'])$this->showSweet($sweet);
                    }?>
	            </header>
                <?php
                    if($this->checkRight('typeOfSweets', 'view')) {
                        $this->showTypesOfSweets($typesOfSweets);
                }?>
            </body>
            </html>
            <?php
        }

        public function showSweetEditForm(\Model\Sweet $sweet) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
	            <title>Редагування солодощів</title>
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.css">
	            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/jquery.min.js"></script>
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/bootstrap.min.js"></script>
            </head>
            <body>
            <div class="container">
                <div class="row">
	                <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
	                    <form name='edit-sweet' method='post' style="font-size: 18pt; color: white" action="?action=edit-sweet&sweet=<?php echo $_GET['sweet'];?>">
                            <p><a class="btn btn-info btn-sm pull-center" style="font-size: 18pt" href="index.php?sweet=<?php echo $_GET['sweet'];?>">На головну</a>
		                    <div class="form-sweet"></p>
                                <label for='sweetName' style="font-size: 18pt; color: white; background: rgba(48, 48, 48, 0.74)">Назва: </label>
                                    <p><input type="text" name="sweetName" value="<?php echo $sweet->getSweetName(); ?>"></p>
                            </div>
		                    <div class="form-sweet">
                                <label for='sweetQuantity' style="font-size: 18pt; color: white; background: rgba(48, 48, 48, 0.74)">Кількість: </label>
                                    <p><input type="text" name="sweetQuantity" value="<?php echo $sweet->getSweetQuantity(); ?>"></p>
		                    </div>
		                    <div class="form-sweet">
			                    <label for='sweetShop' style="font-size: 18pt; color: white; background: rgba(48, 48, 48, 0.74)">Магазин: </label>
                                    <p><input type="text" name="sweetShop" value="<?php echo $sweet->getSweetShop(); ?>"></p>
		                    </div>
                            <p><button type="submit" class="btn btn-success pull-right" style="font-size: 18pt" href="index.php?sweet=<?php echo $_GET['sweet'];?>">Змінити</button></p>
	                    </form>
                    </div>
                </div>
            </div>
            </body>
            </html>
            <?php
        }

        public function showTypeOfSweetsEditForm(\Model\TypeOfSweets $typeOfSweets) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Редагування виду солодощів</title>
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.css">
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/checkbox.css">
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/jquery.min.js"></script>
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/bootstrap.min.js"></script>
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
            </head>
            <body>
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
                            <form name='edit-typeOfSweets' method='post' style="color: white; font-size: 18pt" action="?action=edit-typeOfSweets&file=<?php echo $_GET['file'];?>&sweet<?php echo $_GET['sweet'];?>">
                                <p><a class="btn btn-info pull-center btn-sm" style="font-size: 18pt" href="index.php?sweet=<?php echo $_GET['sweet'];?>">На головну</a></p>
                                <div class="form-sweet">
                                    <label for='typeOfSweetsType' style="font-size: 18pt; color: white; background: rgba(48, 48, 48, 0.74)">Тип: </label>
                                    <input class="form-control" type="text" name="typeOfSweetsType" value="<?php echo trim($typeOfSweets->getTypeOfSweetsType()); ?>">
                                </div>
                                <div class="form-sweet">
                                    <label for='date_cooked' style="font-size: 18pt; color: white; background: rgba(48, 48, 48, 0.74)">Дата виготовлення: </label>
                                    <input class="form-control" type="date" name="date_cooked" value="<?php echo $typeOfSweets->getTypeOfSweetsDateCooked()->format('d.m.Y'); ?>">
                                </div>
                                <div class="form-sweet">
                                    <label for='size' style="font-size: 18pt; color: white; background: rgba(48, 48, 48, 0.74)">Розмір: </label>
                                    <select class="form-control" name="size">
                                        <option disabled>Розмір</option>
                                        <option <?php echo (trim($typeOfSweets->isSizeBig()))?"selected":""; ?> value="великий">великий</option>
                                        <option <?php echo (trim($typeOfSweets->isSizeSmall()))?"selected":""; ?> value="маленький">маленький</option>
                                    </select>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" <?php echo ($typeOfSweets->isOrderStatus())?"checked":""; ?> name="order" value=1 style="font-size: 18pt">Замовлено
                                    </label>
                                </div>
                                <p><a class="btn btn-success pull-right" style="font-size: 18pt" href="index.php?sweet=<?php echo $_GET['sweet'];?>">Змінити</a></p>
                                <a class="btn btn-success pull-right" style="font-size: 18pt" href="#" onclick="togglePaymentMessage(); return false;">Оплатити</a>
                                <div id="paymentMessage" style="font-size: 18pt; color: white; background: rgba(48, 48, 48, 0.74); display: none;">Ваше замовлення сплачено</div>
                                <script>
                                    var paymentMessageVisible = false;
                                    function togglePaymentMessage() {
                                        var paymentMessage = document.getElementById('paymentMessage');
                                        paymentMessageVisible = !paymentMessageVisible;
                                        if (paymentMessageVisible) {
                                            paymentMessage.style.display = 'block';
                                        } else {
                                            paymentMessage.style.display = 'none';
                                        }
                                    }
                                </script>
                            </form>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            <?php
        }

        public function showTypeOfSweetsCreateForm() {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
	            <title>Додавання виду солодощів</title>
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.css">
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/checkbox.css">
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/jquery.min.js"></script>
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/bootstrap.min.js"></script>
            </head>
            <body>
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
                            <form name='edit-typeOfSweets' method='post' action="?action=create-typeOfSweets&sweet=<?php echo $_GET['sweet'];?>">
                                <a class="btn btn-info pull-center btn-sm" href="index.php?sweet=<?php echo $_GET['sweet'];?>">На головну</a>
		                        <div class="form-sweet">
	                                <label for='typeOfSweetsType'>Тип: </label>
	                                <input class="form-control" type="text" name="typeOfSweetsType">
	                            </div>
	                            <div class="form-sweet">
	                                <label for='date_cooked'>Дата виготовлення: </label>
	                                <input class="form-control" type="date" name="date_cooked">
	                            </div>
	                            <div class="form-sweet">
	                                <label for='size'>Розмір: </label>
	                                <select class="form-control" name="size">
	                                    <option disabled>Розмір</option>
	                                    <option value="великий">великий</option>
	                                    <option value="маленький">маленький</option>
	                                    <option value="інше">інше</option>
	                                </select>
	                            </div>
	                            <div class="checkbox">
                                    <label><input type="checkbox" name="order" value=1 style="font-size: 18pt">Замовлено</label>
	                            </div>
                                <button type="submit" class="btn btn-success pull-right" style="font-size: 18pt">Додати</button>
	                        </form>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            <?php
        }

        public function showLoginForm() {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Аутентифікація</title>
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.css">
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/login.css">
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/jquery.min.js"></script>
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/bootstrap.min.js"></script>
            </head>
            <body>
                <form method="post" action="?action=checkLogin">
                    <div class="container">
                        <div class="row text-center">
                            <div class="col-sm-6 col-md-4 col-lg-3 col-sm-offset-3 col-md-offset-4" style="margin: 0 auto;"> 
                                <div class="form-sweet">
                                    <p><input name="username" placeholder="username" class="form-control" style="font-size: 18pt"></p>
                                </div>
                                <div class="form-sweet">
                                    <p><input type="password" name="password" placeholder="password" class="form-control" style="font-size: 18pt"></p>
                                </div>
                                <p><button type="submit" class="btn btn-default" style="font-size: 18pt">Увійти</button></p>
                            </div>
                        </div>
                    </div>
                </form>
            </body>
            </html>
            <?php
        }

        public function showAdminForm($users) {
            ?>
            <head>
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.css">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/main.css">
            </head>
            <div class="container admin-form">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 text-center lead" style="margin: 0 auto;">
                        <p><h1 style="color: white; font-size: 18pt; background: rgba(48, 48, 48, 0.74)">Адміністративна панель</h1></p>
                        <p><a style="font-size: 18pt" class="btn btn-primary" href="?action=admin-sweets">Адміністрування</a></p>
                        <p><a style="font-size: 18pt" class="btn btn-info" href="?action=logout">Вихід</a></p>
                    </div>
                </div>
            </div>
            <?php
        }
    
        public function showUserEditForm($user) { 
            ?>
            <head>
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
            </head>
            <div class="container user-edit-form">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 text-center lead">
                        <h2>Редагування користувача</h2>
                        <form method="post" action="?action=edit-user">
                            <input type="hidden" name="id" value="<?php echo $user->getId(); ?>">
                            <div class="form-group">
                                <label for="username">Ім'я користувача</label>
                                <input type="text" class="form-control" name="username" value="<?php echo $user->getUsername(); ?>">
                            </div>
                            <div class="form-group">
                                <label for="password">Пароль</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="form-group">
                                <label for="role">Роль</label>
                                    <option value="user" <?php echo ($user->getRole() === 'user') ? 'selected' : ''; ?>>Користувач</option>
                                    <option value="admin" <?php echo ($user->getRole() === 'admin') ? 'selected' : ''; ?>>Адміністратор</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Зберегти" style="font-size: 18pt">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
    }
?>