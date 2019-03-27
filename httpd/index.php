<?php
include('server.php');
	if (isset($_GET['useredit'])) {
		$id = $_GET['useredit'];
		$update = true;
		$record = mysqli_query($db, "SELECT * FROM users WHERE id=$id");

		if (count($record) == 1 ) {
			$n = mysqli_fetch_array($record);
			$username = $n['username'];
			$password = $n['password'];
			$date = $n['date'];
			$dir = $n['dir'];
		}

	}
?>
<!DOCTYPE html>
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" lang="ru-RU">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>VSFTPd</title>

	<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"></script>
	<script src="https://v4-alpha.getbootstrap.com/dist/js/bootstrap.min.js"></script>
	<link href="https://v4-alpha.getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
	<link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="style.css">

<script type="text/javascript">
$(document).ready(function(){
    $("#myModal").modal('show');
});

/* only english*/
$(function(){
    $("#from").keypress(function(event){
        var ew = event.which;
        if(ew == 32)
            return true;
        if(48 <= ew && ew <= 57)
            return true;
        if(65 <= ew && ew <= 90)
            return true;
        if(97 <= ew && ew <= 122)
            return true;
        return false;
    });
});
</script>

</head>
<body>
<div class="container" align="center">
<h4>Управление пользователями сервера SRV-FTP-01</h4>
</div>
<div class="container">
<script>
setTimeout("q();",10);
function q()
{
document.getElementById('to').value = "/home/ftp/"+document.getElementById('from').value;
setTimeout("q();",10);
}

//$(document).ready(function(){
//    $('input[name=radio]:radio').on('change', function() {
//            $('#from').val( $(this).val() );
//    });
//});

//$(function() {
//function change () {
//    this.value.indexOf(this.defaultValue) && (this.value = this.defaultValue);
//}
//$(".no_chanche").on("input", change);
//});

</script>
	<?php if (isset($_SESSION['message'])): ?>

<!-- The Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">
        <?php
		echo $_SESSION['message_header'];
		unset($_SESSION['message_header']);
	?>
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

			<?php
				echo $_SESSION['message']; 
				unset($_SESSION['message']);
			?>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <?php
		echo $_SESSION['message_footer'];
		unset($_SESSION['message_footer']);
	?>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
      </div>

    </div>
  </div>
</div>

	<?php endif ?>

<?php $results = mysqli_query($db, "SELECT * FROM users ORDER BY id DESC"); ?>

<table class="table table-striped">
	<thead>
		<tr>
			<th><!--<i class="fa fa-user"></i> -->Логин</th>
			<!--<th>Хэш пароля</th>-->
			<th><!--<i class="fa fa-envelope-o"></i> -->E-mail</th>
			<!--<th>Тип</th>-->
			<th><!--<i class="fa fa-refresh"></i> -->Обновлено</th>
			<th><!--<i class="fa fa-remove"></i> -->Удаление</th>
			<th><!--<i class="fa fa-folder-open-o"></i> -->Каталог</th>
			<th>Размер</th>
			<th colspan="2"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal1"><i class="fas fa-user-plus"></i> Добавить</button>&nbsp;&nbsp;
			<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal2"><i class="fas fa-info-circle"></i> Инструкция</button></th>
		</tr>
	</thead>
	<tbody>
<?php while ($row = mysqli_fetch_array($results)) { ?>
		<tr>
			<td><strong><?php echo $row['username']; ?></strong></td>
			<!--<td><?php echo $row['password']; ?></td>-->
			<td><?php echo $row['email']; ?></td>
			<!--<td><?php switch( $row['temp'] ) {
			case 0: echo "<i class=\"fas fa-lock\"></i>"; break;
			case 1: echo "<i class=\"fas fa-unlock\"></i>"; break;
			} ?></td>-->
			<td><?php echo $row['date']; ?></td>
			<td><?php switch( $row['temp'] ) {
			case 0: echo ""; break;
			case 1: echo "<font color=\"#990000\"><strong>" . $row['date_end'] . "</strong></font>"; break;
			} ?></td>
			<td><?php echo $row['dir']; ?></td>
			<td><?php $directory = get_dir_size($row['dir']); echo HumanSize($directory); ?></td>
			<td><a href="server.php?edituser=<?php echo $row['id']; ?>" class="btn btn-warning"><i class="far fa-edit"></i> Редактировать</a></td>
			<td><a href="server.php?del=<?php echo $row['id']; ?>" class="btn btn-danger" ><i class="far fa-trash-alt"></i> Удалить</a></td>
		</tr>
<?php } ?>
	</tbody>
</table>
<div class="container" align="center">
<h4>Ресурсы сервера:</h4>
</div>
<?php
//$bytes = disk_free_space(".");
//$si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
//$base = 1024;
//$class = min((int)log($bytes , $base) , count($si_prefix) - 1);
//echo $bytes . '<br />';
//echo '<div align=right>Свободно места на сервере: ';
//echo sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class] . '<br />';
//echo '</div>';
$dir_total = disk_total_space("/home/ftp");
$dir_free = disk_free_space("/home/ftp");
$progress = round(100 - $dir_free*100/$dir_total, 2);

//echo "<div align=right>disk total space: " . HumanSize($dir_total) . "</div>\r\n";
//echo "<div align=right>disk free space: " . HumanSize($dir_free) . "</div>\r\n";

//echo $progress;

?>

<div class="container">
    <div class="progress">
	<div class="progress-bar bg-success progress-bar-striped" style="width:<?php echo $progress; ?>%">
		<font color="#000000"><?php echo $progress; ?>%</font>
	</div>
    </div>
</div>
<div align="right">Размер диска: <?php echo HumanSize($dir_total); ?></div>
<div align="right">Свободно: <?php echo HumanSize($dir_free);?></div>

<script>
function generate() {

function makeid() {
    var text = "";
    var possible = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz123456789";

    for (var i = 0; i < 8; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}
document.getElementById("pass_gen").value = makeid();
document.getElementById("pass_gen2").value = makeid();
}
</script>

<form method="post" action="server.php" >

<!-- The Modal -->
<div class="modal fade" id="myModal1">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Добавить пользователя</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

	<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
	<label class="hand radio-inline">
		<input type="radio" name="temp" value="1" checked> Временный
	</label>
	&nbsp;&nbsp;
	<label class="hand radio-inline">
	    <input type="radio" name="temp" value="0"> Постоянный
	</label>
	<div class="form-group">
		<label>* Имя пользователя:</label>
		<input class="form-control" type="text" name="username" id="from" value="<?php echo $row['username']; ?>" maxlength="12" required autofocus>
	</div>
	<div class="form-group">
		<label>Пароль:</label>
		<div class="input-group mb-3">
		<input class="dis form-control" id="pass_gen" type="text" name="password" value="<?php echo rand_string(8);?>" readonly>
		    <div class="input-group-append">
			<button class="btn btn-outline-secondary" type="button" onclick="generate()"><i class="fas fa-sync"></i></button>
		    </div>
		</div>
	</div>
	<div class="form-group">
		<label>Ваш E-mail:</label>
		<input class="form-control" type="text" name="email" value="<?php echo $row['email']; ?>">
		<small>на него будут высланы: логин, пароль, инструкция по подключению</small>
	</div>
	<input type="hidden" name="date" value="<?php echo date('Y-m-d H:i:s'); ?>">
	<input type="hidden" name="date_end" value="<?php echo date('Y-m-d', strtotime("+1 month")); ?>">
	<div class="form-group">
		<label>Каталог</label>
		<input class="dis form-control" type="text" name="dir" id="to" value="<?php echo $row['dir']; ?>" readonly>
	</div>
	<input type="hidden" name="local_ip" value="<?php echo $_SERVER['REMOTE_ADDR'];?>">

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
	<button class="btn btn-success" type="submit" name="save">Добавить</button>
	<button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
      </div>

    </div>
  </div>
</div>

</form>



<!-- The Modal -->
<div class="modal fade" id="myModal2">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Инструкция</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
    1. Как добавить нового пользователя?
</button></p>
<div class="collapse" id="collapse1">
    <ul>
	<li>Нажмите синюю кнопку "Добавить"</li>
	<li>Выбирите тип учетной записи: "Временная" (удалится через 30 дней) или "Постоянная"</li>
	<li>Введите имя пользователя (не более 12 символов, только цифры и латинские буквы)</li>
	<li>Введите ваш e-mail, на него будут отправлены данные учетной записи. Так же на него будут приходить уведомления при окончании срока действия учетной записи, либо удалении</li>
	<li>Нажмите зеленую кнопку "Добавить"</li>
    </ul>
<hr></div>

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
    2. Как удалить пользователя?
</button></p>
<div class="collapse" id="collapse2">
    <ul>
	<li>Нажмите красную кнопку "Удалить" напротив той учетной записи, которую вы хотите удалить</li>
    </ul>
<hr></div>

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
    3. Изменить пароль пользователя?
</button></p>
<div class="collapse" id="collapse3">
    <ul>
	<li>Нажмите желтую кнопку "Редактировать" напротив той учетной записи, чей пароль вы хотите сменить</li>
	<li>Новый пароль генерируется автоматически</li>
	<li>Так же вы можите сменить тип учетной записи и изменить e-mail, куда будут отправлять все уведомления</li>
	<li>Нажмите зеленую кнопку "Обновить"</li>
    </ul>
<hr></div>

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
    4. Как изменить тип записи с временной на постоянную?
</button></p>
<div class="collapse" id="collapse4">
    <ul>
	<li>Нажмите желтую кнопку "Редактировать" напротив той учетной записи, чей тип вы хотите изменить</li>
	<li>Новый пароль генерируется автоматически</li>
	<li>Выбирите тип учетной записи</li>
	<li>Нажмите зеленую кнопку "Обновить"</li>
    </ul>
<hr></div>

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
    5. Как изменить e-mail для уведомлений?
</button></p>
<div class="collapse" id="collapse5">
    <ul>
	<li>Нажмите желтую кнопку "Редактировать" напротив той учетной записи, e-mail для уведомлений которой вы хотите изменить</li>
	<li>Новый пароль генерируется автоматически</li>
	<li>Введите новый e-mail</li>
	<li>Нажмите зеленую кнопку "Обновить"</li>
    </ul>
<hr></div>

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
    6. Сколько дней хранится временная учетная запись?
</button></p>
<div class="collapse" id="collapse6">
    <ul>
	<li>Срок хранения "Временной" учетной записи: 30 дней</li>
	<li>Дата удаления отображается в web-интерфейсе</li>
    </ul>
<hr></div>

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
    7. Как продлить срок действия временной учетной записи?
</button></p>
<div class="collapse" id="collapse7">
    <ul>
	<li>Нажмите желтую кнопку "Редактировать" напротив той учетной записи, срок действия которой вы хотите продлить</li>
	<li>Новый пароль генерируется автоматически</li>
	<li>Нажмите зеленую кнопку "Обновить"</li>
	<li>Учетная запись будет храниться 30 дней с момента последнего обновления</li>
	<li>Дата удаления отображается в web-интерфейсе</li>
    </ul>
<hr></div>

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
    8. Как восстановить пароль от учетной записи?
</button></p>
<div class="collapse" id="collapse8">
    <ul>
	<li>Пароль от учетной записи хранится в зашифрованном виде, и закодирован алгоритмом md5. Напрямую из базы его не достать</li>
	<li>Если вы указывали свой e-mail при добавлении/обновлении учетной записи, все данные регистрации были высланы на него</li>
	<li>Все уведомления при добавлении/обновлении пользователя так же отправляются на e-mail системного администратора, пароль можно спросить у него</li>
	<li>Обновить пароль</li>
    </ul>
<hr></div>

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse9" aria-expanded="false" aria-controls="collapse9">
    9. Как подключиться к ftp-серверу?
</button></p>
<div class="collapse" id="collapse9">
    <ul>
	<li>IP-адрес сервера в локальной сети: 192.168.1.19</li>
	<li>Внешний IP-адрес сервера: 77.246.234.125</li>
	<li>Использовать web-браузер, в строке "адресс" ввести: ftp://192.168.1.19 - для локальной сети, либо ftp://77.246.234.125</li>
	<li>Использовать ftp-клиент (FileZilla, FreeCommander, DoubleCommander и т.д.)</li>
	<li>Имя соединения: любое удобное вам название</li>
	<li>Хост / Host: 192.168.1.19 - для локальной сети, либо 77.246.234.125</li>
	<li>Порт / Port: 21</li>
	<li>Имя пользователя и Пароль: от учетной записи</li>
    </ul>
<hr></div>

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse10" aria-expanded="false" aria-controls="collapse10">
    10. Как загружать данные на ftp-сервер?
</button></p>
<div class="collapse" id="collapse10">
    Anim pariatur cliche reprehenderit
<hr></div>

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse11" aria-expanded="false" aria-controls="collapse11">
    11. Как скачать данные с ftp-сервера?
</button></p>
<div class="collapse" id="collapse11">
    Anim pariatur cliche reprehenderit
<hr></div>

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse12" aria-expanded="false" aria-controls="collapse12">
    12. Как подключиться к ftp-серверу из дома?
</button></p>
<div class="collapse" id="collapse12">
    Anim pariatur cliche reprehenderit
<hr></div>

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse13" aria-expanded="false" aria-controls="collapse13">
    13. Какие данные дать заказчикам, для подключения?
</button></p>
<div class="collapse" id="collapse13">
    Anim pariatur cliche reprehenderit
<hr></div>

<p><button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapse14" aria-expanded="false" aria-controls="collapse14">
    14. Как подключиться к ftp-серверу заказчикам?
</button></p>
<div class="collapse" id="collapse14">
    Anim pariatur cliche reprehenderit
<hr></div>

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
      </div>

    </div>
  </div>
</div>

</div>

</body>
</html>
