<?php 

	require('common.php');
	
		
	if(!empty($_POST['edit_user'])) {
		require('request_edit.php');
	}
	
	    //get profile
    $stmt = $dbh->prepare("select * from users where id=:request");
    $stmt->execute(array(":request"=>$_REQUEST['id']));
    $prof = $stmt->fetch();

		//get contents 
		$music_post = $dbh->prepare("select id,artistName,trackName,artwork,user_id,f_m from music_contents where user_id=:request OR id in (select like_contents_id from music_likes where like_user_id = :request) ORDER BY created DESC");
		$music_post->execute(array(":request"=>$_REQUEST['id']));
		
		
		//get contents count
		
		$m_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM music_contents WHERE user_id=:request OR id in (select like_contents_id from music_likes where like_user_id = :request);");
		$m_count->execute(array(":request"=>$_REQUEST['id']));
		$m_counts = $m_count->fetchColumn();
		
		//follower count
		$follower_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM follows WHERE follow_user_id=:follow;");
		$follower_count->execute(array(":follow"=>$_REQUEST['id']));
		$follower_counts = $follower_count->fetchColumn();
																				    
		//follow count
		$follow_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM follows WHERE follower_user_id=:follower;");
		$follow_count->execute(array(":follower"=>$_REQUEST['id']));
		$follow_counts = $follow_count->fetchColumn();	
		
		$stmt = $dbh->prepare("select name,prof,email,picture from users where id=:request");
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
    <link href="css/scrollUpimage.css" rel="stylesheet" media="screen">
    <link href="css/jquery.nailthumb.1.1.min.css" type="text/css" rel="stylesheet" />
    <link href="css/colorbox.css" type="text/css" rel="stylesheet" />
    <link href="css/head_add.css" type="text/css" rel="stylesheet" />
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
    <a href="index.php">
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
    <div id="user-barview">
    <!--user-->
    <div class="user-content">
    <div class="user-nailsthumb">
    <img alt="Profile-img" class="avatar nailsthumbuser" src="<?= h($prof['picture']); ?>">
    </div>
    <div class="user-info">
    <div class="user-name"><?= h($prof['name']); ?> 
    </div>
    <div class="clearl">
    </div>
    <div class="user-bio"><?= h($prof['prof']); ?> 
    </div>
    <div class="clearl">
    </div>
    <div class="user-website">
    <a href="" target="_blank"></a>
    </div>
    <div class="user-stats">
    <div class="user-stat">
    <a href="view.php?id=<?= h($_REQUEST['id']); ?>">
      <div class="user-stat-count">
      <font color="#01b1ed"><?= h($m_counts); ?></font>
      </div>
      <div class="user-stat-label">
      <font color="#01b1ed">music</font>
      </div>
    </a>
    </div>
    <div class="user-stat">
    <a href="followers.php?id=<?= h($_REQUEST['id']); ?>">
      <div class="user-stat-count">
      <div id="follower_count"><?= h($follower_counts); ?> 
      </div>
      </div>
      <div class="user-stat-label">followers 
      </div>
    </a>
    </div>
    <div class="user-stat">
    <a href="following.php?id=<?= h($_REQUEST['id']); ?>">
      <div class="user-stat-count">
      <div id="follow_count"><?= h($follow_counts); ?> 
      </div>
      </div>
      <div class="user-stat-label">following 
      </div>
    </a>
    </div>
    <?php if($_REQUEST['id'] !== $_SESSION['me']['id']) { ?>
    <div class="user-stat">
    <!--フォローボタン-->
    <div class="follows" style="width:110px;">
    <!--follow部分-->
    <form name="like" method="post" action="" class="follow">
      <input type="hidden" value="<?= $_REQUEST['id']; ?>" name="follows">
      <?php
			$follow = $dbh->prepare("select id from follows where follow_user_id=:me AND follower_user_id=:you");
					    $follow->execute(array(":me"=>$_SESSION['me']['id'],":you"=>$_REQUEST['id']));
					    $follow_in = $follow->fetch();
		    
		    if(empty($follow_in)) {
			?>
      <input type="image" id="follower" src="http://digly.jp/img/follow1.png"style="display: inline-block;border-width:0px;border-style:None;" onmouseover="this.src='http://digly.jp/img/followhover1.png'" onmouseout="this.src='http://digly.jp/img/follow1.png'"/>
      <?php } else { ?>
      <input type="image" id="follower" src="http://digly.jp/img/following1.png"style="display: inline-block;border-width:0px;border-style:None;" onmouseover="this.src='http://digly.jp/img/unfollow1.png'" onmouseout="this.src='http://digly.jp/img/following1.png'"/>
      <?php } ?>
    </form>
    </div>
    </div>
    <?php } ?>
    <div id="share-links-user-wrap">
    <div id="share-links-user">
    <a href="http://twitter.com/share?url=http://www.digly.jp/user.php?id=<6a8f7afbc599cf5ee27d4f4605c85498 />&text=ユーザーページ：<cc047a0d6887692af0bf8e61cf157f7e /> by digly.jp [URL]" target="_blank">
      <img src="img/twitter.png" />
    </a>
    <a href="http://www.facebook.com/sharer.php?s=100
&amp;p[url]=http://www.digly.jp/user.php?id=<8a0eccfc7da6854aae4d7d327baeadde />&amp;
p[title]=digly.jp&amp;p[summary]=[userpage]<abd2d6b04faf3b525b86afed04ba0e15 />&amp;m2w" target="_blank" rel="nofollow">
      <img src="img/facebook.png">
    </a>
    </div>
    </div>
    </div>
    <div class="clearb">
    </div>
    </div>
    </div>
    </div>
    <!--/.End user-->
    <!-- main -->
    <!-- items -->
    <div id="main" role="main" style="padding-top:30px;">
    <ul id="tiles">
    <!-- items -->
    <li>
    <div class="digWrapper">
    <div class="sortbox">
    <div class="sortboxinner1">
    <span>
    <i class="icon-ok" style="font-size:16px;"></i>
    </span>
    <span style="margin:0px;">:
    </span>
    <span class="sort_active">
    <a href="view.php?id=<?= $_REQUEST['id']; ?>" style="color:#3498db;">All</a>
    </span>
    <span>
    <a href="view_post.php?id=<?= $_REQUEST['id']; ?>">Post</a>
    </span>
    <span>
    <a href="view_like.php?id=<?= $_REQUEST['id']; ?>">Faborite</a>
    </span>
    </div>
    <div class="sortboxinner2">
    <span>
    <i class="icon-picture" style="font-size:15px;"></i>
    </span>
    <span style="margin:0px;">:
    </span>
    <span><?= h($m_counts); ?> Contents
    </span>
    </div>
    </div>
    </div>
    </li>
    <?php $i = 0; ?>
    <?php while($music = $music_post->fetch()) { ?>
    <li>
    <div class="digWrapper">
    <div class="digImageActionButtonWrapper">
    <div class="likeEditButtonWrapper">
    <?php
	$i++;
	//if not mypage, put like btn
	if($_SESSION['me']['id'] !== $music['user_id']) {
	?>
    <form name="like" method="post" action="" class="like_form">
      <input type="hidden" value="<?= $music['id']. "," . $music['f_m']; ?>" name="contents">
      <?php
			$like = $dbh->prepare("select id from music_likes where like_user_id=:me AND like_contents_id=:request");
			$like->execute(array(":me"=>$_SESSION['me']['id'],":request"=>$music['id']));
			$likes = $like->fetch();
		
		if(empty($likes)) {
		?>
      <input type="image" id="<?= $music['id']; ?>" src="img/digliked.png" >
      <?php } else { ?>
      <input type="image" id="<?= $music['id']; ?>" src="img/diglike.png" >
      <?php } ?>
    </form>
    <?php } ?>
    </div>
    <div class="digHolder">
    <a href="#" class="digImageWrapper">
      <div class="fadeContainer">
      <div class="hoverMask">
      </div>
      <a class="iframe digImg fullBleed loaded" href="music_contents.php?id=<?= h($music['id'])?>">
        <img src="<?= h($music['artwork']); ?>" style="width:100%;">
      </a>
      </div>
    </a>
    </div>
    </div>
    <?php 

		$like_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM music_likes WHERE like_contents_id=:c_id;");
		$like_count->execute(array(":c_id"=>$music['id']));
		$like_counts = $like_count->fetchColumn();	
		
		$comme_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM music_comments WHERE contents_id=:c_id;");
		$comme_count->execute(array(":c_id"=>$muisc['id']));
		$comme_counts = $comme_count->fetchColumn();
		
	?>
    <div class="digMeta">
    <!-- meta data -->
    <p class="digDescriptionMusic"><?= h($music['trackName']); ?>
    </p>
    <p class="digDescriptionMusic" style="font-size:12px;"><?= h($music['artistName']); ?>
    </p>
    </div>
    <div class="digMetaUnder">
    <div class="digSocialMeta">
    <p class="icon-blue">
    <i class="icon-heart" title="heart"></i>
    </p>
    <span class="socialItem" id="<?= 'change' . h($music['id']); ?>"><?= h($like_counts); ?>
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
      <input type=hidden name="mfora" value="<?= $music['preview'] ?>">
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
    <?php
		//this contentsのuserを取得
		$this_user = $dbh->prepare("select id,name,picture from users where id=:me");
	    $this_user->execute(array(":me"=>$music['user_id']));
	    $this_users = $this_user->fetch();
							
	?>
    <div class="digCredits hasCondensedCredits">
    <a href="/ianliu/pictures/" class="creditItem firstCredit lastCredit recommendeddig">
      <img src="<?= h($this_users['picture']); ?>" class="creditImg">
      <!--user img-->
      <span class="creditName"><?= h($this_users['name']); ?>
      </span>
      <!--username-->
    </a>
    </div>
    <?php
		//thisコンテンツがlikeされたものかの判別
		$this_liked = $dbh->prepare("select id from music_likes where like_user_id=:me AND like_contents_id=:request");
	    $this_liked->execute(array(":me"=>$_REQUEST['id'], ":request"=>$music['id']));
	    $this_likeds = $this_liked->fetch();
	?>
    <?php if(!empty($this_likeds)) { ?>
    <!-- if liked -->
    <div class="digLiked">
    <span class="likedName">
    <i class="icon-heart icon-black"></i>By
    <a href="user.php?id=<?= h($_REQUEST['id']); ?>"><?= h($prof['name']); ?></a>
    </span>
    <!--likedusername-->
    </div>
    <?php } ?>
    </li>
    <?php } ?>
    </div>
    </ul>
    </div>
    </div>
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
    <script src="js/jquery.colorbox.js"></script>
    <?php
	   $js_music_post = $dbh->prepare("select preview from music_contents where user_id=:request OR id in (select like_contents_id from music_likes where like_user_id = :request) ORDER BY created DESC");
	    $js_music_post->execute(array(":request"=>$_REQUEST['id']));
	?>
    <script type="text/javascript">
		$(document).ready(function(){
		
		<?php $i = 0; ?>
		<?php while($js_music = $js_music_post->fetch(PDO::FETCH_ASSOC)) { ?> 
		<?php $i++; ?>
			$("#jquery_jplayer_<?= $i ?>").jPlayer({
				cssSelectorAncestor: "#jp_container_<?= $i ?>",
				ready: function () {
					$(this).jPlayer("setMedia", {
						m4a: "<?= $js_music['preview']; ?>"
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
    <script>
		$(function(){
			$("form.like_form").submit(function(event) {
				event.preventDefault();
				var val = $(this).children("input[name='contents']").val();
				$.ajax({
					type: 'POST',
					url: 'like.php',
					data: {"contents": val},
					success: function(data) {
						var dt = data.split(",");
						var imgSrc = ($("#" + dt[0]).attr("src") == "img/digliked.png")? "img/diglike.png": "img/digliked.png";
						$("#" + dt[0]).attr("src", imgSrc);
						$("span#change" + dt[0]).text(dt[1]);
					
					}
				});
				return false;
			});
		});
	</script>
    <script>
		$(function(){
			$("form.follow").submit(function(event) {
				event.preventDefault();
				var val = $(this).children("input[name='follows']").val();
				$.ajax({
					type: 'POST',
					url: 'follow.php',
					data: {"users": val},
					success: function(data) {
						var imgSrc = ($("#follower").attr("src") == "img/follow1.png")? "img/following1.png": "img/follow1.png";
						var mouseov = ($("#follower").attr("onmouseover") == "this.src='img/followhover1.png'")? "this.src='img/unfollow1.png'": "this.src='img/followhover1.png'";
						var mouseou = ($("#follower").attr("onmouseout") == "this.src='img/follow1.png'")? "this.src='img/following1.png'": "this.src='img/follow1.png'";
						$("#follower").attr("src", imgSrc);
						$("#follower").attr("onmouseover", mouseov);
						$("#follower").attr("onmouseout", mouseou);
						info = data.split("@");
						$("div#follower_count").text(info[0]);
						$("div#follow_count").text(info[1]);
					}
				});
				return false;
			});
		});
	</script>
    <script>
		$(document).ready(function(){
			$(".iframe").colorbox({iframe:true,width:"28%",height:"80%",scrolling:"false"});	 
		});
	</script>
  </body>
</html>