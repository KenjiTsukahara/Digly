<?php
	
	require('common.php');


	if(empty($_POST['contents']) && empty($_POST['comments'])) {
		$page_url_get = $_SERVER['HTTP_REFERER'];
		header("Location: $page_url_get");
	} else {
		//insert comment
		$id_fm = mb_split(',',$_POST['contents']);
		if($id_fm[1] == 1) {
			$sql = "insert into comments 
			(comment,user_id, contents_id,created) 
			values 
			(:c_text, :user, :contents, now())";
			$f_comment = $dbh->prepare($sql);
			$params = array(
				":c_text"=>$_POST['comments'],
				":user" => $_SESSION['me']['id'],
				":contents" => $id_fm[0]
			);
			$f_comment->execute($params);
			$f_comment_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM comments WHERE contents_id=:likes;");
			$f_comment_count->execute(array(":likes"=>$id_fm[0]));
			$counts = $f_comment_count->fetchColumn();
		} elseif($id_fm[1] == 2) {
			$sql = "insert into music_comments 
			(comment,user_id, contents_id,created) 
			values 
			(:c_text, :user, :contents, now())";
			$m_comment = $dbh->prepare($sql);
			$params = array(
				":c_text"=>$_POST['comments'],
				":user" => $_SESSION['me']['id'],
				":contents" => $id_fm[0]
			);
			$m_comment->execute($params);
			$m_comment_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM music_comments WHERE contents_id=:likes;");
			$m_comment_count->execute(array(":likes"=>$id_fm[0]));
			$counts = $m_comment_count->fetchColumn();
		}
	}

	echo $counts;

?>
