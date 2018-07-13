<?php
$orderBy = array('name', 'E-mail', 'text');

$order = 'type';
if (isset($_GET['orderBy']) && in_array($_GET['orderBy'], $orderBy)) {
    $order = $_GET['orderBy'];
}

$query = 'SELECT * FROM aTable ORDER BY '.$order;

if (isset($_POST["Save"])) {
	$newrows = "<tr><th></th></tr>";
	$newrows .=  "<!--  Add here  -->";
	$file = 'index.php'; // расположение файла
	$FileSourse = file_get_contents($file); // весь html код файла
	$FileSourse = str_replace('<!--  Add here  -->', $newrows, $FileSourse); // Добавляете новые строки в код посредством замены <!--  Add here  --> на новые сгенерированые строки.
	file_put_contents($file, $FileSourse);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Список задач</title>
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
            	<input type="submit" name="save" value="Save">
                <input type="submit" name="look" value="Look">
        	</div>
    	</form>
	<?}else{?>
	<form name="myform" action="<?=$_SERVER["PHP_SELF"]?>" method="post">
		<table border="1">
    		<tr>
        		<th>
            	<a href="?orderBy=name">Имя пользователя</a>
       			</th>
        		<th>
           		<a href="?orderBy=E-mail">E-mail</a>
        		</th>
        		<th>
            	<a href="?orderBy=text">Текст задачи</a>
        		</th>
        		<th>
            	Картинка
        		</th>
    		</tr>
<!--  Add here  -->
		</table>
		<p><input type="submit" name="Add" value="Добавить"></p>
	</form>
	<?}?>
</body>
</html>

