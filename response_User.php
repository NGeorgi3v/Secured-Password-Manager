<?php
	require_once('includes/database_config.php');
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') :
		extract($_POST);
		$masterA=encrypt($master);
		$sql = "SELECT * FROM users WHERE `user_id` = '$userID' AND `user_mPassword` = '$masterA'";
		$res = $dbh->prepare($sql);
		$res->execute();
		$counter = $res->rowCount();
		if($counter == 1)
		{
			$sql = "SELECT * FROM passwords WHERE pw_id = '$pwID' AND pw_uID = '$userID'";
			$res = $dbh->prepare($sql);
			$res->execute();
			$user_name = $res->fetchAll();
			$user_name = decrypt_uPwds($user_name[0]['pw_user']);
			
			$data = array(
				"valid"    => true,
				"user" => $user_name
			);
		}
		else
			$data = array(
				"valid" => false,
				"user" => "Forbidden"
			);
			
		echo json_encode($data);
	endif;
