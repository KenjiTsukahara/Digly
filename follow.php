<?php
	
	require('common.php');
	
	if (empty($_SESSION['me'])) {
		header('Location: '.SITE_URL.'index.php');
		exit;
	}

	if(!empty($_POST['users'])) {
		//judge followed or not
		$follow = $dbh->prepare("select id from follows where follow_user_id=:me AND follower_user_id=:you");
		$follow->execute(array(":me"=>$_SESSION['me']['id'],":you"=>$_POST['users']));
		$follows = $follow->fetch();
		
		//if not followed yet
		if(empty($follows)) {
		
			$sql = "insert into follows (follow_user_id, follower_user_id, created) values (:me, :you, now())";
			$follow_in = $dbh->prepare($sql);
			$params = array(":me"=>$_SESSION['me']['id'], ":you"=>$_POST['users']);
			$follow_in->execute($params);
			
		//if already followed      
		} elseif(!empty($follows)) {
		
			$unfollow = $dbh->prepare("delete from follows where follow_user_id=:me AND follower_user_id=:you");
			$unfollow->execute(array(":me"=>$_SESSION['me']['id'], ":you"=>$_POST['users']));
		
		}
	}

	$follow_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM follows WHERE follower_user_id=:follower");
    $follow_count->execute(array(":follower"=>$_POST['users']));
    $follow_counts = $follow_count->fetchColumn();
    
    $follower_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM follows WHERE follow_user_id=:follower");
    $follower_count->execute(array(":follower"=>$_POST['users']));
    $follower_counts = $follower_count->fetchColumn();
    
    echo $follow_counts . "@" . $follower_counts;	


?>
