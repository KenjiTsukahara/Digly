<?php 
mb_language('ja');
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');

header('Content-Type: text/html; charset=UTF-8');
?>
<?php
session_start();
require_once('config.php');

 try {
        $dbh = new PDO(DSN, DB_USER, DB_PASSWORD);
        $stmt = $dbh -> query("SET NAMES utf8;");
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
 
 
if (empty($_GET['code'])) {
    // 認証の準備
    
    $_SESSION['state'] = sha1(uniqid(mt_rand(), true));
    
    $params = array(
        'client_id' => APP_ID,
        'redirect_uri' => 'http://www.digly.jp/join/redirect.php',
        'state' => $_SESSION['state'],
        'scope' => 'email,user_about_me'
    );
    
    $url = "https://www.facebook.com/dialog/oauth?".http_build_query($params);    
    
  // facebookに一旦飛ばす
    header('Location: '.$url);
    exit;
 
} else {
    // 認証後の処理
    
    // CSRF対策
    if ($_SESSION['state'] != $_GET['state']) {
        echo "不正な処理！";
        exit;
    }
    
    // ユーザー情報の取得
    $params = array(
        'client_id' => APP_ID,
        'client_secret' => APP_SECRET,
        'code' => $_GET['code'],
        'redirect_uri' => 'http://www.digly.jp/join/redirect.php'
    );
    $url = 'https://graph.facebook.com/oauth/access_token?'.http_build_query($params);    
	//$body = json_decode(file_get_contents($url));
    $body = file_get_contents($url);
    parse_str($body);
    
	$url = 'https://graph.facebook.com/me?access_token='.$access_token.'&fields=name,picture,bio,email';
	$me = json_decode(file_get_contents($url));
    //var_dump($me);
    //exit;
    

    
    
    
    $get_id = $me->id;
    $get_name = $me->name;
    //$get_picture = $me->picture->data->url;
    $get_bio = $me->bio;
    $get_mail = $me->email;
    
    $get_picture = 'https://graph.facebook.com/' . $get_id . '/picture?type=large';
    
        
    $stmt = $dbh->prepare("select * from users where user_id=:u_id limit 1");
    $stmt->execute(array(":u_id"=>$get_id));
    $user = $stmt->fetch();
 
  if (empty($user)) {
  
$stmt = $dbh->prepare("insert into fbusers (facebook_user_id, facebook_name, facebook_access_token, created, modified) values (:user_id, :screen_name, :access_token, now(), now());");
        $parameter = array(
            ":user_id"=>$get_id,
            ":screen_name"=>$get_name,
            ":access_token"=>$access_token
        );
		$stmt->execute($parameter);
		$stmt->closeCursor();
		
		
		
		
$stmt = $dbh->prepare("insert into users (user_id, login, picture, prof, name, email, created, modified) values (:getuser, :getlogin, :getpicture, :getprof, :getname, :getmail, now(),now())");
       /* $params = array(":user_id"=>$get_id,":login"=>'facebook',":picture"=>$get_picture,":prof"=>$get_bio,":name"=>$get_name,":mail"=>$get_mail);*/
        $stmt->execute(array(":getuser"=>$get_id,":getlogin"=>'facebook',":getpicture"=>$get_picture,":getprof"=>$get_bio,":getname"=>$get_name,":getmail"=>$get_mail));
        //$tbin->closeCursor(); 
        
        //セッションに入れるデータをselect
        $sesin = $dbh->prepare("select * from users where id=:last_insert_id limit 1");
        $sesin->execute(array(":last_insert_id"=>$dbh->lastInsertId()));
        $user = $sesin->fetch();

        }
        
        
        
        
    if (!empty($user)) {
        session_regenerate_id(true);
        $_SESSION['me'] = $user;
        /*$cookie_param = 1;
        setcookie("already", $cookie_param, time()+604800);*/
        header('Location: ../index.php');
    }
    
    

}
