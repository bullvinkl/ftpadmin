<?php
include('server.php');
$results = mysqli_query($db, "SELECT * FROM users WHERE temp = '1' ORDER BY id DESC");
echo "Временные учетные записи: <br>\r\n";
while ($row = mysqli_fetch_array($results)) {
		$id = $row['id'];
		$username = $row['username'];
		$date_end = $row['date_end'];
		$email = $row['email'];
		$dir = $row['dir'];
//		$local_ip = $_SERVER['REMOTE_ADDR'];
	echo $username . " " . $date_end . " " . date('Y-m-d H-m-s')  . "<br>\r\n";
//	sleep(5);
	if ($date_end == date('Y-m-d', strtotime("+1 day"))){
		/* send mail */
		$subject = 'srv-ftp-01: срок действия учетной записи: ' . $username;
		$comment = 'Внимание!!!' . "\r\n" .
			'Истекает срок действия учетной записи.' . "\r\n" .
			'Имя пользователя: ' . "$username \r\n" .
			'Тип учетной записи: Временная' . "\r\n" .
			'Дата удаления: ' . "$date_end \r\n" .
			'Чтобы продлить срок действия учетной записи, обновите пароль пользователя через панель управления: http://192.168.1.19/server.php?edituser=' . $id;
		$headers = 'From: noreply@example.ru' . "\r\n" .
    			'Reply-To: noreply@example.ru' . "\r\n" .
    			'Bcc: user@example.ru' . "\r\n" .
			'Content-Type: text/plain;charset=UTF-8' . "\r\n" .
    			'X-Mailer: PHP/' . phpversion();
		mail($email, $subject, $comment, $headers);
		echo "<br>send mail to: " . $email . "<br><br>\r\n";
		sleep(5);
	}
	else if ($date_end == date('Y-m-d')){
		/* del user */
		mysqli_query($db, "DELETE FROM users WHERE id=$id");
		/* del directory */
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
		$subject = 'srv-ftp-01: учетная запись удалена: ' . $username;
		$comment = 'Внимание!!!' . "\r\n" .
			'Срок действия учетной записи истек и данные были удалены.' . "\r\n" .
			'Имя пользователя: ' . "$username \r\n" .
			'Тип учетной записи: Временная' . "\r\n" .
			'Дата удаления: ' . "$date_end \r\n";
		$headers = 'From: noreply@example.ru' . "\r\n" .
    			'Reply-To: noreply@example.ru' . "\r\n" .
    			'Bcc: user@example.ru' . "\r\n" .
			'Content-Type: text/plain;charset=UTF-8' . "\r\n" .
    			'X-Mailer: PHP/' . phpversion();
		mail($email, $subject, $comment, $headers);
                echo "<br>del folder and send mail: " . $email ."<br><br>\r\n";
                sleep(5);
        }
}
//echo "<br>ip: " . $local_ip;
?>
