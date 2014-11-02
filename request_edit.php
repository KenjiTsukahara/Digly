<?php

	mb_language('ja');
	mb_internal_encoding('UTF-8');
	mb_regex_encoding('UTF-8');
	header('Content-Type: text/html; charset=UTF-8');

	session_start();
	require_once('join/config.php');


	if (empty($_SESSION['me'])) {
	    header('Location: '.SITE_URL.'index.php');
	    exit;
	}
	
	try {
        $dbh = new PDO(DSN, DB_USER, DB_PASSWORD);
        $stmt = $dbh -> query("SET NAMES utf8;");
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }

	//judge error
	if(!empty($_POST['user_name'])) {
		$fileName = $_FILES['imageEdit']['name'];
		if(!empty($fileName)) {
			$ext = substr($fileName, -3);
			if($ext != 'jpg' && $ext != 'gif' && $ext != 'png' && $ext != 'gif') {
			echo '<script language="javascript">';
			echo 'alert("fileの拡張子が無効です");';
			echo 'location.href("index.php");';
			echo '</script>';
		}
	}
	
	if(empty($error)) {
		//uploade img
		$filein = $_FILES['imageEdit']['name'];
		if(!empty($filein)) {
			$image = 'user_images/' .date('YmdHis') . $_FILES['imageEdit']['name'];
			move_uploaded_file($_FILES['imageEdit']['tmp_name'],$image);
		}else {
			//get session prof
		    $stmt = $dbh->prepare("select picture from users where id=:request");
		    $stmt->execute(array(":request"=>$_SESSION['me']['id']));
		    $ss_prof = $stmt->fetch();
			$image = $ss_prof['picture'];
		}
			$stmt = $dbh->prepare("update users set name=:name, prof=:prof, picture=:picture, modified=now() where id=:id");
	        $params = array(
	            ":name"=>$_POST['user_name'],
	            ":prof"=>$_POST['user_prof'],
	            ":picture"=>$image,
	            ":id"=>$_SESSION['me']['id']
	        );
	        $stmt->execute($params); 
	        $url = $_REQUEST['id'];
	        $page_url_get = $_SERVER['HTTP_REFERER'];
			header("Location: $page_url_get");
			exit();
		}
	}
	
	
	
	
	
	