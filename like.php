<?php

	require('common.php');
	    
	if(!empty($_POST['contents'])) {
	
		$id_fm = mb_split(',',$_POST['contents']);
		
		//if liked or not
		$m_like = $dbh->prepare("select id from music_likes where like_user_id=:me AND like_contents_id=:request");
		$m_like->execute(array(":me"=>$_SESSION['me']['id'],":request"=>$id_fm[0]));
		$m_likes = $m_like->fetch();
		
		//if not liked yet
		if(empty($m_likes)) {
		
		
			//insert create table
			$sql = "insert into music_create_time 
			(user_id, contents_id, f_m, l_p, created) 
			values 
			(:user, :contents, :f_m, :l_p, now())";
			$like = $dbh->prepare($sql);
			$params = array(
				":user" => $_SESSION['me']['id'],
				":contents" => $id_fm[0],
				":f_m" => $id_fm[1],
				":l_p" => 1
			);
			$like->execute($params);
			
			
			$myId = $dbh->lastInsertId();
			
			$sql = "insert into music_likes (like_user_id, like_contents_id, mct_id, f_m, created) 
			values 
			(:user, :contents,:mct_id, :fm, now())";
			$like = $dbh->prepare($sql);
			$params = array(
				":user" => $_SESSION['me']['id'],
				":contents" => $id_fm[0],
				":mct_id" => $myId,
				":fm" => $id_fm[1]
			);
			$like->execute($params);
			
		//if already liked      
		} elseif(!empty($m_likes)) {
			$unlike = $dbh->prepare("delete from music_likes where like_user_id=:me AND like_contents_id=:request");
			$unlike->execute(array(":me"=>$_SESSION['me']['id'], ":request"=>$id_fm[0]));
			
			//delete from create_time
			$c_unlike = $dbh->prepare("delete from music_create_time where user_id=:me AND contents_id=:request AND l_p=1");
			$c_unlike->execute(array(":me"=>$_SESSION['me']['id'], ":request"=>$id_fm[0]));
		}
		
		$like_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM music_likes WHERE like_contents_id=:likes;");
		$like_count->execute(array(":likes"=>$id_fm[0]));
		$like_counts = $like_count->fetchColumn();
	}


	echo $id_fm[0] . "," . $like_counts;	
?>
