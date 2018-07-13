<?php
    require_once 'class/TableRows.php';
    require_once 'class/pic.php';
    //соединимся с БД
    /*define('DB_HOST', 'mysql.zzz.com.ua');
    define('DB_USER', 'fillip');
    define('DB_PASSWORD', 'Dded633');
    define('DB_NAME', 'fillip');*/
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'tas');
    try {$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
    
    } catch (Exception $e) {
    exit('Ошибка соединения с БД');
    }
    $pdo = null;

    $orderBy = array('name', 'email', 'sum');
    $order = 'name';

if (isset($_POST['log_in'])) {
    try {$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
    
    } catch (Exception $e) {
    exit('Ошибка соединения с БД');
    }
    $login = $_POST['login'];
    $password = $_POST['password'];
    //шифрование

    //hash_equals($hashed_password, crypt($password, $hashed_password))

    //$password = password_hash($password, PASSWORD_DEFAULT);

    $query = "SELECT * FROM `users` WHERE `login`='$login'"; 
    $query = $pdo->prepare($query);
    $query->execute([$login]);
    $row = $query->fetch();  

    if (password_verify($password, $row['password']))
        {
        echo "Добро пожаловать, $login";
        } else{echo 'Не верный логин и/или пароль';
        }
}

if (isset($_POST['Save'])) {
    try {$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
    
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //$pic = new Pic:::Check();

    $query = "INSERT INTO `tasks` VALUES('', '$name', '$email', '$sum', '$file', '')";
    $res = $pdo->exec($query);
    //echo "$res";

        } catch (Exception $e) {
    echo $query . "<br>" . $e->getMessage();
    }
    $pdo = null;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Список задач</title>
      <meta charset="utf-8">
  <style>
    th {
      cursor: pointer;
    }

    th:hover {
      background: yellow;
    }
  </style>
</head>
<body>
	<!--<table border="1">
		<tr>
			<th>Имя пользователя</th>
			<th>E-mail</th>
			<th>Текст задачи</th>
			<th>Картинка</th>
		</tr>
	</table>-->
	<? if (isset($_POST["Add"])) {?>
		<form name="formAdd" action="<?=$_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data">
            <div>Имя пользователя:
            	<input type="text" name="name" value="">
        	</div>
        	<div>E-mail:
            	<input type="text" name="mail" value="">
        	</div>
        	<div>Текст задачи:
            	<textarea name="sum"></textarea>
        	</div>
            <p>Картинка:
                <input type="file" name="file">
            </p>
        	<div>
            	<input type="submit" name="Save" value="Save">
                <input type="submit" name="Look" value="Look">
        	</div>
    	</form>
	<?}elseif (isset($_POST["Log"])){?>
        <form name="log_form" action="<?=$_SERVER["PHP_SELF"]?>" method="post">
            <p>
                <p><stpong>Ваш логин</stpong>:</p>
                <input type="text" name="login" value="">
            </p>    
            <p>
                <p><stpong>Ваш пароль</stpong>:</p>
                <input type="password" name="password" value="">
            </p>
            <p>
                <button type="submit" name="log_in">Войти</button>
            </p>
        </form>
    <?}else{?>
	<form name="myform" action="<?=$_SERVER["PHP_SELF"]?>" method="post">
		<div>
            <button type="submit" name="Log">Войти</button>
        </div>

        <table id="grid" border="1">
    		<tr>
        		<th>
            	<a href="?orderBy=name">Имя пользователя</a>
       			</th>
        		<th>
           		<a href="?orderBy=email">E-mail</a>
        		</th>
        		<th>
            	<a href="?orderBy=sum">Текст задачи</a>
        		</th>
        		<th>
            	Картинка
        		</th>
    		</tr>

            <?php 

                try {
                $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                //выполним сортировку
                if (isset($_GET['orderBy']) && in_array($_GET['orderBy'], $orderBy)) {

                $order = $_GET['orderBy'];
                $query = 'SELECT `name`, `email`, `sum`, `file` FROM tasks ORDER BY '.$order;}
                //echo "$query";
                $res = $pdo->prepare($query);

                $res->execute();

                $result = $res->setFetchMode(PDO::FETCH_ASSOC);
                foreach(new TableRows(new RecursiveArrayIterator($res->fetchAll())) as $k=>$v) {
                echo $v;
                }
                }
                catch(PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                $pdo = null;
            ?>
            
            <!--  Add here  -->
		</table>

<!--   <script>
    // сортировка таблицы без использования БД и соответственно без запроса

    var grid = document.getElementById('grid');

    grid.onclick = function(e) {
      if (e.target.tagName != 'TH') return;

      // Если TH -- сортируем
      sortGrid(e.target.cellIndex, e.target.getAttribute('data-type'));
    };

    function sortGrid(colNum, type) {
      var tbody = grid.getElementsByTagName('tbody')[0];

      // Составить массив из TR
      var rowsArray = [].slice.call(tbody.rows);

      // определить функцию сравнения, в зависимости от типа
      var compare;

      switch (type) {
        case 'number':
          compare = function(rowA, rowB) {
            return rowA.cells[colNum].innerHTML - rowB.cells[colNum].innerHTML;
          };
          break;
        case 'string':
          compare = function(rowA, rowB) {
            return rowA.cells[colNum].innerHTML > rowB.cells[colNum].innerHTML;
          };
          break;
      }

      // сортировать
      rowsArray.sort(compare);

      // Убрать tbody из большого DOM документа для лучшей производительности
      grid.removeChild(tbody);

      // добавить результат в нужном порядке в TBODY
      // они автоматически будут убраны со старых мест и вставлены в правильном порядке
      for (var i = 0; i < rowsArray.length; i++) {
        tbody.appendChild(rowsArray[i]);
      }

      grid.appendChild(tbody);

    }
  </script> -->

		<p><input type="submit" name="Add" value="Добавить"></p>
	</form>
	<?}?>
</body>
</html>
<?php
if (isset($_POST["Save"])) {
    
    $name = (isset($_POST["name"])) ? $_POST["name"] : '';
    $email = (isset($_POST["mail"])) ? $_POST["mail"] : '';
    $sum = (isset($_POST["sum"])) ? $_POST["sum"] : '';
    $file = (isset($_POST["file"])) ? $_POST["file"] : '';
    //без использования БД
    /*$newrows = "<tr><th>$name</th><th>$mail</th><th>$sum</th><th>$file</th></tr><!--  Add here  -->";
    $file = 'index.php'; // расположение файла
    $FileSourse = file_get_contents($file); // весь html код файла
    $FileSourse = str_replace('<!--  Add here  -->', $newrows, $FileSourse); // Добавляете новые строки в код посредством замены
    file_put_contents($file, $FileSourse);*/
    try {$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "INSERT INTO `tasks` VALUES('', '$name', '$email', '$sum', '$file', '')";
    $res = $pdo->exec($query);
    //echo "$res";

        } catch (Exception $e) {
    echo $query . "<br>" . $e->getMessage();
    }
    $pdo = null;
}
?>