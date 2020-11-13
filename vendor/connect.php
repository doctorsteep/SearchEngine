<?php
	$db_host = 'localhost'; // хост баззы данных - например, localhost
	$db_user = 'root'; // пользователь от базы данных - например, root
	$db_password = '1234'; // пароль от зады данных - например, 1234
	$db_name = 'search'; // имя базы данных - например, doctorsteep

	$connect = mysqli_connect($db_host, $db_user, $db_password, $db_name); // соединяемся с базой данных

	if (!$connect) { // проверка соединения (ошибка соединения)
		die('Ошибка соединения с базой!');
	}
?>