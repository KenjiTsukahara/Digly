$(function(){
	//初期設定
	$("ul#f_clcarouselInner").css("width",150*$("#f_clcarouselInner li").size()+"px");//138*数
	$("ul#f_clcarouselInner li:last").prependTo("ul#f_clcarouselInner");//最後のカラムを先頭へ移動
	$("ul#f_clcarouselInner").css("margin-left","-138px");//-138px移動
	//戻るボタン
	$("#f_clcarouselPrev").click(function(){
		$("#f_clcarouselPrev,#f_clcarouselNext").hide();
		$("#f_clcarouselInner").animate({
		marginLeft:parseInt($("ul#f_clcarouselInner").css("margin-left"))+138+"px"
		},50,"swing",
		function(){
				$("ul#f_clcarouselInner").css("margin-left","-138px");
				$("ul#f_clcarouselInner li:last").prependTo("ul#f_clcarouselInner");
				$("#f_clcarouselPrev,#f_clcarouselNext").show();
		});
	});
	//進むボタン
	$("#f_clcarouselNext").click(function(){
		//$("#f_clcarouselPrev,#f_clcarouselNext").hide();
		$("ul#f_clcarouselInner").animate({
		marginLeft:parseInt($("ul#f_clcarouselInner").css("margin-left"))-138+"px"
		},50,"swing",
		function(){
				$("ul#f_clcarouselInner").css("margin-left","-138px");
				$("ul#f_clcarouselInner li:first").appendTo("ul#f_clcarouselInner");
				$("#f_clcarouselPrev,#f_clcarouselNext").show();
		});
	});
	//ロールオーバーー
	$("#f_clcarouselPrev").mouseover(function(){
		$("#f_clcarouselPrev img").attr("src","img/cl_prev.png");
	}).mouseout(function(){
		$("#f_clcarouselPrev img").attr("src","img/cl_prev.png");
	});
	$("#f_clcarouselNext").mouseover(function(){
		$("#f_clcarouselNext img").attr("src","img/cl_next.png");
	}).mouseout(function(){
		$("#f_clcarouselNext img").attr("src","img/cl_next.png");
	});
	//タイマー
	var timerID = setInterval(function(){
		$("#f_clcarouselNext").click();
	},2500);
	
	$("#f_clcarouselPrev img,#f_clcarouselNext img").click(function(){
		clearInterval(timerID);
	});
});