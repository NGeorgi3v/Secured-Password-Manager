<?php
  $db_host = 'localhost';
  $db_name = 'elec4qif_pwds';
  $db_user = 'elec4qif_pwds';
  $db_pass = '!Electric-42';
  $charset = 'utf8';

  $dsn = "mysql:host=$db_host;dbname=$db_name;charset=$charset";
  $opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];

  try
  {
    $dbh = new PDO($dsn, $db_user, $db_pass, $opt);
  }
  catch (Exception $e)
  {
     echo 'Connection failed: ' . $e->getMessage();
  }

  function encrypt_uPwds($pass, $mKey)
  {
	$max = max(mb_strlen($pass), mb_strlen($mKey));
    $splitedPass = str_split($pass);
    $newData = null;
    foreach($splitedPass as $split)
    {
      $sign = ord($split) + 3; // (x+3)*3 = y    (5+3)*3 = 8*3 = 24     24/3 = 8 - 3;
      $sign = chr($sign);
      $newData .= $sign;
    }
	
	$splitedNewP = str_split($newData);
	$splitedMKey = str_split($mKey);
	$encPass = mb_strlen($pass)."*";
	
	for($i=0; $i<=$max; $i++)
	{
		$encPass .= $splitedMKey[$i] . $splitedNewP[$i];
	}

    $salt = "sdiufh8hr348(*9f9p8h9gfde5#pass_q!werty123";
    $salt2 = "youtube3_#asda9asd3244mypasswo)_r993";
    $encPass = base64_encode($salt.$encPass.$salt2);

    return $encPass;
  }

  function decrypt_uPwds($pass)
  {
	$salt = "sdiufh8hr348(*9f9p8h9gfde5#pass_q!werty123";
	$salt2 = "youtube3_#asda9asd3244mypasswo)_r993";
	$newData = base64_decode($pass);
	$newData = str_replace($salt, "", str_replace($salt2, "", $newData));
	$newData = explode("*", $newData, 2);
	$len = $newData[0];
	$newData = $newData[1];
	$newData = str_split($newData);
	
	$decPass = NULL;
	for($i = 0; $i <= $len*2 ; $i++)
	{
		$decPass .= $i%2!=0 ? $newData[$i] : "";
	}
   
	$splited = str_split($decPass);
	$decPass = null;
	foreach($splited as $split)
	{
	  $sign = ord($split) - 3;
	  $sign = chr($sign);
	  $decPass .= $sign;
	}
	  
	return $decPass;
  }

  function encrypt($pass)
  {
      $splited = str_split($pass);
		$newData = null;
		foreach($splited as $split)
		{
			$sign = ord($split) + 1;
			$sign = chr($sign);
			$newData .= $sign;
		}

		$pwd = "tt9)hr!a3a2*_ss3d58ps2wpq4p9hya8fuy#48gawi3eusm_ob9d3h#r9f1s_seya94sru9(edf3do";
		$newData = $pwd.$pass.$pwd;
		$newData = hash('sha512', $pwd.$newData.$pwd);
		$newData = hash('whirlpool', $pwd.$newData.$pwd);
		$newData = base64_encode($pwd.$newData.$pwd);
		$newData = md5($pwd.$newData.$pwd);

      return $newData;
  }
