<?php 
include 'config.php';
include 'functions.php'; 
if (!empty($_SESSION['auth'])) {
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/style.css">
	<title>Фильтр по датам</title>
</head>
<body>
	<div class="d-flex justify-content-center align-items-center">
		<div class="counter mr-1">
        	<a href="<?=$siteroot?>/logout.php">Выйти из программы</a>
    	</div>
		<div class="counter ml-1"><a href="index.php">Назад</a></div>
	</div>
	<?php 
		if (isset($_GET['day_start'])) {
			$dateStart = $_GET['day_start'];
		} else {
			$dateStart = '';
		}

		if (isset($_GET['time_start'])) {
			$timeStart = str_replace('%3A', ':', $_GET['time_start']);
		} else { 
			$timeStart = '00:00';
		} 

		if (isset($_GET['day_end'])) {
			$dateEnd = $_GET['day_end'];
		} else {
			$dateEnd = '';
		}

		if (isset($_GET['time_end'])) {
			$timeEnd = str_replace('%3A', ':', $_GET['time_end']);
		} else { 
			$timeEnd = '23:59';
		} 

		// операторы
		$operators = allOperators();

		if (isset($_GET['operator'])) {
			$operator = str_replace('+', ' ', $_GET['operator']);
		} else {
			$operator = 'all';
		}

	?>

	<form>
		<div class="d-flex justify-content-center align-items-center">
			<div class="button_block m-1">
				<div class="input_place">Дата с:<br><input name="day_start" type="date" required value="<?=$dateStart?>" /></div>
				<div class="input_place">Время с:<br><input id="time_start" name="time_start" type="time" required value="<?=$timeStart?>" /></div> 
			</div>
			<div class="button_block m-1">    
				<div class="input_place">Дата по:<br><input name="day_end" type="date" required value="<?=$dateEnd?>" /></div>
				<div class="input_place">Время по:<br><input id="time_end" name="time_end" type="time" required value="<?=$timeEnd?>" /></div>        
			</div>
			<div class="button_block m-1"> 
				<div class="input_place select_place">
					<select name="operator">
						<option value="all">Все операторы</option>
						<?php foreach($operators as $operator) { ?>
							<option value="<?=$operator['username']?>"><?=$operator['username']?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		<div class="counter"><button class="button" type="submit" name="submit" value="1">Сформировать отчет</button></div>   

		<div class="container">
			<div class="row justify-content-md-center">
				<div class="col col-lg-5">
					<div class="input-group m-3">
						<input type="text" class="form-control" placeholder="Номер телефона в формате 071......." aria-label="Recipient's username" aria-describedby="basic-addon2" name="phone_number">
						<div class="input-group-append">
							<button class="btn btn-outline-success font-weight-bold" type="submit" name="about_number" value="1">Проверить номер</button>
						</div>
					</div>
				</div>
			</div>
		</div>

	</form>
	<?php 
	// нажат ли submit
	if(!isset($_GET['submit']) && !isset($_GET['about_number'])){
		
	 ?>
		<div class="counter"><p>Сформируйте отчет</p></div> 
	<?php } else {

		$date_start = $_GET['day_start'] . ' ' . $_GET['time_start'] . ':00';
		$date_end = $_GET['day_end'] . ' ' . $_GET['time_end'] . ':59';

		$new_date_start = strftime("%d.%m.%Y", strtotime($_GET['day_start']));
		$new_date_end = strftime("%d.%m.%Y", strtotime($_GET['day_end']));
		$operator = $_GET['operator'];

		if($_GET['submit'] == 1) {
			// инфо по входящим
			$allIncomingCalls = allIncomingCalls($date_start, $date_end);
			$uniqueIncomingCalls = uniqueIncomingCalls($date_start, $date_end);
			$uniqueIncomingCallsNo = uniqueIncomingCallsNo($date_start, $date_end);
			$uniqueIncomingCallsNoModal = uniqueIncomingCallsNoModal($date_start, $date_end);

			// входящий до 10
			$waitIncoming10 = waitIncoming10($date_start, $date_end);
			$waitIncoming10Operator = waitIncoming10Operator($operator, $date_start, $date_end);
			$waitIncoming10Modal = waitIncoming10Modal($date_start, $date_end);
			$waitIncoming10OperatorModal = waitIncoming10OperatorModal($operator, $date_start, $date_end);

			// входящий 10-30
			$waitIncoming1030 = waitIncoming1030($date_start, $date_end);
			$waitIncoming1030Operator = waitIncoming1030Operator($operator, $date_start, $date_end);
			$waitIncoming1030Modal = waitIncoming1030Modal($date_start, $date_end);
			$waitIncoming1030OperatorModal = waitIncoming1030OperatorModal($operator, $date_start, $date_end);

			// входящий более 30
			$waitIncoming30 = waitIncoming30($date_start, $date_end);
			$waitIncoming30Operator = waitIncoming30Operator($operator, $date_start, $date_end);
			$waitIncoming30Modal = waitIncoming30Modal($date_start, $date_end);
			$waitIncoming30OperatorModal = waitIncoming30OperatorModal($operator, $date_start, $date_end);

			// исходящие
			$allIncomingOutcoming = allIncomingOutcoming($date_start, $date_end);
			$allIncomingOutcomingOperator = allIncomingOutcomingOperator($operator, $date_start, $date_end);
			$allIncomingOutcomingModal = allIncomingOutcomingModal($date_start, $date_end);
			$allIncomingOutcomingOperatorModal = allIncomingOutcomingOperatorModal($operator, $date_start, $date_end);

			// исходящие 60
			$callIncomingOutcoming60 = callIncomingOutcoming60($date_start, $date_end);
			$callIncomingOutcoming60Operator = callIncomingOutcoming60Operator($operator, $date_start, $date_end);
			$callIncomingOutcoming60Modal = callIncomingOutcoming60Modal($date_start, $date_end);
			$callIncomingOutcoming60OperatorModal = callIncomingOutcoming60OperatorModal($operator, $date_start, $date_end);

			// исходящие 60-120
			$callIncomingOutcoming60120 = callIncomingOutcoming60120($date_start, $date_end);
			$callIncomingOutcoming60120Operator = callIncomingOutcoming60120Operator($operator, $date_start, $date_end);
			$callIncomingOutcoming60120Modal = callIncomingOutcoming60120Modal($date_start, $date_end);
			$callIncomingOutcoming60120OperatorModal = callIncomingOutcoming60120OperatorModal($operator, $date_start, $date_end);

			// исходящие 120-180
			$callIncomingOutcoming120180 = callIncomingOutcoming120180($date_start, $date_end);
			$callIncomingOutcoming120180Operator = callIncomingOutcoming120180Operator($operator, $date_start, $date_end);
			$callIncomingOutcoming120180Modal = callIncomingOutcoming120180Modal($date_start, $date_end);
			$callIncomingOutcoming120180OperatorModal = callIncomingOutcoming120180OperatorModal($operator, $date_start, $date_end);

			// исходящие 180
			$callIncomingOutcoming180 = callIncomingOutcoming180($date_start, $date_end);
			$callIncomingOutcoming180Operator = callIncomingOutcoming180Operator($operator, $date_start, $date_end);
			$callIncomingOutcoming180Modal = callIncomingOutcoming180Modal($date_start, $date_end);
			$callIncomingOutcoming180OperatorModal = callIncomingOutcoming180OperatorModal($operator, $date_start, $date_end);

			// инфо по исходящим
			$allOutcoming = allOutcoming($date_start, $date_end);
			$allOutcomingOperator = allOutcomingOperator($operator, $date_start, $date_end);
			$allOutcomingSuccess = allOutcomingSuccess($date_start, $date_end);
			$allOutcomingSuccessOperator = allOutcomingSuccessOperator($operator, $date_start, $date_end);
			$allOutcomingSuccessModal = allOutcomingSuccessModal($date_start, $date_end);
			$allOutcomingSuccessOperatorModal = allOutcomingSuccessOperatorModal($operator, $date_start, $date_end);
			$allOutcomingFailed = allOutcomingFailed($date_start, $date_end);
			$allOutcomingFailedModal = allOutcomingFailedModal($date_start, $date_end);
	?>

			<div class="counter">Количественная оценка с <?=$new_date_start?> <?=$_GET['time_start']?> по <?=$new_date_end?> <?=$_GET['time_end']?>. Сформировано в <?=$today = date("d.m.Y H:i:s")?>
			<?php 
				if ($_GET['operator'] != 'all') {
					echo 'Оператор: <u>' . $operator . '</u>';
				}
			?>
				
			</div>

			<table class="table">
				<thead>
					<tr>
						<th>Все входящие звонки</th>
						<th><?=$allIncomingCalls?></th>
						<th>%</th>
					</tr>
				</thead>
				<tr>
					<td class="m-1">Уникальные входящие</td>
					<td><?=$uniqueIncomingCalls?></td>
					<td>
						<?php if($allIncomingCalls != '0') {
							echo round(($uniqueIncomingCalls*100)/$allIncomingCalls, 2);
						} else {
							echo '0';
						} ?>
					</td>
				</tr>
				<tr>
					<td class="d-flex justify-content-center align-items-center p-0">Уникальные недозвонившиеся <button type="button" class="btn btn-secondary m-2" data-toggle="modal" data-target=".modal-nocall">Статистика всех звонков</button></td>
					<td><?=$uniqueIncomingCallsNo?></td>
					<td>
						<?php if($allIncomingCalls != '0') {
							echo round(($uniqueIncomingCallsNo*100)/$allIncomingCalls, 2);
						} else {
							echo '0';
						} ?>	
					</td>
				</tr>

				<!-- ОТВЕТ ДО 10 СЕК -->
				<?php 
					createCallsTr('Время ожидания ответа до 10 сек', '.modal-10', $waitIncoming10, $allIncomingCalls,$waitIncoming10Operator);
				?> 
				<!-- ОТВЕТ ОТ 10 ДО 30 СЕК -->
				<?php 
					createCallsTr('Время ожидания ответа между 10 и 30 сек', '.modal-10-30', $waitIncoming1030, $allIncomingCalls,$waitIncoming1030Operator);
				?> 
				<!-- ОТВЕТ БОЛЕЕ 30 СЕК -->
				<?php 
					createCallsTr('Время ожидания больше 30 сек', '.modal-30', $waitIncoming30, $allIncomingCalls,$waitIncoming30Operator);
				?> 

				<!-- ВХОДЯЩИЕ И ИСХОДЯЩИЕ -->
				<tr id="green">
				<td class="d-flex justify-content-center align-items-center p-0">Успешные входящие и исходящие звонки <button type="button" class="btn btn-secondary m-2" data-toggle="modal" data-target=".modal-incomingOutcomingSuccess">Подробнее о звонках</button></td>
					<td>
						<?php
							echo $allIncomingOutcoming;
							if($_GET['operator'] != 'all') {
								echo '/';
								echo $allIncomingOutcomingOperator;
							}
						?>
					</td>
					<td>%</td>
				</tr>

				<!-- РАЗГОВОР ДО 60 СЕК -->
				<?php 
					createCallsTr('Длительность разговора до 60 сек (включая исходящие)', '.modal-60', $callIncomingOutcoming60, $allIncomingOutcoming,$callIncomingOutcoming60Operator);
				?> 

				<!-- РАЗГОВОР 60-120 СЕК -->
				<?php 
					createCallsTr('Длительность разговора между 60 и 120 сек (включая исходящие)', '.modal-60-120', $callIncomingOutcoming60120, $allIncomingOutcoming,$callIncomingOutcoming60120Operator);
				?> 

				<!-- РАЗГОВОР 120-180 СЕК -->
				<?php 
					createCallsTr('Длительность разговора между 120 и 180 сек (включая исходящие)', '.modal-120-180', $callIncomingOutcoming120180, $allIncomingOutcoming,$callIncomingOutcoming120180Operator);
				?> 

				<!-- РАЗГОВОР 180 СЕК -->
				<?php 
					createCallsTr('Длительность разговора более 180 сек (включая исходящие)', '.modal-180', $callIncomingOutcoming180, $allIncomingOutcoming,$callIncomingOutcoming180Operator);
				?> 

				<!-- ВХОДЯЩИЕ -->
				<tr id="green2">
					<td m-1>Общее количество исходящих звонков (успешные + неуспешные)</td>
					<td>
						<?php
							echo $allOutcoming;
							if($_GET['operator'] != 'all') {
								echo '/';
								echo $allOutcomingOperator;
							}
						?>
					</td>
					<td>%</td>
				</tr>

				<!-- УСПЕШНЫЕ ИСХОДЯЩИЕ -->
				<?php 
					createCallsTr('Количество успешных исходящих звонков', '.modal-allOutcomingSuccess', $allOutcomingSuccess, $allOutcoming, $allOutcomingSuccessOperator);
				?> 

				<tr>
					<td class="d-flex justify-content-center align-items-center p-0">Недозвон оператора <button type="button" class="btn btn-secondary m-2" data-toggle="modal" data-target=".modal-allOutcomingFailed">Подробнее</button></td>
					<td><?=$allOutcomingFailed?></td>
					<td>
						<?php if($allOutcoming != '0') {
							echo round(($allOutcomingFailed*100)/$allOutcoming, 2);
						} else {
							echo '0';
						} ?>
					</td>
				</tr>
			</table>
			
			<?php 
			include "modal-windowNew.php";
		} 
		// *****************************
		// ABOUT NUMBER INFO
		if($_GET['about_number'] == 1) { 
			$phone_number = $_GET['phone_number'];
			$actionNumberCalls = actionNumberCalls($phone_number, $date_start, $date_end);

			?>
			<div class="counter">Статистика телефонного номера <?=$phone_number?> c <?=$new_date_start?> <?=$_GET['time_start']?> по <?=$new_date_end?> <?=$_GET['time_end']?>. Сформировано в <?=$today = date("d.m.Y H:i:s")?></div>



			<div class="counter">Звонки</div>
			<table class="table">
				<thead>
					<tr>
						<th>№</th>
						<th>Звонок</th>
						<th>Телефон</th>
						<th>Время начала</th>
						<th>Время ответа</th>
						<th>Длительность разговора</th>
						<th>Оператор</th>
					</tr>
				</thead>

				<?php $numCallsNumber = 1; ?>
				<?php if($actionNumberCalls): ?>
					<?php foreach($actionNumberCalls as $call): ?>
					<tr>
						<td class="table_column"><span><?=$numCallsNumber?></span></td>
						<td class="table_column">
						<?php 
							if($call['direction'] == 1) { ?>
		                        <span class="badge badge-warning">Входящий</span>
		                    <?php } elseif($call['direction'] == 2) { ?>
		                    	<span class="badge badge-info">Исходящий</span>
		                    <?php } ?>
						</td>
                        <td class="table_column"><span><?=$call['callerid']?></span></td>
                        <td class="table_column"><span><?=date("d.m.Y H:i:s", strtotime($call['timestart']));?></span></td>
                        <td class="table_column">
                        	<?php 
	                        	if ($call['direction'] == 1) {
	                        		echo "<span>{$call['com0totalsec']}</span>";
	                        	} elseif($call['direction'] == 2){
	                        		echo '<span class="badge badge-info">Исходящий</span>';
	                        	} 
                        	?>
                        </td>
                        <td class="table_column">
                        <?php
                        	if($call['direction'] == 1) { ?>
		                       <span><?=$call['com1totalsec']?></span>
		                    <?php } elseif($call['direction'] == 2) { ?>
		                    	 <span><?=$call['com0totalsec']?></span>
		                    <?php } ?></td>
                        <td class="table_column"><span><?=$call['username']?></span></td>
                    </tr>
                    <?php ++$numCallsNumber; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		<?php } 
	} 
	} else { 
		header("Location: $loginroot");
		die();
	}
	?> 
<script type="text/javascript" src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>