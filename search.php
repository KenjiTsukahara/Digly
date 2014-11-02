<?php 
	
	require('common.php');
	
    if(!empty($_POST['edit_user'])) {
			require('request_edit.php');
		}
	
	
	if(empty($_REQUEST['search_word'])) {
		header('Location: index.php');
	}

	$user_search = $_REQUEST['search_word'];
	$user_s = mb_convert_kana($user_search,"s","utf-8");
	$not_words = array(",", "、", " ","。",".");
	$clean_search = str_replace($not_words, ' ', $user_s);
	
	$search_words = explode(" ", $clean_search);
		
	//if several search words	
	if(count($search_words) > 1) {
		foreach($search_words as $word) {
			$where_list2[] = "concat(artistName, trackName) collate utf8_unicode_ci LIKE '%$word%' OR";
		}
		
		$where_clause2 = implode(" ", $where_list2);
		$where_clause2 = substr($where_clause2, 0, -2);
		$search_fcontents = $dbh->prepare("SELECT * FROM music_contents WHERE $where_clause2 ORDER BY created DESC");
		$search_fcontents->execute();
		
		
		$js_music_create = $dbh->prepare("SELECT preview FROM music_contents WHERE $where_clause2 ORDER BY created DESC");
		$js_music_create->execute();

		//if one serach word
		} elseif(count($search_words) == 1) {
		
		$ifone = $search_words[0];
		$search_fcontents  = $dbh->prepare("SELECT * FROM music_contents WHERE concat(artistName, trackName) collate utf8_unicode_ci LIKE '%$ifone%' ORDER BY created DESC");
		$search_fcontents->execute();
		
		$js_music_create = $dbh->prepare("SELECT preview FROM music_contents WHERE concat(artistName, trackName) collate utf8_unicode_ci LIKE '%$ifone%' ORDER BY created DESC");
		$js_music_create->execute();
	
		
	}
	
    $stmt = $dbh->prepare("select * from users where id=:request");
    $stmt->execute(array(":request"=>$_SESSION['me']['id']));
    $s_prof = $stmt->fetch();

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
    <link href="css/head_add.css" rel="stylesheet">
    <link href="css/scrollUpimage.css" rel="stylesheet" media="screen">
    <link href="css/jquery.nailthumb.1.1.min.css" type="text/css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Cookie' rel='stylesheet' type='text/css'>
    <!--webfont-->
  </head>
  <body>
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
      <!--valueはtextareaタグの間の文言-->
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
      <!--mail表示-->
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
    <a href="#">
      <i class="icon-random icon-white" style="margin-right:5px;"></i>Shuffle
    </a>
    </li>
    <li>
    <a href="post.php">
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
    <a href="logout.php" class="mactive">
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
    <div class="searchselectbox">
    <div class="digWrapper">
    <div class="sortbox">
    <div class="sortboxinnersearch">

    </div>
    </div>
    </div>
    </div>
    <!-- main -->
    <div id="main" role="main">
    <ul id="tiles">
    <?php $i = 0; ?>
    <?php while($get_c = $search_fcontents->fetch(PDO::FETCH_ASSOC)) { ?>
    <li>
	<?php
		$i++;
		
		//get user posted
		$get_like_users = $dbh->prepare("select id,name,picture from users where id=:key");
		$get_like_users->execute(array(":key"=>$get_c['user_id']));
		$get_u = $get_like_users->fetch();
	
	
	?>
    <div class="digWrapper">
    <div class="digImageActionButtonWrapper">
    <div class="likeEditButtonWrapper">
    <?php
							//if not posted contents, put like btn
							if($_SESSION['me']['id'] !== $get_u['id']) {
							?>
    <form name="like" method="post" action="" class="like_form">
      <input type="hidden" value="<?= $get_c['id'] . "," . $get_c['f_m']; ?>" name="contents">
      <?php
			$like = $dbh->prepare("select id from music_likes where like_user_id=:me AND like_contents_id=:request");
			$like->execute(array(":me"=>$_SESSION['me']['id'],":request"=>$get_c['id']));
			$likes = $like->fetch();
	
		if(empty($likes)) {
	?>
      <input type="image" id="<?= $get_c['id']; ?>" src="img/digliked.png" >
      <?php } else { ?>
      <input type="image" id="<?= $get_c['id']; ?>" src="img/diglike.png" >
      <?php } ?>
    </form>
    <?php } ?>
    </div>
    <div class="digHolder">
    <a href="#" class="digImageWrapper">
      <div class="fadeContainer">
      <div class="hoverMask">
      </div>
      <a class="iframe digImg fullBleed loaded" href="music_contents.php?id=<?= h($get_c['id'])?>">
        <img style="width:100%;" src="<?= h($get_c['artwork']); ?>"c>
      </a>
      </div>
    </a>
    </div>
    </div>
    <?php 
	
		$like_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM music_likes WHERE like_contents_id=:c_id;");
		$like_count->execute(array(":c_id"=>$get_c['id']));
		$like_counts = $like_count->fetchColumn();	
		
		$comme_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM music_comments WHERE contents_id=:c_id;");
		$comme_count->execute(array(":c_id"=>$get_c['id']));
		$comme_counts = $comme_count->fetchColumn();
		
	?>
    <div class="digMeta">
    <!-- meta data -->
    <p class="digDescriptionMusic"><?= h($get_c['trackName']); ?>
    </p>
    <p class="digDescriptionMusic" style="font-size:12px;"><?= h($get_c['artistName']); ?>
    </p>
    </div>
    <div class="digMetaUnder">
    <div class="digSocialMeta">
    <p class="icon-blue">
    <i class="icon-heart" title="heart"></i>
    </p>
    <span class="socialItem" id="<?= 'change' . h($get_c['id']); ?>"><?= h($like_counts); ?>
    </span>
    <!--like count-->&nbsp; 
    <p class="icon-blue">
    <i class="icon-comment" title="heart"></i>
    </p>
    <span class="socialItem"><?= h($comme_counts); ?>
    </span>
    <!--comment count-->
    </div>
    <div class="digSocialMetajp">
    <div id="jquery_jplayer_<?= $i ?>" class="jp-jplayer">
    </div>
    <div id="jp_container_<?= $i ?>" class="jp-audio" style="color:transpaernt;background-color:transparent;border:none;">
    <div class="jp-type-single">
    <div class="jp-gui jp-interface" style="display:block;">
    <form name="plays_<?= $i ?>" method="post" action="" class="prev_get" >
      <input type=hidden name="prev" value="4">
      <input type=hidden name="mfora" value="<?= $get_c['preview'] ?>">
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
    </ul>
    </div>
    </div>
    </div>
    </div>
    </div>
    <div class="digCredits hasCondensedCredits">
    <a href="user.php?id=<?= h($get_u['id']); ?>" class="creditItem firstCredit lastCredit recommendeddig">
      <img src="<?= h($get_u['picture']); ?>" class="creditImg">
      <!--user img-->
      <span class="creditName"><?= h($get_u['name']); ?>
      </span>
      <!--username-->
    </a>
    </div>
    <?php if($create_time['l_p'] == 1) { ?>
    <!-- if liked -->
    <div class="digLiked">
    <span class="likedName">
    <i class="icon-heart icon-black"></i>By
    <a href="user.php?id=<?= h($get_f['id']); ?>"><?= h($get_f['name']); ?></a>
    </span>
    <!--likedusername-->
    </div>
    </div>
    <?php } ?>
    </li>
    <?php } ?>
    </div>
    </ul>
    </div>
    <!-- main -->
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
    <!--nailshumb-->
    <script type="text/javascript">  
		$(document).ready(function() {  
	    	$('.nailthumb-container').nailthumb({width:129,height:129});
	    	$('.nailsthumbuser').nailthumb({width:140,height:140,fitDirection:'top center'});//top userimg
	    	$('.nailthumb-edit').nailthumb({width:129,height:129,fitDirection:'top center'}); //edit userimg
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
    <!-- Wookmark plug-in  -->
    <script type="text/javascript">
		(function ($){
		  $('#tiles').imagesLoaded(function() {
		    // Prepare layout options.
		    var options = {
		      itemWidth: 200, // Optional min width of a grid item
		      autoResize: true, // This will auto-update the layout when the browser window is resized.
		      container: $('#tiles'), // Optional, used for some extra CSS styling
		      offset: 5, // Optional, the distance between grid items
		      outerOffset: 10, // Optional the distance from grid to parent
		      flexibleWidth: 250 // Optional, the maximum width of a grid item
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
		$(document).ready(function(){
		<?php
			$i = 0;
			while($js_p_music = $js_music_create->fetch(PDO::FETCH_ASSOC)) {
			$i++;
		?>
			$("#jquery_jplayer_<?= $i ?>").jPlayer({
				cssSelectorAncestor: "#jp_container_<?= $i ?>",
				ready: function () {
		
					$(this).jPlayer("setMedia", {	
						m4a: "<?= $js_p_music['preview']; ?>"
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