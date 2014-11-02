<?php


session_start();

if (!empty($_SESSION['me'])) {
    header('Location: ../index.php');
    exit;
}

	
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>Digly</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="../css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="../css/bootstrap-overrides.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css' />
  </head>
  <body>
    <style>
    @import url(http://fonts.googleapis.com/css?family=Damion); /* webfont */
    </style>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.backstretch.min.js"></script>

    <script type="text/javascript">
		$.backstretch("../img/digly-img-background.jpg");
	</script>
    <!-- Top Nav-->
    <div class="navbar navbar-inverse navbar-static-top">
      <div class="navbar-inner" style="padding: 10px 0px 10px 0px;
background: #252528;">
        <div class="container" style="line-height: 40px;">
          <a style="font-family: 'Damion', cursive; color: #01b1ed !important;	font-size:40px !important; vertical-align: bottom; font-weight:600;" href="#">Digly</a>
        </div>
      </div>
    </div>
    <!-- main-->
    <div class="front-card"style="height: 328px; margin: -154px 0 0 -418px; top: 50%; width: 838px; left: 50%; position: absolute; width: 838px;">
      <div class="front-welcome" style="display: block; height: 328px; left: 0; position: absolute; top: 0; width: 520px;">
        <div class="front-welcome-text" style="top 0; color: #eee; font-size: 20px; font-weight: 300; left: 0; line-height: 22px; padding: 20px; position: absolute;
text-align: left; text-shadow: #000 0 1px 2px; width: 470px;">
          <h1 style="font-family:arial">Welcome to Digly!</h1>
        </div>
      </div>
      <div class="front-signin" style="left: 536px; position: absolute; top: 50px;">
        <a href="callback.php">
          <img src="../img/join_tw.png" style="width:260px; border: solid 1px #fff;">
        </a>
      </div>
      <div class="front-signin" style="left: 536px; position: absolute; top: 140px;">
        <a href="redirect.php">
          <img src="../img/join_fb.png" style="width:260px; border: solid 1px #fff;">
        </a>
      </div>
    </div>
    <!-- footer-->
    <div id="footer" style="width:100%; position: absolute; bottom: 0;" 
      <div id="footer inner" style="width:900px; height: 80; text-align: center; padding: 0; margin:auto 0;">
        <ul style="text-align:center; margin: 0; padding: 0; ">
          <li style="display:inline-block; font-size:11px; margin-right:3px;">
            <a style="color:#fff;" href="">Diglyについて</a>
          </li>
          <li style="display:inline-block; font-size:11px; margin-right:3px;">
            <a style="color:#fff;" href="">利用規約</a>
          </li>
          <li style="display:inline-block; font-size:11px; margin-right:3px;">
            <a style="color:#fff;" href="">プライバシーポリシー</a>
          </li>
          <li style="display:inline-block; font-size:11px; margin-right:3px;">
            <a style="color:#fff;" href="">ヘルプ</a>
          </li>
          <li style="display:inline-block; font-size:11px; margin-right:3px;">
            <a style="color:#fff;" href="">プレスリリース</a>
          </li>
          <li style="display:inline-block; font-size:11px; margin-right:3px;">
            <a style="color:#fff;" href="">お問い合わせ</a>
          </li>
        </ul>
      </div>
    </div>
  </body>
</html>
