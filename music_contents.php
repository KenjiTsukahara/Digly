<?php
	
	require('common.php');
	
    //get contents
    $mpost = $dbh->prepare("select id,trackName,artistName,caption,user_id,trackview,artwork,f_m,created,preview from music_contents where id=:request");
    $mpost->execute(array(":request"=>$_REQUEST['id']));
    $music = $mpost->fetch();
    
     //get contents
    $me = $dbh->prepare("select name from users where id=:request");
    $me->execute(array(":request"=>$_SESSION['me']['id']));
    $me1 = $me->fetch();
    
     //get user
    $stmt = $dbh->prepare("select id,name,picture from users where id=:request");
    $stmt->execute(array(":request"=>$music['user_id']));
    $prof = $stmt->fetch();
    

	$like_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM music_likes WHERE like_contents_id=:c_id;");
	$like_count->execute(array(":c_id"=>$music['id']));
	$like_counts = $like_count->fetchColumn();	
	
	$comme_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM music_comments WHERE contents_id=:c_id;");
	$comme_count->execute(array(":c_id"=>$music['id']));
	$comme_counts = $comme_count->fetchColumn();
	
	//get comment
    $music_c = $dbh->prepare("select id,user_id,comment,contents_id from music_comments where contents_id=:request");
    $music_c->execute(array(":request"=>$music['id']));
    
    function getTime($create_time) {
	    
		date_default_timezone_set('Asia/Tokyo');
	    $unix   = strtotime($create_time);
	    $now    = time();
	    $diff_sec   = $now - $unix;
	
	    if($diff_sec < 60){
	        $time2   = $diff_sec;
	        $time2 = floor($time2);
	        $unit   = "秒前";
	        $time = $time2.$unit;
	    }
	    elseif($diff_sec < 3600){
	        $time2   = $diff_sec/60;
	        $time2 = floor($time2);
	        $unit   = "分前";
	        $time = $time2.$unit;
	    }
	    elseif($diff_sec < 86400){
	        $time2   = $diff_sec/3600;
	        $time2 = floor($time2);
	        $unit   = "時間前";
	        $time = $time2.$unit;
	    }else{
	        $time2   = $diff_sec/86400;
	        $time2 = floor($time2);
	        $unit   = "日前";
	        $time = $time2.$unit;
	
	    }
    
    return $time;
	    	    
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
    <link href="css/jquery.nailthumb.1.1.min.css" type="text/css" rel="stylesheet" />
    <link href="css/jquery.colorbox.css" type="text/css" rel="stylesheet" />
  </head>
  <body style="background-color:#fff; min-height:auto; padding-top:0px;　overflow-y: hidden;">
    <div id="container">
    <div class="row-fluid" style="width:300px; margin:0 auto;padding-top:20px; padding-bottom:20px;">
    <div class="span12 contentswapper">
    <!--全体幅-->
    <div class="row-fluid">
    <div class="span8" style="width:300px;height:200px;margin:0 auto;">
    <!--左幅-->
    <div class="contents_left_photo" style="width:200px; height: 200px; padding-left:50px;">
    <img style="width:200px; height: 200px;" src="<?= h($music['artwork']); ?>" class="contents_img" id="target">
    </img>
    <!--Default Img-->
    </div>
    </div>
    <div class="span4" style="padding-top:20px; padding-right:10px; width:300px;float:left;">
    <!--右幅 metadata-->
    <div class="row-fluid" style="margin:0 auto;">
    <div class="span12" style="background:;">
    <div class="row-fluid">
    <div class="contents_user">
    <!--ユーザ欄-->
    <div class="contents_userimg">
    <img class="nailthumb-contents_user square-thumb-contents-user" src="<?= h($prof['picture']); ?>">
    </img>
    </div>
    <div class="contents_username_box">
    <span class="contents_username1">
      <a href="user.php?id=<?= h($prof['id']); ?>"><?= h($prof['name']); ?></a>
    </span>
    <br>
      <span class="contents_username2">
        <i class="icon-time"></i>
        <span><?= getTime($music['created']); ?></span>
      </span>
      </div>
      </div>
      </div>
      <div class="row-fluid">
      <!--曲名-->
      <div class="contents_brand">
      <p class="contents_brand_name1">Title</p>
      <span class="contents_brand_name2"><?= h($music['trackName']); ?></span>
      </div>
      </div>
      <div class="row-fluid">
      <!--アーティスト名-->
      <div class="contents_artist">
      <p class="contents_brand_artist1">Artist</p>
      <span class="contents_brand_artist2"><?= h($music['artistName']); ?></span>
      </div>
      </div>
      <div class="row-fluid">
      <!--キャプション-->
      <div class="contents_caption">
      <span><?= h($music['caption']); ?></span>
      </div>
      </div>
      <?php if($_SESSION['me']['id'] !== $music['user_id']) { ?>
      <div class="row-fluid">
      <!--ソーシャルボタン-->
      <div class="contents_share_box">
      <div class="contents_share">
      <form name="like" method="post" action="" class="like_form">
        <input type="hidden" value="<?= $music['id'] . "," . $music['f_m']; ?>" name="contents">
        <?php
			$like = $dbh->prepare("select id from music_likes where like_user_id=:me AND like_contents_id=:request");
			$like->execute(array(":me"=>$_SESSION['me']['id'],":request"=>$music['id']));
			$likes = $like->fetch();
		
		if(empty($likes)) {
		?>
        <input type="image" id=
        <?= $music['id']; ?> src="img/digliked.png" >
        <?php } else { ?>
        <input type="image" id=
        <?= $music['id']; ?> src="img/diglike.png" >
        <?php } ?>
      </form>
      </div>
      </div>
      </div>
      <?php } ?>
      <div class="row-fluid">
      <!--ソーシャルボタン-->
      <div class="contents_share_box">
      <div class="contents_share">
      <a href="http://twitter.com/share?url=http://www.digly.jp/music_contents.php?id=<e3fde5fdab397d222c323cb8b1c37130 />&text=music contents!!：<983932d873e65eb6e0b8d02c6b09731d /> by digly.jp [URL]" target="_blank">
        <img alt="Share-icon-twitter" src="img/twitter.png">
      </a>
      <a href="http://www.facebook.com/sharer.php?s=100
&amp;p[url]=http://www.digly.jp/music_contents.php?id=<71e08e2f375ae644263b224ad5ad7eab />&amp;
p[title]=digly.jp&amp;p[summary]=[userpage]<fe5b105521d316985bffba8a934b0f5f />&amp;m2w" target="_blank">
        <img alt="Share-icon-facebook" src="img/facebook.png">
      </a>
      <a href="http://www.youtube.com/results?search_query=<?= $music['artistName']. "+" . $music['trackName']; ?>" target="_blank">
        <img alt="Share-icon-facebook" src="img/youtube2.png">
      </a>
      <a href="<?= $music['trackview']; ?>" target="_blank">
        <img alt="Share-icon-facebook" src="img/itunes.png">
      </a>
      </div>
      </div>
      </div>
      <div class="row-fluid">
      <!--スタッツ欄-->
      <div class="contents_Meta">
      <p class="icon-blue">
        <i class="icon-heart" title="heart"></i>
      </p>
      <span class="socialItem" id="<?= "change". h($music['id']); ?>"><?= h($like_counts); ?></span>
	  &nbsp; 
      <p class="icon-blue">
        <i class="icon-comment" title="heart"></i>
      </p>
      <span class="socialItem" id="change_c"><?= h($comme_counts); ?></span>
      <!--comment count-->
      <div class="digSocialMetajp_fc">
      <div id="jquery_jplayer_1" class="jp-jplayer">
      </div>
      <div id="jp_container_1" class="jp-audio" style="color:transpaernt;background-color:transparent;border:none;">
      <div class="jp-type-single">
      <div class="jp-gui jp-interface" style="display:block;">
      <a href="javascript:;" class="jp-play" tabindex="1">
        <i class="icon-play icon-blue icon-size24"></i>
      </a>
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
      </div>
      </div>
      <div class="row-fluid">
      <!--追加コメント一覧-->
      <div class="contents_comment_top">
      <p class="contents_brand_name1">Comments</p>
      </div>
      </div>
      <?php
		while($comment = $music_c->fetch()) { 		
				    	
			$stmt = $dbh->prepare("select id,name,picture from users where id=:request");
		    $stmt->execute(array(":request"=>$comment['user_id']));
		    $commenter = $stmt->fetch();
		?>
      <div class="row-fluid" id="join-comment">
      <!--コメント-->
      <div class="contents_comment_wapper">
      <div class="span12 contents_comment_box">
      <div class="span2">
      <div class="contents_comment_userimg">
      <img class="nailthumb-contents_user square-thumb-contents-user" src="<?= h($commenter['picture'])?>">
      </img>
      </div>
      </div>
      <div class="span10">
      <div class="contents_comment_username">
      <a href="user.php?id=<?= h($commenter['id']); ?>"><?= h($commenter['name'])?></a>
      <span><?= h($comment['comment'])?></span>
      </div>
      </div>
      </div>
      </div>
      <?php } ?>
      <!--コメント追加-->
      <div class="row-fluid">
      <div class="span12" style="background:;">
      <span class="contents_brand_name1">コメントする</span>
    </br>
    <form method="post" action="" id="comment-form" enctype="multipart/form-data">
      <input type="hidden" value="<?= h($music['id']). "," . h($music['f_m']); ?>" name="c_ajax">
      <textarea rows="1" class="span12" name="comment_text" id="get_area"></textarea>
      <input type="image" value="POST">
    </form>
    </div>
    </div>
    </div>
    </div>
    </div>
    <!-- /container -->
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.wookmark.js"></script>
    <script src="js/jquery.imagesloaded.js"></script>
    <script src="js/jquery.scrollUp.js"></script>
    <script src="js/jquery.colorbox.js"></script>
    <script src="js/jquery.nailthumb.1.1.min.js"></script>
    <script src="js/jquery.jplayer.min.js"></script>
    <script type="text/javascript">
		$(document).ready(function(){
			$("#jquery_jplayer_1").jPlayer({
				cssSelectorAncestor: "#jp_container_1",
				ready: function () {
					$(this).jPlayer("setMedia", {
						m4a: "<?= $music['preview']; ?>"
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
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {  
		    $('.nailthumb-fc').nailthumb({width:100,height:100,fitDirection:'center center'});
		    $('.nailthumb-contents_user').nailthumb({width:40,height:40,fitDirection:'top center'});
		});  
	</script>
    <script>
		$(function(){
			$("form#comment-form").submit(function(event) {
				event.preventDefault();
				var val = $(this).children("input[name='c_ajax']").val();
				var tiesto = document.getElementById('get_area').value;
				$.ajax({
					type: 'POST',
					url: 'comment.php',
					data: {"contents": val, 
					"comments": tiesto},
					success: function(data) {
						$("span#change_c").text(data);
						location.reload();
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
  </body>
</html>