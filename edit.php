<?php
	include('configdb.php');
	$alternatif = $_POST['alternatif']; 
	$k1 = $_POST['k1'];
	$k2 = $_POST['k2']; 
	$k3 = $_POST['k3'];
	$k4 = $_POST['k4'];
	$k5 = $_POST['k5'];
	$k6 = $_POST['k6'];
	$k7 = $_POST['k7'];
	$k8 = $_POST['k8'];
	$k9 = $_POST['k9'];
	$k10 = $_POST['k10'];
	$k11 = $_POST['k11'];
	
	$result = $mysqli->query("UPDATE alternatif SET `alternatif` = '".$alternatif."', 
	`k1` = '".$k1."',`k2` = '".$k2."',`k3` = '".$k3."',`k4` = '".$k4."',`k5` = '".$k5."',`k6` = '".$k6."',`k7` = '".$k7."',`k8` = '".$k8."',`k9` = '".$k9."',`k10` = '".$k10."',`k11` = '".$k11."' WHERE `id_alternatif` = ".$_GET['id'].";");
	if(!$result){
		echo $mysqli->connect_errno." - ".$mysqli->connect_error;
		exit();
	}
	else{
		header('Location: alternatif.php');
	}
?>