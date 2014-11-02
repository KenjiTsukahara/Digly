$(function(){
	//初期設定
	$("ul#carouselInner").css("width",138*$("#carouselInner li").size()+"px");//138*数
	$("ul#carouselInner li:last").prependTo("ul#carouselInner");//最後のカラムを先頭へ移動
	$("ul#carouselInner").css("margin-left","-138px");//-138px移動
	//戻るボタン
	$("#carouselPrev").click(function(){
		$("#carouselPrev,#carouselNext").hide();
		$("#carouselInner").animate({
		marginLeft:parseInt($("ul#carouselInner").css("margin-left"))+138+"px"
		},50,"swing",
		function(){
				$("ul#carouselInner").css("margin-left","-138px");
				$("ul#carouselInner li:last").prependTo("ul#carouselInner");
				$("#carouselPrev,#carouselNext").show();
		});
	});
	//進むボタン
	$("#carouselNext").click(function(){
		$("#carouselPrev,#carouselNext").hide();
		$("ul#carouselInner").animate({
		marginLeft:parseInt($("ul#carouselInner").css("margin-left"))-138+"px"
		},50,"swing",
		function(){
				$("ul#carouselInner").css("margin-left","-138px");
				$("ul#carouselInner li:first").appendTo("ul#carouselInner");
				$("#carouselPrev,#carouselNext").show();
		});
	});
	//ロールオーバーー
	$("#carouselPrev").mouseover(function(){
		$("#carouselPrev img").attr("src","img/prev_btn_f2.jpg");
	}).mouseout(function(){
		$("#carouselPrev img").attr("src","img/prev_btn.jpg");
	});
	$("#carouselNext").mouseover(function(){
		$("#carouselNext img").attr("src","img/next_btn_f2.jpg");
	}).mouseout(function(){
		$("#carouselNext img").attr("src","img/next_btn.jpg");
	});
	//タイマー
	var timerID = setInterval(function(){
		$("#carouselNext").click();
	},2500);
	
	$("#carouselPrev img,#carouselNext img").click(function(){
		clearInterval(timerID);
	});
});