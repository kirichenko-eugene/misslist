<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<script type="text/javascript" src="js/jquery.js"></script>
	<title>Oktell</title>
</head>
<body>

	<?php
	require_once 'config.php'; 
	if (!empty($_SESSION['auth'])) {
		$result = mysqli_query($db, ("SELECT id, date_format(date,'%d.%m.%Y %H:%i:%s') AS date, callerid, state FROM missed_list ORDER BY id ASC"))  or die("Ошибка " . mysqli_error($db));
		$all = mysqli_num_rows($result);

    // Таблица с результатами
		echo "<div class=\"counter\">Текущий пользователь: {$_SESSION['username']}</div>";
		echo '<div class="d-flex justify-content-center align-items-center">';
		echo "<div class=\"counter mr-1\">
		<a href=\"$siteroot"."logout.php\">Выйти из программы</a>
		</div>";
		echo "<div class=\"counter ml-1\"><a class=\"\" href=\"filterNew.php\">Статистика звонков</a></div>";
		echo '</div>';
		echo "<div class=\"counter\">Всего звонков: $all</div>";

		echo "<form action=\"index.php\" method=\"POST\">";
		if (!empty($_SESSION['superuser'])) {
			echo "<div class=\"counter\"><input class=\"button\" type=\"submit\" name=\"submit\" value=\"Удалить записи\"></div>";
		}
		echo "<table><tr><th>id</th><th>№</th><th>Дата</th><th>Телефон</th><th>Статус</th></tr>";
		$num = 1;
		while ($row = mysqli_fetch_array($result)) {
			$id = $row['id'];
			$time = $row['date'];
			$phone = $row['callerid'];
			$state = $row['state']; 
			echo "<tr>";
			echo "<td><input class=\"check\" id=\"check_$id\" type=\"checkbox\" name=\"idname[]\" value=\"$id\"></td>";
			echo "<td>$num</td>";
			echo "<td>$time</td>";
			echo "<td>$phone</td>";
			echo "<td>$state</td>";
			echo "</tr>";
			++$num;
		}
		?>
	</table>
</form>

<?php 
	// очищаем результат
mysqli_free_result($result);	
} else { 
	header("Location: $loginroot");
	die();
}
?>
<script>

	function onClickBox() {
		var arr = $('.check').map(function() {
			return this.checked;
		}).get();
		localStorage.setItem("checked", JSON.stringify(arr));
	}

	$(document).ready(function() {
		var arr = JSON.parse(localStorage.getItem('checked')) || [];
		arr.forEach(function(checked, i) {
			$('.check').eq(i).prop('checked', checked);
		});

		$(".check").click(onClickBox);
	});

</script>
</body>
</html>