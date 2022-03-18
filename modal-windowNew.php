<div class="modal fade modal-nocall" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="nocall">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class=" modal-header-full-width modal-header text-center">
                <h5 class="modal-title w-100" id="nocall">Уникальные недозвонившиеся</h5>
            </div>
			<?php $num = 1; ?>
			<table class="table mt-2"><thead class="table_head"><tr><th>№</th><th>Телефон</th><th>Время начала звонка</th><th>Время окончания звонка</th><th>Время на линии</th><th>Когда перезвонили</th><th>Кто перезвонил</th></tr></thead>
				<?php if($uniqueIncomingCallsNoModal): ?>
					<?php foreach($uniqueIncomingCallsNoModal as $call): ?>
						<tr>
							<td class="table_column"><span><?=$num?></span></td>

	                        <td class="table_column"><span><?=$call['callerid']?></span></td>

	                        <td class="table_column"><span><?=date("d.m.Y H:i:s", strtotime($call['timestart']));?></span></td>

	                        <td class="table_column"><span><?=date("d.m.Y H:i:s", strtotime($call['timestop']));?></span></td>

 							<td class="table_column"><span><?=$call['totalsec']?></span></td>
 		
	                       	<?php
	                       		
	                       		$findRecall = uniqueRecallCallsNoModal($call['callerid'], $call['timestop']);
	                       		$recallTime = date("d.m.Y H:i:s", strtotime($findRecall['timestart']));
	                       		$recallOperator = $findRecall['username'];

	                       		if ($findRecall == NULL) {
	                       			echo '<td class="table_column">
	                       			<span class="badge badge-warning">Нет ответа</span></td>';

	                       			echo '<td class="table_column">
	                       			<span class="badge badge-warning">Нет ответа</span></td>';
	                       		} else {
		                       		echo '<td class="table_column">';
		                       	 	echo $recallTime;
		                       	 	echo '</td>';

		                       	 	echo '<td class="table_column">';
		                       	 	echo $recallOperator;
		                       	 	echo '</td>';
	                       	 	}
	                       	 ?>                       	
	                    	
	                    </tr>
	                    <?php ++$num; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>

<div class="modal fade modal-10" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal-10">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class=" modal-header-full-width modal-header text-center">
                <h5 class="modal-title w-100" id="modal-10">Время ожидания ответа до 10 сек</h5>
            </div>
			<?php $num = 1; ?>
			<table class="table mt-2"><thead class="table_head"><tr><th>№</th><th>Телефон</th><th>Время начала</th><th>Время ответа</th><th>Длительность разговора</th><th>Оператор</th></tr></thead>
				<?php if($_GET['operator'] == 'all') {
					$waitModal = $waitIncoming10Modal;
				} else {
					$waitModal = $waitIncoming10OperatorModal;
				}?>
				<?php if ($waitModal): ?>
					<?php foreach($waitModal as $call): ?>
					<tr>
						<td class="table_column"><span><?=$num?></span></td>
                        <td class="table_column"><span><?=$call['callerid']?></span></td>
                        <td class="table_column"><span><?=date("d.m.Y H:i:s", strtotime($call['timestart']));?></span></td>
                        <td class="table_column"><span><?=$call['com0totalsec']?></span></td>
                        <td class="table_column"><span><?=$call['com1totalsec']?></span></td>
                        <td class="table_column"><span><?=$call['username']?></span></td>
                    </tr>
                    <?php ++$num; ?>
					<?php endforeach; ?>
				<?php endif ?>	
			</table>
		</div>
	</div>
</div>

<div class="modal fade modal-10-30" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal-10-30">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class=" modal-header-full-width modal-header text-center">
                <h5 class="modal-title w-100" id="modal-10-30">Время ожидания ответа между 10 и 30 сек</h5>
            </div>
			<?php $num = 1; ?>
			<table class="table mt-2"><thead class="table_head"><tr><th>№</th><th>Телефон</th><th>Время начала</th><th>Время ответа</th><th>Длительность разговора</th><th>Оператор</th></tr></thead>
				<?php if($_GET['operator'] == 'all') {
					$waitModal = $waitIncoming1030Modal;
				} else {
					$waitModal = $waitIncoming1030OperatorModal;
				}?>
				<?php if ($waitModal): ?>
					<?php foreach($waitModal as $call): ?>
					<tr>
						<td class="table_column"><span><?=$num?></span></td>
                        <td class="table_column"><span><?=$call['callerid']?></span></td>
                        <td class="table_column"><span><?=date("d.m.Y H:i:s", strtotime($call['timestart']));?></span></td>
                        <td class="table_column"><span><?=$call['com0totalsec']?></span></td>
                        <td class="table_column"><span><?=$call['com1totalsec']?></span></td>
                        <td class="table_column"><span><?=$call['username']?></span></td>
                    </tr>
                    <?php ++$num; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>

<div class="modal fade modal-30" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal-30">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class=" modal-header-full-width modal-header text-center">
                <h5 class="modal-title w-100" id="modal-30">Время ожидания больше 30 сек</h5>
            </div>
			<?php $num = 1; ?>
			<table class="table mt-2"><thead class="table_head"><tr><th>№</th><th>Телефон</th><th>Время начала</th><th>Время ответа</th><th>Длительность разговора</th><th>Оператор</th></tr></thead>
				<?php if($_GET['operator'] == 'all') {
					$waitModal = $waitIncoming30Modal;
				} else {
					$waitModal = $waitIncoming30OperatorModal;
				}?>
				<?php if ($waitModal): ?>
					<?php foreach($waitModal as $call): ?>
					<tr>
						<td class="table_column"><span><?=$num?></span></td>
                        <td class="table_column"><span><?=$call['callerid']?></span></td>
                        <td class="table_column"><span><?=date("d.m.Y H:i:s", strtotime($call['timestart']));?></span></td>
                        <td class="table_column"><span><?=$call['com0totalsec']?></span></td>
                        <td class="table_column"><span><?=$call['com1totalsec']?></span></td>
                        <td class="table_column"><span><?=$call['username']?></span></td>
                    </tr>
                    <?php ++$num; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>

<div class="modal fade modal-incomingOutcomingSuccess" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal-incomingOutcomingSuccess">
	<div class="modal-dialog modal-lg">
		<div class="modal-content modal-width">
			<div class=" modal-header-full-width modal-header text-center">
                <h5 class="modal-title w-100" id="modal-incomingOutcomingSuccess">Успешные входящие и исходящие звонки</h5>
            </div>
			<?php $num = 1; ?>
			<table class="table mt-2"><thead class="table_head"><tr><th>№</th><th>Звонок</th><th>Телефон</th><th>Время начала</th><th>Время окончания</th><th>Время ответа</th><th>Длительность разговора</th><th>Оператор</th></tr></thead>
				<?php if($_GET['operator'] == 'all') {
					$waitModal = $allIncomingOutcomingModal;
				} else {
					$waitModal = $allIncomingOutcomingOperatorModal;
				}?>
				<?php if ($waitModal): ?>
					<?php foreach($waitModal as $call): ?>
					<tr>
						<td class="table_column"><span><?=$num?></span></td>
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
                         <td class="table_column"><span><?=date("d.m.Y H:i:s", strtotime($call['timestop']));?></span></td>
                        <td class="table_column"><span>
                        	<?php if ($call['direction'] == 1){
                        		echo $call['com0totalsec'];
                        	} elseif($call['direction'] == 2) {
                        		echo $call['totalsec'] - $call['com0totalsec'];
                        	} ?>                  		
                        </span></td>
                        <td class="table_column">
                        <?php
                        	if($call['direction'] == 1) { ?>
		                       <span><?=$call['com1totalsec']?></span>
		                    <?php } elseif($call['direction'] == 2) { ?>
		                    	 <span><?=$call['com0totalsec']?></span>
		                    <?php } ?></td>
                        <td class="table_column"><span><?=$call['username']?></span></td>
                    </tr>
                    <?php ++$num; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>

<div class="modal fade modal-60" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal-60">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class=" modal-header-full-width modal-header text-center">
                <h5 class="modal-title w-100" id="modal-60">Длительность разговора до 60 сек (включая исходящие)</h5>
            </div>
			<?php $num = 1; ?>
			<table class="table mt-2"><thead class="table_head"><tr><th>№</th><th>Звонок</th><th>Телефон</th><th>Время начала</th><th>Время ответа</th><th>Длительность разговора</th><th>Оператор</th></tr></thead>
				<?php if($_GET['operator'] == 'all') {
					$waitModal = $callIncomingOutcoming60Modal;
				} else {
					$waitModal = $callIncomingOutcoming60OperatorModal;
				}?>
				<?php if ($waitModal): ?>
					<?php foreach($waitModal as $call): ?>
					<tr>
						<td class="table_column"><span><?=$num?></span></td>
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
                        <td class="table_column"><span>
                        	<?php if ($call['direction'] == 1){
                        		echo $call['com0totalsec'];
                        	} elseif($call['direction'] == 2) {
                        		echo $call['totalsec'] - $call['com0totalsec'];
                        	} ?>                  		
                        </span></td>
                        <td class="table_column">
                        <?php
                        	if($call['direction'] == 1) { ?>
		                       <span><?=$call['com1totalsec']?></span>
		                    <?php } elseif($call['direction'] == 2) { ?>
		                    	 <span><?=$call['com0totalsec']?></span>
		                    <?php } ?></td>
                        <td class="table_column"><span><?=$call['username']?></span></td>
                    </tr>
                    <?php ++$num; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>

<div class="modal fade modal-60-120" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal-60-120">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class=" modal-header-full-width modal-header text-center">
                <h5 class="modal-title w-100" id="modal-60-120">Длительность разговора между 60 и 120 сек (включая исходящие)</h5>
            </div>
			<?php $num = 1; ?>
			<table class="table mt-2"><thead class="table_head"><tr><th>№</th><th>Звонок</th><th>Телефон</th><th>Время начала</th><th>Время ответа</th><th>Длительность разговора</th><th>Оператор</th></tr></thead>
				<?php if($_GET['operator'] == 'all') {
					$waitModal = $callIncomingOutcoming60120Modal;
				} else {
					$waitModal = $callIncomingOutcoming60120OperatorModal;
				}?>
				<?php if ($waitModal): ?>
					<?php foreach($waitModal as $call): ?>
					<tr>
						<td class="table_column"><span><?=$num?></span></td>
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
                        <td class="table_column"><span>
                        	<?php if ($call['direction'] == 1){
                        		echo $call['com0totalsec'];
                        	} elseif($call['direction'] == 2) {
                        		echo $call['totalsec'] - $call['com0totalsec'];
                        	} ?>                  		
                        </span></td>
                        <td class="table_column">
                        <?php
                        	if($call['direction'] == 1) { ?>
		                       <span><?=$call['com1totalsec']?></span>
		                    <?php } elseif($call['direction'] == 2) { ?>
		                    	 <span><?=$call['com0totalsec']?></span>
		                    <?php } ?></td>
                        <td class="table_column"><span><?=$call['username']?></span></td>
                    </tr>
                    <?php ++$num; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>

<div class="modal fade modal-120-180" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal-120-180">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class=" modal-header-full-width modal-header text-center">
                <h5 class="modal-title w-100" id="modal-120-180">Длительность разговора между 120 и 180 сек (включая исходящие)</h5>
            </div>
			<?php $num = 1; ?>
			<table class="table mt-2"><thead class="table_head"><tr><th>№</th><th>Звонок</th><th>Телефон</th><th>Время начала</th><th>Время ответа</th><th>Длительность разговора</th><th>Оператор</th></tr></thead>
				<?php if($_GET['operator'] == 'all') {
					$waitModal = $callIncomingOutcoming120180Modal;
				} else {
					$waitModal = $callIncomingOutcoming120180OperatorModal;
				}?>
				<?php if ($waitModal): ?>
					<?php foreach($waitModal as $call): ?>
					<tr>
						<td class="table_column"><span><?=$num?></span></td>
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
                        <td class="table_column"><span>
                        	<?php if ($call['direction'] == 1){
                        		echo $call['com0totalsec'];
                        	} elseif($call['direction'] == 2) {
                        		echo $call['totalsec'] - $call['com0totalsec'];
                        	} ?>                  		
                        </span></td>
                        <td class="table_column">
                        <?php
                        	if($call['direction'] == 1) { ?>
		                       <span><?=$call['com1totalsec']?></span>
		                    <?php } elseif($call['direction'] == 2) { ?>
		                    	 <span><?=$call['com0totalsec']?></span>
		                    <?php } ?></td>
                        <td class="table_column"><span><?=$call['username']?></span></td>
                    </tr>
                    <?php ++$num; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>

<div class="modal fade modal-180" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal-180">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class=" modal-header-full-width modal-header text-center">
                <h5 class="modal-title w-100" id="modal-180">Длительность разговора более 180 сек (включая исходящие)</h5>
            </div>
			<?php $num = 1; ?>
			<table class="table mt-2"><thead class="table_head"><tr><th>№</th><th>Звонок</th><th>Телефон</th><th>Время начала</th><th>Время ответа</th><th>Длительность разговора</th><th>Оператор</th></tr></thead>
				<?php if($_GET['operator'] == 'all') {
					$waitModal = $callIncomingOutcoming180Modal;
				} else {
					$waitModal = $callIncomingOutcoming180OperatorModal;
				}?>
				<?php if ($waitModal): ?>
					<?php foreach($waitModal as $call): ?>
					<tr>
						<td class="table_column"><span><?=$num?></span></td>
						<td class="table_column">
						<?php 
							if($call['direction'] == 1) { ?>
		                        <span class="badge badge-warning">Входящий</span>
		                    <?php } elseif($call['direction'] == 2) { ?>
		                    	<span class="badge badge-info">Исходящий</span>
		                    <?php } ?>
						</td>
                        <td class="table_column"><span>
                        	<?php if ($call['direction'] == 1){
                        		echo $call['com0totalsec'];
                        	} elseif($call['direction'] == 2) {
                        		echo $call['totalsec'] - $call['com0totalsec'];
                        	} ?>                  		
                        </span></td>
                        <td class="table_column"><span><?=date("d.m.Y H:i:s", strtotime($call['timestart']));?></span></td>
                        <td class="table_column"><span>
                        	<?php if ($call['direction'] == 1){
                        		echo $call['com0totalsec'];
                        	} elseif($call['direction'] == 2) {
                        		echo $call['totalsec'] - $call['com0totalsec'];
                        	} ?>                  		
                        </span></td>
                        <td class="table_column">
                        <?php
                        	if($call['direction'] == 1) { ?>
		                       <span><?=$call['com1totalsec']?></span>
		                    <?php } elseif($call['direction'] == 2) { ?>
		                    	 <span><?=$call['com0totalsec']?></span>
		                    <?php } ?></td>
                        <td class="table_column"><span><?=$call['username']?></span></td>
                    </tr>
                    <?php ++$num; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>

<div class="modal fade modal-allOutcomingSuccess" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal-allOutcomingSuccess">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class=" modal-header-full-width modal-header text-center">
                <h5 class="modal-title w-100" id="modal-allOutcomingSuccess">Успешные исходящие звонки</h5>
            </div>
			<?php $num = 1; ?>
			<table class="table mt-2"><thead class="table_head"><tr><th>№</th><th>Телефон</th><th>Время начала</th><th>Время ответа</th><th>Длительность разговора</th><th>Оператор</th></tr></thead>
				<?php if($_GET['operator'] == 'all') {
					$waitModal = $allOutcomingSuccessModal;
				} else {
					$waitModal = $allOutcomingSuccessOperatorModal;
				}?>
				<?php if ($waitModal): ?>
					<?php foreach($waitModal as $call): ?>
					<tr>
						<td class="table_column"><span><?=$num?></span></td>
                        <td class="table_column"><span><?=$call['callerid']?></span></td>
                        <td class="table_column"><span><?=date("d.m.Y H:i:s", strtotime($call['timestart']));?></span></td>
                        <td class="table_column"><span><?=$call['totalsec'] - $call['com0totalsec']?></span></td>
                        <td class="table_column"><span><?=$call['com0totalsec']?></span></td>
                        <td class="table_column"><span><?=$call['username']?></span></td>
                    </tr>
                    <?php ++$num; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>

<div class="modal fade modal-allOutcomingFailed" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="modal-allOutcomingFailed">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class=" modal-header-full-width modal-header text-center">
                <h5 class="modal-title w-100" id="modal-allOutcomingFailed">Неотвеченные исходящие звонки</h5>
            </div>
			<?php $num = 1; ?>
			<table class="table mt-2"><thead class="table_head"><tr><th>№</th><th>Телефон</th><th>Время начала</th><th>Время ответа</th><th>Длительность разговора</th><th>Оператор</th></tr></thead>
				<?php if($allOutcomingFailedModal): ?>
					<?php foreach($allOutcomingFailedModal as $call): ?>
					<tr>
						<td class="table_column"><span><?=$num?></span></td>
                        <td class="table_column"><span><?=$call['callerid']?></span></td>
                        <td class="table_column"><span><?=date("d.m.Y H:i:s", strtotime($call['timestart']));?></span></td>
                        <td class="table_column"><span><?=$call['totalsec'] - $call['com0totalsec']?></span></td>
                        <td class="table_column"><span><?=$call['com0totalsec']?></span></td>
                        <td class="table_column"><span><?=$call['username']?></span><?php 
                        		if ($call['commcount'] == 0) { ?>
                        			<span class="badge badge-danger">Проблемы на линии</span>
                        		<?php } ?>
                        </td>
                    </tr>
                    <?php ++$num; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>