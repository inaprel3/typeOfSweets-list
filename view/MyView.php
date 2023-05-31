<?php 
    namespace View; 

    class MyView extends SweetListView {
        private function showSweets($sweets) {
            ?>
            <form name='sweet-form' method='get'>
                <label for="sweet">Солодощі</label>
                <select name="sweet">
                    <option value=""></option>
                    <?php
                    foreach ($sweets as $cursweet) {
                        echo "<option " . (($cursweet->getId() == $_GET['sweet']) ? "selected":"") . "value='" . $cursweet->getId() . "''>" . $cursweet->getSweetName() . "</option>";
                    }?>
                </select>
                <input type="submit" value="Перейти">
                <?php if($this->CheckRight('sweet', 'create')):?>
				    <a href="?action=create-sweet">Додати солодощі</a>
			<?php endif; ?>
		</form>
        <?php
        }

        private function showSweet(\Model\Sweet $sweet) {
            ?>
            <h3>Назва:<span class='sweet-name'>
			    <?php echo trim($sweet->getSweetName()); ?></span> 
		    </h3>
		    <h3>Кількість:<span class='sweet-quantity'>
			    <?php echo trim($sweet->getSweetQuantity()); ?></span> 
		    </h3>
		    <h3>Магазин:<span class='sweet-shop'>
			    <?php echo trim($sweet->getSweetShop()); ?></span> 
		    </h3>
		    <div class='control' style="margin-top: 3%">
		        <?php if($this->CheckRight('sweet', 'edit')):?>
			        <a href="?action=edit-sweet-form&sweet=
                        <?php echo trim($_GET['sweet']); ?>">Редагувати солодощі</a>
			    <?php endif; ?>
			    <?php if($this->CheckRight('sweet', 'delete')):?> 
			        <a href="?action=delete-sweet&sweet=
				        <?php echo $_GET['sweet']; ?>">Видалити солодощі</a>
			    <?php endif; ?>
		    </div>
            <?php
        }

        private function showTypesOfSweets($typesOfSweets) {
            ?>
            <section>
                <?php if($_GET['sweet']): ?>
                <?php if($this->CheckRight('typeOfSweets', 'create')):?> 
		            <div class='control' style="margin-top: 3%">
			            <a href="?action=create-typeOfSweets-form&sweet=
				            <?php echo trim($_GET['sweet']); ?>">Додати вид солодощів</a>
		            </div>
		        <?php endif; ?>
                <form name='typesOfSweets-filter' method='post' style="margin-top: 3%">
			        Фільтрувати за назвою <input type="text" name="typeOfSweetsTypeFilter" value='
				        <?php echo strip_tags(trim($_POST['typeOfSweetsTypeFilter']) ?? '');?>'>
			        <input type="submit" value="Фільтрувати">
		        </form>
                <table>
			        <thead>
				        <tr>Види солодощів :
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
				        <?php $row_class = 'row';
					        if ($typeOfSweets->isSizeBig) {
						        $row_class = 'big';
					        }
					        if ($typeOfSweets->isSizeSmall) { 
						        $row_class = 'small';
					        }
				        ?>
				        <tr class = '<?php echo $row_class; ?>'>
					        <td><?php echo ($key + 1); ?></td>
					        <td><?php echo trim($typeOfSweets->getTypeOfSweetsType()); ?></td> 
					        <td><?php echo trim($typeOfSweets->isSizeBig())?'великий':'маленький'; ?></td>
					        <td><?php echo date_format($typeOfSweets->getDatePublish(), 'd.m.Y'); ?></td>
					        <td>
						        <?php if($this->CheckRight('typeOfSweets', 'edit')):?> 
						        <a href='?action=edit-typeOfSweets-form&sweet=
                                    <?php echo trim($_GET['sweet']); ?>&file=<?php echo $typeOfSweets->getId();?>'>Замовити</a>
						        <?php endif; ?>
						        |
						        <?php if($this->CheckRight('typeOfSweets', 'delete')):?> 
						        <a href='?action=delete-typeOfSweets&sweet=
                                    <?php echo trim($_GET['sweet']) ?>&file=<?php echo $typeOfSweets->getId();?>'>Видалити</a>
						        <?php endif; ?>
					        </td>
				        </tr>  
				    <?php endif; ?>
				    <?php endforeach; ?>
                    <?php endif; ?>
			        </tbody>
		        </table>
                <?php endif; ?>
            </section>
            <?php
        }
        public function showMainForm($sweets, \Model\Sweet $sweet, $typesOfSweets) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
	            <title>Солодощі</title>
            </head>
            <body>
                <header>
		            <div class="user-info">
			            <span>Ласкаво просимо, <?php echo $_SESSION['user']; ?> !</span>
			            <?php if ($this->CheckRight('user','admin')): ?> 
				            <a href="?action=admin">Адміністрування</a>
				        <?php endif; ?>
			            <a href="?action=logout">Logout</a>
		            </div>
		            <?php if ($this->CheckRight('sweet', 'view')) {
                        $this->showSweets($sweets);
                        if ($_GET['sweet']) {
                            $this->showSweet($sweet);
                        }
                    }
                    ?> 
	            </header>
                <?php
                    if($this->checkRight('typeOfSweets', 'view')) {
                        $this->showTypesOfSweets($typesOfSweets);
                    }
                ?>
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
            </head>
            <body>
	            <a href="index.php?sweet=<?php echo trim($_GET['sweet']);?>">На головну</a>
	            <form name='edit-sweet' method='post' action="?action=edit-sweet&sweet=<?php echo $_GET['sweet'];?>">
		            <div>
                        <label for='sweetName'>Назва:</label><input type="text" name="sweetName" value="
				            <?php echo trim($sweet->getSweetName()); ?>">
                    </div>
		            <div>
			            <label for='sweetQuantity'>Кількість:</label><input type="text" name="sweetQuantity" value="
				            <?php echo trim($sweet->getSweetQuantity()); ?>">
		            </div>
		            <div>
			            <label for='sweetShop'>Магазин:</label><input type="text" name="sweetShop" value="
				            <?php echo trim($sweet->getSweetShop()); ?>">
		            </div>
		            <div><input type="submit" name="ok" style="margin-top: 20%; height:50px; width:150px" value="Змінити"></div>
	            </form>
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
            </head>
            <body>
                <a href="index.php?sweet=<?php echo $_GET['sweet'];?>">На головну</a>
                <form name='edit-typeOfSweets' method='post' action="?action=edit-typeOfSweets&file=
                    <?php echo $_GET['file'];?>&sweet<?php echo $_GET['sweet'];?>">
                <div>
                    <label for='typeOfSweetsType'>Тип:</label>
                    <input type="text" name="typeOfSweetsType" value="
                        <?php echo trim($typeOfSweets->getTypeOfSweetsType()); ?>">
                </div>
                <div>
                    <label for='date_publish'>Дата виготовлення:</label>
                    <input type="date" name="date_publish" value="
                        <?php echo $typeOfSweets->getTypeOfSweetsDateCooked()->format('d.m.Y'); ?>">
                </div>
                <div>
                    <label for='size'>Розмір:</label>
                    <select name="size">
                        <option disabled>Розмір</option>
                        <option <?php echo (trim($typeOfSweets->isSizeBig()))?"selected":""; ?> 
                            value="великий">великий
                        </option>
                        <option <?php echo (trim($typeOfSweets->isSizeSmall()))?"selected":""; ?> 
                            value="маленький">маленький
                        </option>
                    </select>
                </div>
                <div>
                    <input type="checkbox" <?php echo ($typeOfSweets->isOrderStatus())?"checked":""; ?> 
                        name="order" value=1>Замовлено
                </div>
                <div><input type="submit" name="ok" style="margin-top: 20%; height:50px; width:150px" value="Змінити"></div>
                <div><input type="submit" name="ok" style="margin-top: 20%; height:50px; width:150px" value="Оплатити"></div>
                </form>
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
            </head>
            <body>
	            <a href="?sweet=<?php echo $_GET['sweet'];?>">На головну</a>
	            <form name='edit-typeOfSweets' method='post' action="?action=create-typeOfSweets&sweet=<?php echo $_GET['sweet'];?>">
		        <div>
	                <label for='typeOfSweetsType'>Тип:</label>
	                <input type="text" name="typeOfSweetsType">
	            </div>
	            <div>
	                <label for='date_publish'>Дата виготовлення:</label>
	                <input type="date" name="date_publish">
	            </div>
	            <div>
	                <label for='size'>Розмір:</label>
	                <select name="size">
	                    <option disabled>Розмір</option>
	                    <option value="великий">великий</option>
	                    <option value="маленький">маленький</option>
	                    <option value="інше">інше</option>
	                </select>
	            </div>
	            <div>
	                <input type="checkbox" name="order" value=1>Замовлено
	            </div>
	            <div><input type="submit" name="ok" value="Додати"></div>
	            </form>
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
                <link rel="stylesheet" type="text/css" href="css/login-style.css">
            </head>
            <body>
                <form method="post" action="?action=checkLogin">
                    <p><input align="center" type="text" name="username" placeholder="username"></p>
                    <p><input type="password" name="password" placeholder="password"></p>
                    <p><input type="submit" value="login"></p>
                </form>
            </body>
            </html>
            <?php
        }

        public function showAdminForm($users) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Адміністрування</title>
            </head>
            <body>
            <header>
                <a href="index.php">На головну</a>
                <h1>Адміністрування користувачів</h1>
                <link rel="stylesheet" type="text/css" href="css/main-style.css">
            </header>
            <section>
                <table>
                    <thead>
                    <tr>
                        <th>Користувач</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user):?>
                            <?php if($user->getUserName() != $_SESSION['user'] && $user->getUserName() != 'admin' && trim($user->getUserName()) != '' ): ?>
                            <tr>
                                <td><a href="?action=edit-user-form&username=
                                    <?php echo $user->getUserName();?>">
                                    <?php echo $user->getUserName();?>
                                </td>
                            </tr>
                        <?php endif ?> 
                        <?php endforeach;?>
                    </tbody>
                </table>
            </section>
            </body>
            </html>
            <?php
        }

        public function showUserEditForm(\Model\User $user) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Редагування користувача</title>
                <link rel="stylesheet" type="text/css" href="admin.css">
            </head>
            <body>
                <a href="?action=admin">До списку користувачів</a>
                <form name='edit-user' method='post' action="?action=edit-user&user=
                    <?php echo $_GET['user'];?>">
                    <div class='tbl'>
                        <div>
                            <label for='user_name'>Username:</label>
                            <input readonly type="text" name="user_name" value='<?php echo $user->getUserName(); ?>'>
                        </div>
                        <div>
                            <label for='user_pwd'>Password:</label>
                            <input type="text" name="user_pwd" value='<?php echo $user->getPassword(); ?>'>
                        </div>
                    </div>
                    <div><p>Солодощі:</p>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(0))?"checked":""; ?> 
                            name="right0" value="1"><span>перегляд</span>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(1))?"checked":""; ?> 
                            name="right1" value="1"><span>створення</span>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(2))?"checked":""; ?> 
                            name="right2" value="1"><span>редагування</span>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(3))?"checked":""; ?> 
                            name="right3" value="1"><span>видалення</span>
                    </div>
                    <div><p>Вид солодощів:</p>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(4))?"checked":""; ?> 
                            name="right4" value="1"><span>перегляд</span>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(5))?"checked":""; ?> 
                            name="right5" value="1"><span>створення</span>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(6))?"checked":""; ?> 
                            name="right6" value="1"><span>редагування</span>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(7))?"checked":""; ?> 
                            name="right7" value="1"><span>видалення</span>
                    </div>
                    <div><p>Користувачі:</p>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(8))?"checked":""; ?> 
                            name="right8" value="1"><span>адміністрування</span>
                    </div>
                    <div><input type="submit" name="ok" value="Змінити"></div>
                </form>
            </body>
            </html>
            <?php
        }
    }
?>