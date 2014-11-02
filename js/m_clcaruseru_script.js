$(function(){
	//初期設定
	$("ul#m_clcarouselInner").css("width",150*$("#m_clcarouselInner li").size()+"px");//138*数
	$("ul#m_clcarouselInner li:last").prependTo("ul#m_clcarouselInner");//最後のカラムを先頭へ移動
	$("ul#m_clcarouselInner").css("margin-left","-138px");//-138px移動
	//戻るボタン
	$("#m_clcarouselPrev").click(function(){
		$("#m_clcarouselPrev,#m_clcarouselNext").hide();
		$("#m_clcarouselInner").animate({
		marginLeft:parseInt($("ul#m_clcarouselInner").css("margin-left"))+138+"px"
		},50,"swing",
		function(){
				$("ul#m_clcarouselInner").css("margin-left","-138px");
				$("ul#m_clcarouselInner li:last").prependTo("ul#m_clcarouselInner");
				$("#m_clcarouselPrev,#m_clcarouselNext").show();
		});
	});
	//進むボタン
	$("#m_clcarouselNext").click(function(){
		$("#m_clcarouselPrev,#m_clcarouselNext").hide();
		$("ul#m_clcarouselInner").animate({
		marginLeft:parseInt($("ul#m_clcarouselInner").css("margin-left"))-138+"px"
		},50,"swing",
		function(){
				$("ul#m_clcarouselInner").css("margin-left","-138px");
				$("ul#m_clcarouselInner li:first").appendTo("ul#m_clcarouselInner");
				$("#m_clcarouselPrev,#m_clcarouselNext").show();
		});
	});
	//ロールオーバーー
	$("#m_clcarouselPrev").mouseover(function(){
		$("#m_clcarouselPrev img").attr("src","img/cl_prev.png");
	}).mouseout(function(){
		$("#m_clcarouselPrev img").attr("src","img/cl_prev.png");
	});
	$("#m_clcarouselNext").mouseover(function(){
		$("#m_clcarouselNext img").attr("src","img/cl_next.png");
	}).mouseout(function(){
		$("#m_clcarouselNext img").attr("src","img/cl_next.png");
	});
	//タイマー
	var timerID = setInterval(function(){
		$("#m_clcarouselNext").click();
	},2500);
	
	$("#m_clcarouselPrev img,#m_clcarouselNext img").click(function(){
		clearInterval(timerID);
	});
});