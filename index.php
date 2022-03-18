<?php 
	require_once 'config.php'; 
	if (!empty($_SESSION['auth'])) {
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<script type="text/javascript" src="js/jquery.js"></script>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="<?=$cssroot?>">
	<title>Oktell</title>
</head>
<body>
	<div id="content"></div>
	
	<script>

		function show()
		{
			$.ajax({
				url: "list.php",
				cache: false,
				success: function(html){
					$("#content").html(html);
				}
			});
		}
	
		$(document).ready(function(){
			show();
			setInterval('show()',5000); //время обновления в мс
		});
	</script>

	<?php
	
		if(isset($_POST['idname'])) { 
			
			foreach($_POST['idname'] as $delid){
				$query = "DELETE FROM missed_list WHERE id ='$delid'";	     
				$result2 = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db)); 	    
			}  
   		}
   	} else { 
        header("Location: $loginroot");
        die();
    }	
	?>
</body>
</html>