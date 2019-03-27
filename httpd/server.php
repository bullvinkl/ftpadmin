<?php 
	session_start();
	$db = mysqli_connect('localhost', 'vsftpd_user', 'passwordftp', 'vsftpd');

	// initialize variables
	$username = "";
	$password = "";
	$date = "";
	$dir = "";
	$id = 0;
	$update = false;


function HumanSize($Bytes)
{
    $Type=array("", "K", "M", "G");
    $Index=0;
    while($Bytes>=1024)
    {
	$Bytes/=1024;
	$Index++;
    }
    return("".round($Bytes, 2)." ".$Type[$Index]."b");
}

function get_dir_size($directory) {
    $size = 0;
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file) {
	$size += $file->getSize();
    }
    return $size;
}

/* добавить пользователя */
	if (isset($_POST['save'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		$date = $_POST['date'];
		$date_end = $_POST['date_end'];
		$temp = $_POST['temp'];
//		$dir = "/home/ftp/$username";
		$dir = $_POST['dir'];
		$local_ip = $_POST['local_ip'];
//		if ($temp == 0){ $temp_value = "Постоянный"; }
//		else if($temp == 1){ $temp_value = "Временный"; }
		if ($temp == 0){
		    $temp_value = "";
		    $temp_value_2 = "Тип пользователя: <strong>Постоянный</strong>";
		}
		else if($temp == 1){
		    $temp_value = "Дата удаления: <font color=\"#990000\"><strong>$date_end</strong></font>";
		    $temp_value_2 = "Тип пользователя: <strong>Временный</strong>";
		}
		/*проверка, еслть ли такой пользователь*/
		$results = mysqli_query($db, "SELECT id FROM users WHERE username='$username'");
		$row = mysqli_fetch_array($results);
		if (!empty($row['id'])) {
			$_SESSION['message_header'] = "Ошибка при добавлении";
			$_SESSION['message'] = "Пользователь <strong>$username</strong> уже есть в базе, попробуйте ввести другое имя пользователя.";
			$_SESSION['message_footer'] = "";
		}
else {
		mysqli_query($db, "INSERT INTO users (username, password, email, date, date_end, temp, dir, local_ip) VALUES ('$username', md5('$password'), '$email', '$date', '$date_end', '$temp', '$dir', '$local_ip')");
		$_SESSION['message_header'] = "Пользователь добавлен";
		$_SESSION['message'] = "Имя пользователя: <strong>$username</strong><br>
		Пароль: <strong>$password</strong><br>
		$temp_value_2<br>
		Дата добавления: <strong>$date</strong><br>
		$temp_value<br>
		Данные отправлены на: <strong>$email</strong>";
		$_SESSION['message_footer'] = "";
//		mkdir("/home/ftp/$username",0755);
		mkdir("$dir",0777);

/* send mail */
if ($temp == 0){
    $temp_mail = "";
    $temp_mail_2 = "Постоянный";
}
else if($temp == 1){
    $temp_mail = "Дата удаления: $date_end";
    $temp_mail_2 = "Временный";
}
$subject = 'srv-ftp-01: ' . $_SESSION['message_header'];
$comment = '' . $_SESSION['message_header'] . "\r\n" .
	'Имя пользователя: ' . "$username \r\n" .
	'Пароль: ' . "$password \r\n" .
	'Тип пользователя: ' . "$temp_mail_2 \r\n" .
	'Дата добавления: ' . "$date \r\n" .
	'' . "$temp_mail \r\n" .
	'IP: ' . "$local_ip";
$headers = 'From: noreply@digimap.ru' . "\r\n" .
    'Reply-To: noreply@digimap.ru' . "\r\n" .
    'Bcc: m.makarov@digimap.ru' . "\r\n" .
    'Content-Type: text/plain;charset=UTF-8' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($email, $subject, $comment, $headers);

}
		header('location: index.php');
	}

/* Обновлеие */
	if (isset($_POST['update'])) {
		$id = $_POST['id'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		$date = $_POST['date'];
		$date_end = $_POST['date_end'];
		$temp = $_POST['temp'];
//		$dir = "/home/ftp/$username";
		$dir = $_POST['dir'];
		$local_ip = $_POST['local_ip'];
//		if ($temp == 0){ $temp_value = "Постоянный"; }
//		else if($temp == 1){ $temp_value = "Временный"; }
		if ($temp == 0){
		    $temp_value = "";
		    $temp_value_2 = "Тип пользователя: <strong>Постоянный</strong>";
		}
		else if($temp == 1){
		    $temp_value = "Дата удаления: <font color=\"#990000\"><strong>$date_end</strong></font>";
		    $temp_value_2 = "Тип пользователя: <strong>Временный</strong>";
		}
		mysqli_query($db, "UPDATE users SET password=md5('$password'), email='$email', date='$date', date_end='$date_end', temp='$temp', local_ip='$local_ip' WHERE id=$id");
		$_SESSION['message_header'] = "Пользователь обновлен";
		$_SESSION['message'] = "Имя пользователя: <strong>$username</strong><br>
		Пароль: <strong>$password</strong><br>
		$temp_value_2<br>
		Дата добавления: <strong>$date</strong><br>
		$temp_value<br>
		Данные отправлены на: <strong>$email</strong>";
		$_SESSION['message_footer'] = "";

/* send mail */
if ($temp == 0){
    $temp_mail = "";
    $temp_mail_2 = "Постоянный";
}
else if($temp == 1){
    $temp_mail = "Дата удаления: $date_end";
    $temp_mail_2 = "Временный";
}
$subject = 'srv-ftp-01: ' . $_SESSION['message_header'];
$comment = '' . $_SESSION['message_header'] . "\r\n" .
	'Имя пользователя: ' . "$username \r\n" .
	'Пароль: ' . "$password \r\n" .
	'Тип пользователя: ' . "$temp_mail_2 \r\n" .
	'Дата обновления: ' . "$date \r\n" .
	'' . "$temp_mail \r\n" .
	'IP: ' . "$local_ip";
$headers = 'From: noreply@digimap.ru' . "\r\n" .
    'Reply-To: noreply@digimap.ru' . "\r\n" .
    'Bcc: m.makarov@digimap.ru' . "\r\n" .
    'Content-Type: text/plain;charset=UTF-8' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($email, $subject, $comment, $headers);

		header('location: index.php');
	}

/* удаление */
if (isset($_GET['del'])) {
	$id = $_GET['del'];
	$results = mysqli_query($db, "SELECT * FROM users WHERE id=$id");
	$row = mysqli_fetch_array($results);
	$username = $row['username'];
	$email = $row['email'];
//	$username = $_GET['username'];
	$dir = $row['dir'];
	$local_ip = $_SERVER['REMOTE_ADDR'];
	$date = date('Y-m-d H:i:s');
	mysqli_query($db, "DELETE FROM users WHERE id=$id");
	$_SESSION['message_header'] = "Пользователь удален";
	$_SESSION['message'] = "Имя пользователя: <strong>$username</strong><br>
	Дата удаления: <strong>$date</strong>";
	$_SESSION['message_footer'] = "";
//	rmdir("/home/ftp/$username");
//	rmdir("$dir");

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
            return true;
    }

    if (!is_dir($dir)) {
            return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}
deleteDirectory($dir);

/* send mail */
$subject = 'srv-ftp-01: ' . $_SESSION['message_header'];
$comment = '' . $_SESSION['message_header'] . "\r\n" .
	'Имя пользователя: ' . "$username \r\n" .
	'Дата удаления: ' . "$date \r\n" .
	'IP: ' . "$local_ip";
$headers = 'From: noreply@digimap.ru' . "\r\n" .
    'Reply-To: noreply@digimap.ru' . "\r\n" .
    'Bcc: m.makarov@digimap.ru' . "\r\n" .
    'Content-Type: text/plain;charset=UTF-8' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($email, $subject, $comment, $headers);

	header('location: index.php');
}

/* не задействовано */
if (isset($_GET['add'])) {
	$id = $_GET['edit'];
	$_SESSION['message'] = "";
	header('location: index.php');
}

function rand_string( $length ) {
    $chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ123456789";
    return substr(str_shuffle($chars),0,$length);
}

/* не задействовано */
	if (isset($_GET['edit'])) {
		$id = $_GET['edit'];
		$results = mysqli_query($db, "SELECT * FROM users WHERE id=$id");
		$row = mysqli_fetch_array($results);
		$username = $row['username'];
		$password = rand_string(8);
		$email = $row['email'];
		$date = date('Y-m-d H:i:s');
		$date_end = date('Y-m-d', strtotime("+1 month"));
//		$dir = "/home/ftp/$username";
//		$dir = $_POST['dir'];
		if ($row['temp'] == 0){
		    $temp_value = "";
		    $temp_value_2 = "Тип пользователя: <strong>Постоянный</strong>";
		}
		else if($row['temp'] == 1){
		    $temp_value = "Дата удаления: <font color=\"#990000\"><strong>$date_end</strong></font>";
		    $temp_value_2 = "Тип пользователя: <strong>Временный</strong>";
		}
		mysqli_query($db, "UPDATE users SET password=md5('$password'), date='$date', date_end='$date_end' WHERE id=$id");
		$_SESSION['message_header'] = "Пользователь обновлен";
		$_SESSION['message'] = "Имя пользователя: <strong>$username</strong><br>
		Пароль: <strong>$password</strong><br>
		$temp_value_2<br>
		Дата обновления: <strong>$date</strong><br>
		$temp_value";
		$_SESSION['message_footer'] = "";
/* send mail */
$subject = 'srv-ftp-01: ' . $_SESSION['message_header'];
$comment = 'Имя пользователя: ' . "$username \r\n" .
	'Пароль: ' . "$password \r\n" .
	'Дата обновления: ' . "$date";
$headers = 'From: noreply@digimap.ru' . "\r\n" .
    'Reply-To: m.makarov@digimap.ru' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($email, $subject, $comment, $headers);

		header('location: index.php');
	}

/* вывод формы редактирования пользователя */
	if (isset($_GET['edituser'])) {
		$id = $_GET['edituser'];
		$results = mysqli_query($db, "SELECT * FROM users WHERE id=$id");
		$row = mysqli_fetch_array($results);
		if ($row['temp'] == 1){
		    $temp_value_1 = "checked";
		    $temp_value_2 = "";
		}
		else if ($row['temp'] == 0){
		    $temp_value_1 = "";
		    $temp_value_2 = "checked";
		}
		$username = $row['username'];
		$password = rand_string(8);
		$email = $row['email'];
		$date = date('Y-m-d H:i:s');
		$date_end = date('Y-m-d', strtotime("+1 month"));
		$dir = $row['dir'];
		$local_ip = $_SERVER['REMOTE_ADDR'];
		$_SESSION['message_header'] = "Редактировать пользователя";
		$_SESSION['message'] = "<form method=\"post\" action=\"server.php\">
		<input type=\"hidden\" name=\"id\" value=\"$id\">
		<label class=\"hand radio-inline\"><input type=\"radio\" name=\"temp\" value=\"1\" $temp_value_1> Временный</label>&nbsp;&nbsp;
		<label class=\"hand radio-inline\"><input type=\"radio\" name=\"temp\" value=\"0\" $temp_value_2> Постоянный</label>
		<div class=\"form-group\">
		<label>Имя пользователя:</label>
		<input class=\"dis form-control\" type=\"text\" name=\"username\" value=\"$username\" maxlength=\"8\" readonly>
		</div>
		<div class=\"form-group\">
		<label>* Пароль:</label>
		<div class=\"input-group mb-3\">
		    <input class=\"dis form-control\" type=\"text\" id=\"pass_gen2\" name=\"password\" value=\"$password\" maxlength=\"12\" readonly>
		    <div class=\"input-group-append\">
			<button class=\"btn btn-outline-secondary\" type=\"button\" onClick=\"generate();\"><i class=\"fas fa-sync\"></i></button>
		    </div>
		</div>
		</div>
		<div class=\"form-group\">
		<label>Ваш E-mail:</label>
		<input class=\"form-control\" type=\"text\" name=\"email\" value=\"$email\">
		<small>на него будут высланы: логин, пароль, инструкция по подключению</small>
		</div>
		<input type=\"hidden\" name=\"date\" value=\"$date\">
		<input type=\"hidden\" name=\"date_end\" value=\"$date_end\">
		<div class=\"form-group\">
		<label>Каталог</label>
		<input class=\"dis form-control\" type=\"text\" name=\"dir\" value=\"$dir\" readonly>
		</div>
		<input type=\"hidden\" name=\"local_ip\" value=\"$local_ip\">";
		$_SESSION['message_footer'] = "<button class=\"btn btn-success\" type=\"submit\" name=\"update\">Обновить</button>
		</form>";
		header('location: index.php');
}

//if (isset($_GET['edit'])) {
//	$id = $_GET['edit'];
//	$_SESSION['message'] = "edit";
//	header('location: index.php');
//}

	$results = mysqli_query($db, "SELECT * FROM users");

?>
