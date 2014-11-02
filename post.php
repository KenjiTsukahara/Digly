<?php 

	session_cache_limiter('private_no_expire');
	require('common.php');


	if(!empty($_POST['edit_user'])) {
		require('request_edit.php');
	}
	
	//get session prof
   $stmt = $dbh->prepare("select name,prof,email,picture from users where id=:request");
   $stmt->execute(array(":request"=>$_SESSION['me']['id']));
   $s_prof = $stmt->fetch();
   
   
   if(isset($_POST['artist'])) {
   		$url = $_POST['artwork'];
		$data = file_get_contents($url);
		$date = date('Ymd-His');
		$file_name = $date . sha1( uniqid( mt_rand() , true ) ) . ".jpg";
		file_put_contents('contents_img/'.$file_name, $data);
    	
    	$img_url = 	"http://www.digly.jp/contents_img/".$file_name;
	   
	   $sql = "INSERT INTO music_contents 
				(artistName, trackName, category, artwork, preview, trackview, caption, user_id, f_m, created, modified) 
                values 
                (:artist, :track, :category, :artwork, :preview, :trackview, :caption, :user_id, :f_m, now(), now())";
        $stmt = $dbh->prepare($sql);
        $params = array(
            ":artist" => $_POST['artist'],
            ":track" => $_POST['track'],
			":category" => $_POST['category'],
            ":artwork" => $img_url,
            ":preview" => $_POST['preview'],
            ":trackview" => $_POST['trackview'],
            ":caption" => $_POST['caption'],
            ":user_id" => $_SESSION['me']['id'],
            ":f_m" => 2,
            );
        $stmt->execute($params);
        
        $last_id = $dbh->prepare("select id from music_contents where id=:last_insert_id limit 1");
        $last_id->execute(array(":last_insert_id"=>$dbh->lastInsertId()));
        $last_contents_id = $last_id->fetch();
        
        $sql = "insert into music_create_time 
                (contents_id, user_id, f_m, l_p, created) 
                values 
                (:c_id, :u_id, :fm, :lp, now())";
        $stmt = $dbh->prepare($sql);
        $params = array(
            ":c_id" => $last_contents_id['id'],
            ":u_id" => $_SESSION['me']['id'],
			":fm" => 2,
            ":lp" => 2
            );
        $stmt->execute($params);

		header("Location: index.php");
	   
   }

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
  <title>Digly</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link href="css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
  <link href="css/digly.css" rel="stylesheet" media="screen">
  <link href="css/scrollUpimage.css" rel="stylesheet" media="screen">
  <link href="css/jquery.nailthumb.1.1.min.css" type="text/css" rel="stylesheet" />
  <link href='http://fonts.googleapis.com/css?family=Cookie' rel='stylesheet' type='text/css'>
  <!--webfont-->
  <link href="css/head_add.css" rel='stylesheet' type='text/css'>
  <body style="background-color:#fff;">
    <!-- Edit Modal Window -->
    <div id="editModal" class="modal hide fade">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 class="modal-title">
      <i class="icon-edit" style="font-size:30px;color:#01b1ed;"></i>Edit Your Profile
    </h3>
    </div>
    <div class="modal-content">
    <!-- dialog body -->
    <div class="modal-body">
    <div class="container-fluid">
    <div class="row-fluid">
    <div class="span6">
    <form action="" method="post" enctype="multipart/form-data" />
    <span>User Name
    </span>
    </div>
    <div class="span6">
    <input type="hidden" value="on" class="span12" name="edit_user">
    <input type="text" value="<?= h($s_prof['name']); ?>" class="span12" name="user_name">
    </div>
    </div>
    <div class="row-fluid">
    <div class="span6">
    <span>About Me
    </span>
    </div>
    <div class="span6">
    <textarea rows="5" class="span12" name="user_prof"><?= h($s_prof['prof']); ?> </textarea>
    </div>
    </div>
    <div class="row-fluid">
    <div class="span6">
    <span>Mail Adrress
    </span>
    <p class="editmail">
    <a href="register_email.php">変更する</a>
    </span>
    </div>
    <div class="span6">
    <span style="word-wrap:break-word;"><?= h($s_prof['email']); ?>
    </span>
    
    </div>
    </div>
    <div class="row-fluid">
    <div class="span6">
    <span>Profile Image
    </span>
    <p class="filebtn">変更する
    <input type="file" name="imageEdit" id="imageEdit" name="user_image" />
    </p>
    </div>
    <div class="span6" style="width:100px height:100px;">
    <div class="nailthumb-edit square-thumb" style="margin: 0 auto;">
    <img src="<?= h($s_prof['picture']); ?>" id="previewEdit"/>
    </div>
    </div>
    </div>
    </div>
    </div>
    <!-- dialog buttons -->
    <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
    <input type="submit" class="btn btn-primary" value="変更する"/>
    </form>
    </div>
    </div>
    </div>
    </div>
    <!-- /.Edit Modal Window -->
    <!-- navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
    <div class="container">
    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
      <span class="icon-bar">
      </span>
      <span class="icon-bar">
      </span>
      <span class="icon-bar">
      </span>
    </button>
    <a class="brand" href="http://digly.jp">
      <font color="#01b1ed">Digly</font>
    </a>
    <div class="nav-collapse collapse">
    <ul class="nav navbar-nav">
	<li>
    <a href="index.php">
      <i class="icon-bookmark icon-white" style="margin-right:5px;"></i>TimeLine
    </a>
    </li>
    <li>
    <a href="popular.php">
      <i class="icon-headphones icon-white" style="margin-right:5px;"></i>Popular
    </a>
    </li>
    <li>
    <a href="">
      <i class="icon-random icon-white" style="margin-right:5px;"></i>Shuffle
    </a>
    </li>
    <li class="mactive">
    <a href="">
      <i class="icon-plus icon-white" style="margin-right:5px;"></i>Post
    </a>
    </li>
    </ul>
    <ul class="nav pull-right">
    <li>
    <form class="input-append" method="GET" action="search.php" style="margin-top:4px;margin-bottom:5px;" name="search_submit">
    <input type="text" title="Enter keyword(s) to find" class="span2" style="background-color:#302E2F" name="search_word">
    <button type="submit" class="btn" style="background-color:#01b1ed;color:#eee;border-color:#01b1ed;">
      <i class="icon-search" title="Search"></i>
    </button>
    </form>
    </li>
    <li>
    <img style="width:30px; height: 30px;margin-top:4px;margin-bottom:5px;margin-left:5px;" src="<?= h($s_prof['picture']); ?>">
    </img>
    </li>
    <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <i class="icon-chevron-down icon-white"></i>
      <b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
    <li>
    <a href="user.php?id=<?= h($_SESSION['me']['id']); ?>" class="mactive">
      <i class="icon-user icon-white"></i>プロフィール
    </a>
    </li>
    <li>
    <a href="#editModal" data-toggle="modal" class="mactive">
      <i class="icon-cog icon-white"></i>編集
    </a>
    </li>
    <li>
    <a href="logout.html" class="mactive">
      <i class="icon-off icon-white"></i>ログアウト
    </a>
    </li>
    </ul>
    </div>
    <!--/.nav-collapse -->
    </div>
    </div>
    </div>
    <div id="container">
    <header>
      <div class="text-center" style="padding: 50px;">
      <form method="post" class="wellpost form-search">
      <input type="text" class="input-large search-query" placeholder="Artist,Song,Album" name="term">
      <button type="submit" class="btn" style="background-color:#01b1ed;color:#eee;border-color:#01b1ed">
        <i class="icon-search" title="Search"></i>
      </button>
      </form>
      </div>
    </header>
    <!-- main -->
    <div id="main" role="main">
    <ul id="tiles">
    <?php
          $i = 0;
          if(isset($_POST['term'])){
			    $term = urlencode($_POST['term']); // user input 'term' in a form
			    $json =  file_get_contents('http://itunes.apple.com/search?term='.$term.'&country=JP&entity=song');    
			    $array = json_decode($json, true);
		
		    foreach($array['results'] as $value)
			{ ?>
    <li>
    <?php $i++; ?>
    <div class="digWrapper">
    <div class="digImageActionButtonWrapper">
    <div class="likeEditButtonWrapper">
    <!--like-->
    <form name="like" method="post" action="" class="like_form">
    <input type="hidden" value="<9273f28d1fd996c60830bbe685481299 />" name="contents">
    </div>
    <div class="digHolder">
    <a href="#" class="digImageWrapper">
      <div class="fadeContainer">
      <div class="hoverMask">
      </div>
      <img src="<?= h($value['artworkUrl100']); ?>" class="digImg fullBleed loaded" style="width:100%;">
      </div>
    </a>
    </div>
    </div>
    <div class="digMeta">
    <p class="digDescriptionMusic"><?= h($value['trackName']); ?>
    </p>
    <p class="digDescriptionMusic" style="font-size:12px;"><?= h($value['artistName']); ?>
    </p>
    </div>
    <div class="digMetaUnder">
    <div class="digSocialMetajp">
    <div id="jquery_jplayer_<?= $i ?>" class="jp-jplayer">
    </div>
    <div id="jp_container_<?= $i ?>" class="jp-audio" style="color:transpaernt;background-color:transparent;border:none;">
    <div class="jp-type-single">
    <div class="jp-gui jp-interface" style="display:block;">
    <form name="plays_<?= $i ?>" method="post" action="" class="prev_get" >
    <input type=hidden name="prev" value="4">
    <input type=hidden name="mfora" value="<?= $value['previewUrl'] ?>">
    <ul class="jp-controls">
    <a href="javascript:get_id('plays_<?= $i ?>');" class="jp-play" tabindex="1">
      <i class="icon-play icon-blue icon-size24"></i>
    </a>
    </form>
    <a href="javascript:;" class="jp-pause" tabindex="1">
      <i class="icon-pause icon-blue icon-size24"></i>
    </a>
    <a href="javascript:;" class="jp-stop" tabindex="1">
      <i class="icon-stop icon-blue icon-size20"></i>
    </a>
    </div>
    </div>
    </div>
    </div>
    </div>
    <div>
    <form method="post" action="" name="sub_post">
    <input type="hidden" name="artistName" value="<? echo h($value['artistName']); ?>"/>
    <input type="hidden" name="trackName" value="<? echo h($value['trackName']); ?>"/>
    <input type="hidden" name="previewUrl" value="<? echo h($value['previewUrl']); ?>"/>
    <input type="hidden" name="artworkUrl100" value="<? echo h($value['artworkUrl100']); ?>"/>
    <input type="hidden" name="primaryGenreName" value="<? echo h($value['primaryGenreName']); ?>"/>
    <input type="hidden" name="trackviewUrl" value="<? echo h($value['trackViewUrl']); ?>"/>
    <input type="hidden" name="player" value="<? echo $i; ?>" />
    <input class="btn btn-primary" type="submit" style="width:100%;" value="選択">
    </form>
    </div>
    </div>
    </li>
    <?php
			}
			}
		?>
    <?php if(!empty($_POST['artistName'])) { ?>
    <li>
    <div class="digWrapper">
    <div class="digImageActionButtonWrapper">
    <div class="likeEditButtonWrapper">
    <!--like-->
    <form name="like" method="post" action="" class="like_form">
    <input type="hidden" value="<6e766b2be2bd924c720f949844c3e9fc />" name="contents">
    </div>
    <div class="digHolder">
    <a href="#" class="digImageWrapper">
      <div class="fadeContainer">
      <div class="hoverMask">
      </div>
      <a href="">
        <img src="<?= h($_POST['artworkUrl100']); ?>" class="digImg fullBleed loaded" style="width:100%;">
      </a>
      </div>
    </a>
    </div>
    </div>
    <div class="digMeta">
    <p class="digDescriptionMusic"><?= h($_POST['trackName']); ?>
    </p>
    <p class="digDescriptionMusic" style="font-size:12px;"><?= h($_POST['artistName']); ?>
    </p>
    </div>
    <div class="digMetaUnder">
    <div class="digSocialMetajp">
    <div id="jquery_jplayer_1" class="jp-jplayer">
    </div>
    <div id="jp_container_1" class="jp-audio" style="color:transpaernt;background-color:transparent;border:none;">
    <div class="jp-type-single">
    <div class="jp-gui jp-interface" style="display:block;">
    <form name="plays_1" method="post" action="" class="prev_get" >
    <input type=hidden name="prev" value="4">
    <input type=hidden name="mfora" value="<?= $_POST['previewUrl'] ?>">
    <ul class="jp-controls">
    <a href="javascript:get_id('plays_1');" class="jp-play" tabindex="1">
      <i class="icon-play icon-blue icon-size24"></i>
    </a>
    </form>
    <a href="javascript:;" class="jp-pause" tabindex="1">
      <i class="icon-pause icon-blue icon-size24"></i>
    </a>
    <a href="javascript:;" class="jp-stop" tabindex="1">
      <i class="icon-stop icon-blue icon-size20"></i>
    </a>
    </div>
    </div>
    </div>
    </div>
    </div>
    <div>
    <form method="post" action="" name="this_post">
    <input type="hidden" name="artist" value="<? echo h($_POST['artistName']); ?>"/>
    <input type="hidden" name="track" value="<? echo h($_POST['trackName']); ?>"/>
    <input type="hidden" name="preview" value="<? echo h($_POST['previewUrl']); ?>"/>
    <input type="hidden" name="artwork" value="<? echo h($_POST['artworkUrl100']); ?>"/>
    <input type="hidden" name="category" value="<? echo h($_POST['primaryGenreName']); ?>"/>
    <input type="hidden" name="trackview" value="<? echo h($_POST['trackviewUrl']); ?>"/>
    <textarea rows="5" class="span12" name="caption" style="width:90%;"></textarea>
    </div>
    <input class="btn btn-primary" type="submit" style="width:100%;" value="POST">
    </form>
    </div>
    </div>
    </li>
    <div class="research_btn_wrapper">
    <form action="" method="post">
    <input type="hidden" value="on" name="research">
    <input type="submit" value="← Research" class="research_btn">
    </form>
    </div>
    <?php } ?>
    </ul>
    </div>
    </div>
    <!-- /container -->
    <footer>
      <div id="footer">
      <div class="containerfooter" style="text-align:center;">
      <div class="footernav">
      <ul>
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
      <a href="blog.html">ヘルプ</a>
      </li>
      <li>
      <a href="press.html">プレスリリース</a>
      </li>
      <li>
      <a href="contact.html">お問い合わせ</a>
      </li>
      </ul>
      <p class="mallright">All Right Reserved @2013 Digly
      </p>
      </div>
      </div>
      </div>
    </footer>
    </div>
    <!-- /container -->
    <!-- Include the plug-in -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.wookmark.js"></script>
    <script src="js/jquery.imagesloaded.js"></script>
    <script src="js/jquery.scrollUp.js"></script>
    <script src="js/jquery.jplayer.min.js"></script>
    <script src="js/jquery.nailthumb.1.1.min.js"></script>
    <script src="js/footerFixed.js"></script>
    <script type="text/javascript">
		$(function(){
			$("form.submit_music").submit(function(event) {
				event.preventDefault();
				var val = $(this).children("input[name='contents']").val();
				alert(val);
				$.ajax({
					type: 'POST',
					url: '',
					data: {"contents": val},
					success: function() {
						location.href("#mPostModal");
					}
				});
				return false;
			});
		});
	</script>
    <script type="text/javascript">
		(function ($){
		  $('#tiles').imagesLoaded(function() {
		    // Prepare layout options.
		    var options = {
		      itemWidth: 100, // Optional min width of a grid item
		      autoResize: true, // This will auto-update the layout when the browser window is resized.
		      container: $('#tiles'), // Optional, used for some extra CSS styling
		      offset: 5, // Optional, the distance between grid items
		      outerOffset: 10, // Optional the distance from grid to parent
		      //250
		      flexibleWidth: 150 // Optional, the maximum width of a grid item
		    };
		    // Get a reference to your grid items.
		    var handler = $('#tiles li');
		    // Call the layout function.
		    handler.wookmark(options);
		  });
		})(jQuery);
	</script>
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
    <script type="text/javascript">
        $(document).ready(function() {
            // preview img
            $('#imageEdit').change(function() {
                var file = $(this).prop('files')[0];
                var fr = new FileReader();
                fr.onload = function() {
                    $('#previewEdit').attr('src', fr.result);   //set src loaded img
                }
                fr.readAsDataURL(file);  //load img
            });
          });
	</script>
    <script type="text/javascript">
        $(document).ready(function() {
            // preview img
            $('#imageEdit').change(function() {
                var file = $(this).prop('files')[0];
                var fr = new FileReader();
                fr.onload = function() {
                    $('#previewEdit').attr('src', fr.result);   //set src loaded img
                }
                fr.readAsDataURL(file);  //load img
            });
          });
	</script>
    <!--nailshumb-->
    <script type="text/javascript">  
	    $(document).ready(function() {  
	        $('.nailthumb-pf').nailthumb({width:60,height:60,fitDirection:'center center'});//post 
	        $('.nailthumb-edit').nailthumb({width:129,height:129,fitDirection:'top center'}); //edit user　img
	        $('.nailsthumbuser').nailthumb({width:140,height:140,fitDirection:'top center'});//top userim
		});  
	</script>
    <script type="text/javascript">
		$(document).ready(function(){
			<?php
			if(isset($_POST['term'])){
				$i = 0;
				$term2 = urlencode($_POST['term']); // user input 'term' in a form
				$json2 =  file_get_contents('http://itunes.apple.com/search?term='.$term2.'&country=JP&entity=song');    
				$array2 = json_decode($json2, true);
				
				foreach($array2['results'] as $value2) {
				$i++; 
			
			?>
			$("#jquery_jplayer_<?= $i ?>").jPlayer({
				cssSelectorAncestor: "#jp_container_<?= $i ?>",
				ready: function () {
				$(this).jPlayer("setMedia", {
					m4a: "<?= $value2['previewUrl']; ?>"
				});
			},
				play: function() { // To avoid both jPlayers playing together.
				$(this).jPlayer("pauseOthers");
			},
				swfPath: "http://jplayer.org/latest/js",
				supplied: "m4a, oga",
				wmode: "window",
				smoothPlayBar: true,
				keyEnabled: true
			});
			<?php
			} 
		}
			?>
		});
	</script>
    <script type="text/javascript">
		$(document).ready(function(){
			<?php
			if(isset($_POST['artistName'])){
			
			?>
			$("#jquery_jplayer_1").jPlayer({
				cssSelectorAncestor: "#jp_container_1",
				ready: function () {
				$(this).jPlayer("setMedia", {
				m4a: "<?= $_POST['previewUrl']; ?>"
			});
			},
				play: function() { // To avoid both jPlayers playing together.
				$(this).jPlayer("pauseOthers");
			},
				swfPath: "http://jplayer.org/latest/js",
				supplied: "m4a, oga",
				wmode: "window",
				smoothPlayBar: true,
				keyEnabled: true
			});
			<?php } ?>
		});
	</script>
  </body>
</html>