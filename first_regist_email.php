<?php 
	require('common.php');

 	$stmt = $dbh->prepare("select email from users where id=:request");
    $stmt->execute(array(":request"=>$_SESSION['me']['id']));
    $mail = $stmt->fetch();
	
	if(empty($mail['email']) OR $mail['email'] == null) {
	
		if(isset($_POST['email'])) {
		
			$get_mails = $_POST['email'];
			//正規表現
			if (!preg_match('/^(?:(?:(?:(?:[a-zA-Z0-9_!#\$\%&\'*+\/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!#\$\%&\'*+\/=?\^`{}~|\-]+))*)|(?:"(?:\\[^\r\n]|[^\\"])*")))\@(?:(?:(?:(?:[a-zA-Z0-9_!#\$\%&\'*+\/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!#\$\%&\'*+\/=?\^`{}~|\-]+))*)|(?:\[(?:\\\S|[\x21-\x5a\x5e-\x7e])*\])))$/', $get_mails)) {
			$error1 = '正しいメールアドレスを入力してください。';
		
		}
		//judge error
		$stmt = $dbh->prepare("select email from users where email=:request");
		$stmt->execute(array(":request"=>$get_mails));
		$dub_mail = $stmt->fetch();
		
		if(!empty($dub_mail['email']) && $dub_mail['email'] !== null) {
			$error2 = 'このメールアドレスはすでに登録されています';
		}
		
		if(empty($error1) && empty($error2)) {
			//insert user table
			$sql = "update users set email=:mail where id=:id";
			$email = $dbh->prepare($sql);
			$params = array(
			":mail" => $get_mails,
			":id" => $_SESSION['me']['id']
			);
			$email->execute($params);
			header('Location: index.php');
			exit;
		}
		
		} else {
		
		header('Location: index.php');
	
	}
	}
	
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <title>Digly</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="css/digly.css" rel="stylesheet" media="screen">
    <link href="css/scrollUpimage.css" rel="stylesheet" media="screen">
    <link href="css/register_email.css" rel="stylesheet" media="screen">
    <link href='http://fonts.googleapis.com/css?family=Damion' rel='stylesheet' type='text/css'>
    <!--webfont-->
  </head>
  <body style="background-color:#fff;">
    <!-- navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="index.html">
            <font color="#01b1ed">Digly</font>
          </a>
          <div class="nav-collapse collapse">
            <ul class="nav pull-right">
            <li>
            <a href="about.html">Diglyについて</a>
            </li>
            <li>
            <a href="terms.html">利用規約</a>
            </li>
            <li>
            <a href="privacy.html">プライバシーポリシー</a>
            </li>
            <li>
            <a href="faq.html">ヘルプ</a>
            </li>
            <li>
            <a href="press.html">プレスリリース</a>
            </li>
            <li>
            <a href="contact.html">お問い合わせ</a>
            </li>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="icon-chevron-down icon-white"></i>
              <b class="caret"></b>
            </a>
            <ul class="dropdown-menu" class="gactive">
            <li>
            <a href="features.html" class="gactive">プロフィール</a>
            </li>
            <li>
            <a href="services.html" class="gactive">プロフィールを編集</a>
            </li>
            <li>
            <a href="faq.html" class="gactive">ヘルプ</a>
            </li>
            <li>
            <a href="portfolio-item.html" class="gactive">ログアウト</a>
            </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!--/.nav-collapse -->
    <!--Container-->
    <div class="section">
      <div class="email-form">
        <p style="color:#000; font-size:15px;">メールアドレス登録</p>
        <p>メールアドレスを入力して下さい。受信可能なメールアドレスを使用して登録を行って下さい。</p>
        <div>
          <form method="post" class="form-wrapper-01" action="">
            <input id="" class="inputbox email" type="text" placeholder="Email" name="email" />
            <input type="submit" class="btn" style="background-color:#01b1ed;color:#eee;border-color:#01b1ed;" value="送信">
          </form>
          <p><?= $error1; ?><?= $error2; ?></p>
        </div>
      </div>
    </div>
    <!--END-->
    <!-- footer -->
    <footer>
      <div id="footer">
        <div class="containerfooter" style="text-align:center;">
          <div class="footernav">
            <ul>
            <li>
            <a href="">Diglyについて</a>
            </li>
            <li>
            <a href="">利用規約</a>
            </li>
            <li>
            <a href="">プライバシーポリシー</a>
            </li>
            <li>
            <a href="">ヘルプ</a>
            </li>
            <li>
            <a href="">プレスリリース</a>
            </li>
            <li>
            <a href="">お問い合わせ</a>
            </li>
            </ul>
            <p class="gallright">All Right Reserved @2013 Digly</p>
          </div>
        </div>
      </div>
    </footer>
    <!--/.footer-->
    <!-- Include the plug-in -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.scrollUp.js"></script>
    <script src="js/footerFixed.js"></script>
    <!-- scrollUp plug-in-->
    <script>
	$(function () {
	    $.scrollUp({
	        scrollName: 'scrollUp',
	        topDistance: '1500',
	        topSpeed: 300,
	        animation: 'fade',
	        animationInSpeed: 800,
	        animationOutSpeed: 200,
	        scrollText: '', 
	        scrollImg: true,
	        activeOverlay: false
	    });
		});	
	</script>
    <!-- jQuery Prugin-->
    <script type="text/javascript">
		$.backstretch("join/img/digly-img-background.jpg");
	</script>
  </body>
</html>