<?php
 
require_once('config.php');
require_once('codebird.php');
 
session_start();
 
\Codebird\Codebird::setConsumerKey(CONSUMER_KEY, CONSUMER_SECRET);
$cb = \Codebird\Codebird::getInstance();
 
if (! isset($_GET['oauth_verifier'])) {
    // gets a request token
    $reply = $cb->oauth_requestToken(array(
        'oauth_callback' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
    ));
 
    // stores it
    $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
    $_SESSION['oauth_token'] = $reply->oauth_token;
    $_SESSION['oauth_token_secret'] = $reply->oauth_token_secret;
 
    // gets the authorize screen URL
    $auth_url = $cb->oauth_authorize();
    header('Location: ' . $auth_url);
    die();
 
} else {
    // gets the access token
    $cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    $reply = $cb->oauth_accessToken(array(
        'oauth_verifier' => $_GET['oauth_verifier']
    ));
    
    $cb->setToken($reply->oauth_token, $reply->oauth_token_secret);
    
    $me = $cb->account_verifyCredentials();
    
    
    //insert db
    
    try {
        $dbh = new PDO(DSN, DB_USER, DB_PASSWORD);
        $stmt = $dbh -> query("SET NAMES utf8;");
        //$dbin = $dbh -> query("SET NAMES utf8;");
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
    
    $sql = "select * from users where user_id = :id limit 1";
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(":id" => $me->id));
    $user = $stmt->fetch();
    
    if (!$user) {
    		
        $sql = "insert into twusers 
                (tw_user_id, tw_screen_name, tw_user_picture, tw_access_token, tw_access_token_secret, created, modified) 
                values 
                (:tw_user_id, :tw_screen_name, :tw_user_picture, :tw_access_token, :tw_access_token_secret, now(), now())";
        $stmt = $dbh->prepare($sql);
        $params = array(
            ":tw_user_id" => $me->id_str,
            ":tw_screen_name" => $me->screen_name,
			":tw_user_picture" => $me->profile_image_url,
            ":tw_access_token" => $reply->oauth_token,
            ":tw_access_token_secret" => $reply->oauth_token_secret
        );
        $stmt->execute($params);
        
        
        //insert db users
        $sql = "insert into users 
                (user_id, name, picture, prof, login, created, modified) 
                values 
                (:user_id, :name, :picture, :prof, :login, now(), now())";
        $dbin = $dbh->prepare($sql);
        $params = array(
            ":user_id" => $me->id_str,
            ":name" => $me->screen_name,
			":picture" => $me->profile_image_url,
            ":prof" => $me->description,
            ":login" => "twitter"
        );
        $dbin->execute($params);
        
        $myId = $dbh->lastInsertId();
        $sql = "select * from users where id = :id limit 1";
        $dbin = $dbh->prepare($sql);
        $dbin->execute(array(":id" => $myId));
        $user = $dbin->fetch();    
    }
    
    // process login
    if (!empty($user)) {
        // session jijack
        session_regenerate_id(true);
        $_SESSION['me'] = $user;
         // location home
   header('Location: ../first_regist_email.php');    
    }

    
}

?>