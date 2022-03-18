<?php
// логин пользователя
function getUser($domain, $ldaprdn, $ldappass, $userDn, $siteroot)
{
	if (isset($_POST['submit']) and isset($_POST['login']) and isset($_POST['password'])) {
		$ldapConn = ldap_connect($domain);

		if ($ldapConn) {

			    ldap_set_option($ldapConn, LDAP_OPT_NETWORK_TIMEOUT, 5);
			    ldap_set_option($ldapConn, LDAP_OPT_TIMELIMIT, 5);
			    ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
			    ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);

	    		$ldapbind = ldap_bind($ldapConn, $ldaprdn, $ldappass);

	    		if ($ldapbind) {

					$mail = $_POST['login'];
					if (strpos($mail, '@') === false) {
						$mail = $mail . '@goodcity.com.ru';
					}
					$userPassword = $_POST['password'];
	        		$filter = "mail=$mail"; 

	        		$ldapSearch = ldap_search($ldapConn, $userDn, $filter); 
	       		 	$results  = ldap_get_entries($ldapConn, $ldapSearch);

	       		 	//    		 	for ($i=0; $i < $results['count']; $i++) {
	    // 				print_r($results[$i]['memberof']);
	    // 				var_dump(in_array('CN=callcenter,CN=Users,DC=good,DC=city', $results[$i]['memberof']));
	    // 				echo "\n\n";    
					// }
	        
	        		if ($results [0]['dn'] != '') {
	           				if (ldap_bind($ldapConn, $results [0]['dn'], $userPassword)) {
	           					$_SESSION['auth'] = true;
								$_SESSION['username'] = stristr($mail, '@', true);	
								for ($i=0; $i < $results['count']; $i++) {
									if (in_array('CN=callcenter,CN=Users,DC=good,DC=city', $results[$i]['memberof'])) {
										$_SESSION['superuser'] = true;
									} 
								}
	                			header("Location: $siteroot");
								die();
	            			} else {
	                			echo "<h3>Неверный пароль</h3>";
	            			}
	       			} else {
	            		echo "<h3>Почта или логин введены неверно</h3>";
	        		}
	    		} else {
	        		echo "<h3>Нет подключения к серверу</h3>";
	    		}
		}
	}
}
 	
// ****************************************************

// формирование tr для звонков с ожиданием и разговором
function createCallsTr($name, $target, $allCurrentCalls, $allCalls, $operatorCalls){
	echo '<tr>';
	echo "<td class=\"d-flex justify-content-center align-items-center p-0\">$name <button type=\"button\" class=\"btn btn-secondary m-2\" data-toggle=\"modal\" data-target=\"$target\">Подробнее</button></td>
			<td>";
	echo $allCurrentCalls; 
	if($_GET['operator'] != 'all') {
		echo '/';
		echo $operatorCalls;
	}
	echo '</td>
		<td>';
	if ($allCalls != 0) {
			echo round(($allCurrentCalls*100)/$allCalls, 2);
		} else {
			echo '0';
		}
	if($_GET['operator'] != 'all'){
		echo '/';
		if ($allCurrentCalls != 0) {
			echo round(($operatorCalls*100)/$allCurrentCalls, 2);
		} else {
			echo '0';
		}
	}
	echo '</td>
		</tr>';						
}
// ****************************************************

// выбрать всех операторов
function allOperators(){
	global $db;
	$query = "SELECT DISTINCT username from callscontent WHERE username != '' AND username != 'Дмитрий Таратута' AND username !='Рита Калпахчева'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $operators[] = $row;
        }
    return $operators;
    } 
}
// ****************************************************

// выбрать все входящие звонки за период
function allIncomingCalls($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND direction = 1";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************


// выбрать уникальные входящие звонки за период
function uniqueIncomingCalls($date_start, $date_end){
	global $db;
	$query = "SELECT count(DISTINCT callerid) from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND direction = 1";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(DISTINCT callerid)'];
	}
	return $res;
}
// ****************************************************

// выбрать количество уникальных недозвонившихся за период
function uniqueIncomingCallsNo($date_start, $date_end){
	global $db;
	$query = "SELECT count(DISTINCT callerid) from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND direction = 1 AND commcount = 1";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(DISTINCT callerid)'];
	}
	return $res;
}
// ****************************************************

// выбрать всех недозвонившихся за период
function uniqueIncomingCallsNoModal($date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND direction = 1 AND commcount = 1
		ORDER BY timestart ASC, callerid";
	
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    }
}
// ****************************************************

// выбрать недозвонившегося за данное время
function uniqueRecallCallsNoModal($callerid, $timestop){
	global $db;
	$query = "SELECT * FROM callscontent";
	$query = $query . " WHERE timestart > '".$timestop."' AND ((direction = 1 AND commcount = 2) OR (direction = 2 AND commcount = 1)) AND callerid = '".$callerid."' 
	ORDER BY timestart ASC LIMIT 1";
	
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    return $row;
    } 
}
// ****************************************************

//  время ожидания ответа до 10 сек
function waitIncoming10($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE com0timestart >= '".$date_start."' AND com0timestart <= '".$date_end."' AND com0totalsec < '10' AND direction = 1 AND commcount = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  время ожидания ответа до 10 сек с оператором
function waitIncoming10Operator($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND com0timestart >= '".$date_start."' AND com0timestart <= '".$date_end."' AND com0totalsec < '10' AND direction = 1 AND commcount = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  телефоны, где время ожидания ответа до 10 сек
function waitIncoming10Modal($date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND com0totalsec < '10' AND direction = 1 AND commcount = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } 
}
// ****************************************************

//  телефоны, где время ожидания ответа до 10 сек с оператором
function waitIncoming10OperatorModal($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND com0totalsec < '10' AND direction = 1 AND commcount = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } 
}
// ****************************************************

//  время ожидания ответа между 10 и 30 сек
function waitIncoming1030($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE com0timestart >= '".$date_start."' AND com0timestart <= '".$date_end."' AND com0totalsec >= '10' AND com0totalsec < '30' AND direction = 1 AND commcount = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  время ожидания ответа между 10 и 30 сек с оператором
function waitIncoming1030Operator($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND com0timestart >= '".$date_start."' AND com0timestart <= '".$date_end."' AND com0totalsec >= '10' AND com0totalsec < '30' AND direction = 1 AND commcount = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  телефоны, где время ожидания ответа между 10 и 30 сек
function waitIncoming1030Modal($date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND com0totalsec >= '10' AND com0totalsec < '30' AND direction = 1 AND commcount = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    }
}
// ****************************************************

//  телефоны, где время ожидания ответа между 10 и 30 сек с оператором
function waitIncoming1030OperatorModal($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND com0totalsec >= '10' AND com0totalsec < '30' AND direction = 1 AND commcount = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    }
}
// ****************************************************

//   время ожидания больше 30 сек
function waitIncoming30($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE com0timestart >= '".$date_start."' AND com0timestart <= '".$date_end."' AND com0totalsec >= '30' AND direction = 1 AND commcount = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  время ожидания больше 30 сек с оператором
function waitIncoming30Operator($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND com0timestart >= '".$date_start."' AND com0timestart <= '".$date_end."' AND com0totalsec >= '30' AND direction = 1 AND commcount = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  телефоны, где время ожидания ответа между более 30 сек
function waitIncoming30Modal($date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND com0totalsec >= '30' AND direction = 1 AND commcount = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } 
}
// ****************************************************

//  телефоны, где время ожидания ответа между более 30 сек с оператором
function waitIncoming30OperatorModal($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND com0totalsec >= '30' AND direction = 1 AND commcount = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } 
}
// ****************************************************

// выбрать все звонки за период + исходящие
function allIncomingOutcoming($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec > 0) OR (direction = 2 AND com0totalsec > 0))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

// выбрать все звонки за период + исходящие для оператора
function allIncomingOutcomingOperator($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec > 0) OR (direction = 2 AND com0totalsec > 0))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

// выбрать все звонки за период + исходящие с телефонами
function allIncomingOutcomingModal($date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec > 0) OR (direction = 2 AND com0totalsec > 0))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    }
}
// ****************************************************

// выбрать все звонки за период + исходящие с телефонами по оператору
function allIncomingOutcomingOperatorModal($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec > 0) OR (direction = 2 AND com0totalsec > 0))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    }
}
// ****************************************************

//  длительность разговора до 60 сек
function callIncomingOutcoming60($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec > 0 AND com1totalsec < '60') OR (direction = 2 AND com0totalsec > 0 AND com0totalsec < '60'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  длительность разговора до 60 сек с оператором
function callIncomingOutcoming60Operator($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec > 0 AND com1totalsec < '60') OR (direction = 2 AND com0totalsec > 0 AND com0totalsec < '60'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  телефоны, где длительность разговора до 60 сек
function callIncomingOutcoming60Modal($date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec > 0 AND com1totalsec < '60') OR (direction = 2 AND com0totalsec > 0 AND com0totalsec < '60'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } 
}
// ****************************************************

//  телефоны, где длительность разговора до 60 сек с оператором
function callIncomingOutcoming60OperatorModal($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec > 0 AND com1totalsec < '60') OR (direction = 2 AND com0totalsec > 0 AND com0totalsec < '60'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } 
}
// ****************************************************

//  длительность разговора между 60 и 120 сек
function callIncomingOutcoming60120($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec >= '60' AND com1totalsec < '120') OR (direction = 2 AND com0totalsec >= '60' AND com0totalsec < '120'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  длительность разговора между 60 и 120 сек с оператором
function callIncomingOutcoming60120Operator($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec >= '60' AND com1totalsec < '120') OR (direction = 2 AND com0totalsec >= '60' AND com0totalsec < '120'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  телефоны, где длительность разговора между 60 и 120 сек
function callIncomingOutcoming60120Modal($date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec >= '60' AND com1totalsec < '120') OR (direction = 2 AND com0totalsec >= '60' AND com0totalsec < '120'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } 
}
// ****************************************************

//  телефоны, где длительность разговора между 60 и 120 сек по оператору
function callIncomingOutcoming60120OperatorModal($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec >= '60' AND com1totalsec < '120') OR (direction = 2 AND com0totalsec >= '60' AND com0totalsec < '120'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } 
}
// ****************************************************

//  длительность разговора между 120 и 180 сек
function callIncomingOutcoming120180($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec >= '120' AND com1totalsec < '180') OR (direction = 2 AND com0totalsec >= '120' AND com0totalsec < '180'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  длительность разговора между 120 и 180 сек оператор
function callIncomingOutcoming120180Operator($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec >= '120' AND com1totalsec < '180') OR (direction = 2 AND com0totalsec >= '120' AND com0totalsec < '180'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  телефоны, где длительность разговора между 120 и 180 сек
function callIncomingOutcoming120180Modal($date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec >= '120' AND com1totalsec < '180') OR (direction = 2 AND com0totalsec >= '120' AND com0totalsec < '180'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } 
}
// ****************************************************

//  телефоны, где длительность разговора между 120 и 180 сек по оператору
function callIncomingOutcoming120180OperatorModal($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec >= '120' AND com1totalsec < '180') OR (direction = 2 AND com0totalsec >= '120' AND com0totalsec < '180'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } 
}
// ****************************************************

//  длительность разговора дольше 180 сек
function callIncomingOutcoming180($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec >= '180') OR (direction = 2 AND com0totalsec >= '180'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  длительность разговора дольше 180 сек оператор
function callIncomingOutcoming180Operator($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec >= '180') OR (direction = 2 AND com0totalsec >= '180'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  телефоны, где длительность разговора более 180 сек
function callIncomingOutcoming180Modal($date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec >= '180') OR (direction = 2 AND com0totalsec >= '180'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    }
}
// ****************************************************

//  телефоны, где длительность разговора более 180 сек с оператором
function callIncomingOutcoming180OperatorModal($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND ((direction = 1 AND com1totalsec >= '180') OR (direction = 2 AND com0totalsec >= '180'))";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    }
}
// ****************************************************

// выбрать количество всех исходящих звонков за период
function allOutcoming($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND direction = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

// выбрать количество всех исходящих звонков за период по оператору
function allOutcomingOperator($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND direction = 2";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

// выбрать количество успешных исходящих звонков за период
function allOutcomingSuccess($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND direction = 2 AND com0totalsec > 0";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

// выбрать количество успешных исходящих звонков за период по оператору
function allOutcomingSuccessOperator($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND direction = 2 AND com0totalsec > 0";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  телефоны, c успешными исходящими звонками за период
function allOutcomingSuccessModal($date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND direction = 2 AND com0totalsec > 0";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } 
}
// ****************************************************

//  телефоны, c успешными исходящими звонками за период по оператору
function allOutcomingSuccessOperatorModal($operator, $date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE username = '".$operator."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND direction = 2 AND com0totalsec > 0";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } 
}
// ****************************************************

// выбрать количество успешных исходящих звонков за период
function allOutcomingFailed($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND direction = 2 AND com0totalsec = 0";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  телефоны, c неотвеченными исходящими звонками за период
function allOutcomingFailedModal($date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND direction = 2 AND com0totalsec = 0";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } 
}
// ****************************************************

// выбрать все действия по номеру за период
function actionNumberCalls($phone_number, $date_start, $date_end){
	global $db;
	$query = "SELECT * from callscontent";
	$query = $query . " WHERE callerid = '".$phone_number."' AND timestart >= '".$date_start."' AND timestart <= '".$date_end."'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    }
}
// ****************************************************












/*
	СТАРЫЕ ЗАПРОСЫ
*/

// выбрать уникальные звонки за период
function uniqueCalls($date_start, $date_end){
	global $db;
	$query = "SELECT count(DISTINCT callerid) from calls";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(DISTINCT callerid)'];
	}
	return $res;
}
// ****************************************************

// выбрать все звонки за период
function allCalls($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from calls";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

// выбрать количество уникальных недозвонившихся за период
function uniqueCallsNo($date_start, $date_end){
	global $db;
	$query = "SELECT count(DISTINCT callerid) from calls";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND status <> 'answered'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(DISTINCT callerid)'];
	}
	return $res;
}
// ****************************************************

// выбрать всех недозвонившихся за период
function uniqueCallsNoModal($date_start, $date_end){
	global $db;
	$query = "SELECT * from calls";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' 
	ORDER BY timestart ASC, callerid";
	
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } else {
        echo "Нет данных для отображения";
    }
}
// ****************************************************

//  время ожидания ответа до 10 сек
function wait10($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from calls";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND timestop-timestart < '10'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  время ожидания ответа между 10 и 30 сек
function wait1030($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from calls";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND timestop-timestart >= '10' AND timestop-timestart < '30'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  телефоны, где время ожидания ответа между 10 и 30 сек
function wait1030Modal($date_start, $date_end){
	global $db;
	$query = "SELECT * from calls";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND timestop-timestart >= '10' AND timestop-timestart < '30'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } else {
        echo "Нет данных для отображения";
    }
}
// ****************************************************

//   время ожидания больше 30 сек
function wait30($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from calls";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND timestop-timestart >= '30'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//   телефоны, где время ожидания больше 30 сек
function wait30Modal($date_start, $date_end){
	global $db;
	$query = "SELECT * from calls";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND timestop-timestart >= '30'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } else {
        echo "Нет данных для отображения";
    }
}
// ****************************************************

// выбрать все звонки за период + исходящие
function all($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callcontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  длительность разговора до 60 сек
function call60($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callcontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND timestop-timestart < '60'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  длительность разговора между 60 и 120 сек
function call60120($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callcontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND timestop-timestart >= '60' AND timestop-timestart < '120'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  длительность разговора между 120 и 180 сек
function call120180($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callcontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND timestop-timestart >= '120' AND timestop-timestart < '180'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

//  длительность разговора дольше 180 сек
function call180($date_start, $date_end){
	global $db;
	$query = "SELECT count(callerid) from callcontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND timestop-timestart >= '180'";
	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	foreach($result as $call) {
		$res = $call['count(callerid)'];
	}
	return $res;
}
// ****************************************************

// выбрать все действия по номеру за период
function actionNumber($phone_number, $date_start, $date_end){
	global $db;
	$query = "SELECT * from calls";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND callerid = '".$phone_number."' 
	ORDER BY timestart ASC";

	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } else {
        echo "Нет данных для отображения";
    }
}
// ****************************************************

// выбрать все разговоры по номеру за период
function callNumber($phone_number, $date_start, $date_end){
	global $db;
	$query = "SELECT * from callcontent";
	$query = $query . " WHERE timestart >= '".$date_start."' AND timestart <= '".$date_end."' AND callerid = '".$phone_number."' 
	ORDER BY timestart ASC, callerid";

	$result = mysqli_query($db, $query) or die("Ошибка " . mysqli_error($db));
	if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $calls[] = $row;
        }
    return $calls;
    } else {
        echo "Нет данных для отображения";
    }
}
// ****************************************************



?>