<?php

/**
 * класс проверяет валидность загружаемой картинки
 */
class Pic
{
    
    public function Check()
    {
        if (isset($_POST['Send'])) {
            print_r($_FILES);
            $blacklist = ['.php', '.phtml', '.php3', '.php4', '.html', '.htm'];
            foreach ($blacklist as $item) {
            if (preg_match("/$item$/", $_FILES['file']['name'])) exit('Расширение файла не подходит!');
            }
        
            $type = getimagesize($_FILES['file']['tmp_name']);
            if ($type && ($type['mime'] != 'image/png' || 
                $type['mime'] != 'image/jpg' || $type['mime'] != 'image/jpeg')) {
            if ($_FILES['file']['size'] < 320 * 240) {
                $upload = 'images/'.$_FILES['file']['name'];
            if (move_uploaded_file($_FILES['file']['tmp_name'], $upload)) echo 'Файл успешно загружен!';
                else echo 'Ошибка при загрузке файла';
            }
            else exit('Размер файла превышен!');
        }
        else exit('Тип файла не подходит');
        }
    }

}
?>