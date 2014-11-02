<?php 
	
	require('common.php');
	
		
	if(!empty($_POST['edit_user'])) {
			require('request_edit.php');
		}
		    
      //get profile
    $stmt = $dbh->prepare("select id,name,picture,prof from users where id=:request");
    $stmt->execute(array(":request"=>$_REQUEST['id']));
    $prof = $stmt->fetch();
    
    
    $follow = $dbh->prepare("select * from users where id in (select follower_user_id from follows where follow_user_id=:request ORDER BY created DESC)");
    $follow->execute(array(":request"=>$_REQUEST['id']));

    //get session prof
    $stmt = $dbh->prepare("select name,prof,email,picture from users where id=:request");
    $stmt->execute(array(":request"=>$_SESSION['me']['id']));
    $s_prof = $stmt->fetch();
    
    
    //get contents count
    
    $m_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM music_contents WHERE user_id=:request OR id in (select like_contents_id from music_likes where like_user_id = :request);");
    $m_count->execute(array(":request"=>$_REQUEST['id']));
    $m_counts = $m_count->fetchColumn();
    
    //follower count
	$follower_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM follows WHERE follower_user_id=:follow;");
	$follower_count->execute(array(":follow"=>$_REQUEST['id']));
	$follower_counts = $follower_count->fetchColumn();
																						    
	//follow count
	$follow_count = $dbh->prepare("SELECT COUNT(id) AS CNT FROM follows WHERE follow_user_id=:follower;");
	$follow_count->execute(array(":follower"=>$_REQUEST['id']));
	$follow_counts = $follow_count->fetchColumn();	
	
	
	
    
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
    <link href='http://fonts.googleapis.com/css?family=Cookie' rel='stylesheet' type='text/css'>
    <!--webfont-->
    <link href="css/jquery.nailthumb.1.1.min.css" type="text/css" rel="stylesheet" />
    <link href="css/add_head.css" rel="stylesheet">
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
    <form action="" method="post" enctype="multipart/form-data" name="postin" />
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
      <input type="file" name="imageEdit" id="imageEdit"/>
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
           <a href="user.php?id=<?= $_SESSION['me']['id']; ?>" class="gactive">
              <i class="icon-user icon-white"></i>プロフィール
            </a>
          </li>
          <li>
            <a href="#editModal" data-toggle="modal" class="gactive">
              <i class="icon-cog icon-white"></i>編集
            </a>
          </li>
          <li>
            <a href="faq.html" class="gactive">
              <i class="icon-leaf icon-white"></i>ヘルプ
            </a>
          </li>
          <li>
            <a href="logout.php" class="gactive">
              <i class="icon-off icon-white"></i>ログアウト
            </a>
          </li>
        </ul>
      </li>
    </ul>
    </div>
    <!--/.nav-collapse -->
    </div>
    </div>
    </div>
    <div id="container">
    <div id="user-bar">
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
      <div class="user-stat-count"><?= h($m_counts); ?> 
      </div>
      <div class="user-stat-label">music 
      </div>
    </a>
    </div>
    <div class="user-stat">
    <a href="followers.php?id=<?= h($_REQUEST['id']); ?>">
      <div class="user-stat-count">
      <div id="follower_count"><?= h($follower_counts); ?> 
      </div>
      </div>
      <div class="user-stat-label">follower 
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
    <form method="post" action="" class="follow">
      <input type="hidden" value="<?= $_REQUEST['id']; ?>" name="follows">
      <?php
			$follow2 = $dbh->prepare("select id from follows where follow_user_id=:me AND follower_user_id=:you");
			$follow2->execute(array(":me"=>$_SESSION['me']['id'],":you"=>$_REQUEST['id']));
			$follow_in = $follow2->fetch();

			if(empty($follow_in)) {
		?>
      <input type="image" id="follower" src="http://digly.jp/img/follow1.png"style="display: inline-block;border-width:0px;border-style:None;" onmouseover="this.src='img/followhover1.png'" onmouseout="this.src='img/follow1.png'"/>
      <?php } else { ?>
      <input type="image" id="follower" src="http://digly.jp/img/following1.png"style="display: inline-block;border-width:0px;border-style:None;" onmouseover="this.src='img/unfollow1.png'" onmouseout="this.src='img/following1.png'"/>
      <?php } ?>
    </form>
    </div>
    </div>
    <?php } ?>

    <div id="share-links-user-wrap">
    <div id="share-links-user">
    <a href="http://twitter.com/share?url=http://www.digly.jp/user.php?id=<e4cdc1ee00f51a624b69834949df0241 />&text=ユーザーページ：<fe9a26b85fe9ec599672b8534281993b /> by digly.jp [URL]" target="_blank">
      <img src="img/twitter.png" />
    </a>
    <a href="http://www.facebook.com/sharer.php?s=100
		&amp;p[url]=http://www.digly.jp/user.php?id=<2af502d066fd03e52ca51f696e964d08 />&amp;
		p[title]=digly.jp&amp;p[summary]=[userpage]<fe1c3623801553dd06928785c7cc641c />&amp;m2w" target="_blank" rel="nofollow">
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
    <!-- main -->
    <div id="main" role="main">
    <ul id="tiles">
      <?php $i = 0; ?>
      <?php while($follows = $follow->fetch()) { ?>
      <?php 

      		$get_c  = $dbh->prepare("SELECT artwork FROM music_contents WHERE user_id=:g_id ORDER BY rand() LIMIT 3");
			$get_c->execute(array(":g_id"=>$follows['id']));
			$get_cs = $get_c->fetchall(PDO::FETCH_NUM);
			$get_p = array();

			foreach($get_cs as $get_photo) {
				$get_p[] = $get_photo[0];
				$i++;
			}
			
			function pict($pic) {
				if(empty($pic)) {
					echo 'img/no_img.png';
				} else {
					echo h($pic); 
				}
			}
           ?>
      <!-- items -->
      <li>
        <div class="digWrapper">
        <div class="digImageActionButtonWrapper">
        <div class="digHolder">
        <a href="#" class="digImageWrapper " style="background: #74385d;">
          <div class="digCredits         hasCondensedCredits">
          <a href="user.php?id=<?= h($follows['id']); ?>" class="creditItem firstCredit lastCredit recommendeddig">
            <!--user link-->
            <span class="creditName"><?= h($follows['name']); ?>
            </span>
            <!--user name-->
          </a>
          </div>
          <div class="fadeContainer" style="width:228px; height:228px;">
          <a href="">
            <!--user link-->
            <div class="peoplethumb1">
            <div style="float:left; position:relative;" class="peoplethumbcontents">
            <img src="<?= pict($follows['picture']); ?>" class="nailthumb150 square-thumb"/>
            </div>
            <!--user img-->
            <div style="float:left; position:relative;" class="peoplethumbcontents">
            <img src="<?= pict($get_p[0]); ?>" class="nailthumb70 square-thumb"/>
            </div>
            <!--contens img-->
            <div style="float:left; position:relative;" class="peoplethumbcontents">
            <img src="<?= pict($get_p[1]); ?>" class="nailthumb70 square-thumb"/>
            </div>
            <!--contens img-->
            </div>
            <div class="peoplethumb2">
            <div style="float:left; position:relative;" class="peoplethumbcontents">
            <img src="<?= pict($get_p[2]); ?>" class="nailthumb70 square-thumb"/>
            </div>
            <!--contens img-->
            <div style="float:left; position:relative;" class="peoplethumbcontents">
            <img src="<?= pict($get_p[3]); ?>" class="nailthumb70 square-thumb"/>
            </div>
            <!--contens img-->
            <div style="float:left; position:relative;" class="peoplethumbcontents">
            <img src="<?= pict($get_p[4]); ?>" class="nailthumb70 square-thumb"/>
            </div>
            <!--contens img-->
            </div>
          </a>
          </div>
        </a>
        </div>
        <?php 
					$i++;
					$join_id = 'follower'. $follows['id'];
				?>
        <!--follow部分-->
        <form name="like" method="post" action="" class="follow_people" style="margin:0px;">
          <input type="hidden" value="<?= $follows['id'] ?>" name="follows">
          <?php
							$follow_or = $dbh->prepare("select id from follows where follow_user_id=:you");
							$follow_or->execute(array(":you"=>$_SESSION['me']['id']));
							$follow_in = $follow_or->fetch();
						
						if(empty($follow_in)) {
						?>
          <input type="image" id="<?= h($join_id); ?>" src="http://digly.jp/img/follow2.png"style="display: inline-block;border-width:0px;border-style:None;" onmouseover="this.src='img/followhover2.png'" onmouseout="this.src='img/follow2.png'"/>
          <?php } else { ?>
          <input type="image" id="<?= h($join_id); ?>" src="http://digly.jp/img/following2.png"style="display: inline-block;border-width:0px;border-style:None;" onmouseover="this.src='img/unfollow2.png'" onmouseout="this.src='img/following2.png'"/>
          <?php } ?>
        </form>
      </li>
      <?php } ?>
    </ul>
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
      <p class="gallright">All Right Reserved @2013 Digly
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
    <script src="js/jquery.nailthumb.1.1.min.js"></script>
    <script type="text/javascript">
	(function ($){
	  $('#tiles').imagesLoaded(function() {
	    // Prepare layout options.
	    var options = {
	      itemWidth: 230, // Optional min width of a grid item
	      autoResize: true, // This will auto-update the layout when the browser window is resized.
	      container: $('#tiles'), // Optional, used for some extra CSS styling
	      offset: 15, // Optional, the distance between grid items
	      outerOffset: 20, // Optional the distance from grid to parent
	      flexibleWidth: 230 // Optional, the maximum width of a grid item
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
    <!--nailsthumb-->
    <script type="text/javascript">  
	    $(document).ready(function() {  
	        $('.nailthumb-edit').nailthumb({width:129,height:129,fitDirection:'top center'});
	        $('.nailthumb70').nailthumb({width:74,height:74}); //user-contentsimg
	        $('.nailthumb150').nailthumb({width:150,height:150,fitDirection:'top center'}); //user-userimg
	        $('.nailsthumbuser').nailthumb({width:140,height:140,fitDirection:'top center'});//top userimg
		});  
	</script>
    <!--editpreview-->
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
						$("#follower_count").text(info[0]);
						$("#follow_count").text(info[1]);
					}
				});
				return false;
			});
		});
	</script>
  </body>
</html>