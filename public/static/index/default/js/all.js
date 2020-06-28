(function(a,b){$window=a(b),a.fn.lazyload=function(c){function f(){var b=0;d.each(function(){var c=a(this);if(e.skip_invisible&&!c.is(":visible"))return;if(!a.abovethetop(this,e)&&!a.leftofbegin(this,e))if(!a.belowthefold(this,e)&&!a.rightoffold(this,e))c.trigger("appear");else if(++b>e.failure_limit)return!1})}var d=this,e={threshold:0,failure_limit:0,event:"scroll",effect:"show",container:b,data_attribute:"original",skip_invisible:!0,appear:null,load:null};return c&&(undefined!==c.failurelimit&&(c.failure_limit=c.failurelimit,delete c.failurelimit),undefined!==c.effectspeed&&(c.effect_speed=c.effectspeed,delete c.effectspeed),a.extend(e,c)),$container=e.container===undefined||e.container===b?$window:a(e.container),0===e.event.indexOf("scroll")&&$container.bind(e.event,function(a){return f()}),this.each(function(){var b=this,c=a(b);b.loaded=!1,c.one("appear",function(){if(!this.loaded){if(e.appear){var f=d.length;e.appear.call(b,f,e)}a("<img />").bind("load",function(){c.hide().attr("src",c.data(e.data_attribute))[e.effect](e.effect_speed),b.loaded=!0;var f=a.grep(d,function(a){return!a.loaded});d=a(f);if(e.load){var g=d.length;e.load.call(b,g,e)}}).attr("src",c.data(e.data_attribute))}}),0!==e.event.indexOf("scroll")&&c.bind(e.event,function(a){b.loaded||c.trigger("appear")})}),$window.bind("resize",function(a){f()}),f(),this},a.belowthefold=function(c,d){var e;return d.container===undefined||d.container===b?e=$window.height()+$window.scrollTop():e=$container.offset().top+$container.height(),e<=a(c).offset().top-d.threshold},a.rightoffold=function(c,d){var e;return d.container===undefined||d.container===b?e=$window.width()+$window.scrollLeft():e=$container.offset().left+$container.width(),e<=a(c).offset().left-d.threshold},a.abovethetop=function(c,d){var e;return d.container===undefined||d.container===b?e=$window.scrollTop():e=$container.offset().top,e>=a(c).offset().top+d.threshold+a(c).height()},a.leftofbegin=function(c,d){var e;return d.container===undefined||d.container===b?e=$window.scrollLeft():e=$container.offset().left,e>=a(c).offset().left+d.threshold+a(c).width()},a.inviewport=function(b,c){return!a.rightofscreen(b,c)&&!a.leftofscreen(b,c)&&!a.belowthefold(b,c)&&!a.abovethetop(b,c)},a.extend(a.expr[":"],{"below-the-fold":function(c){return a.belowthefold(c,{threshold:0,container:b})},"above-the-top":function(c){return!a.belowthefold(c,{threshold:0,container:b})},"right-of-screen":function(c){return a.rightoffold(c,{threshold:0,container:b})},"left-of-screen":function(c){return!a.rightoffold(c,{threshold:0,container:b})},"in-viewport":function(c){return!a.inviewport(c,{threshold:0,container:b})},"above-the-fold":function(c){return!a.belowthefold(c,{threshold:0,container:b})},"right-of-fold":function(c){return a.rightoffold(c,{threshold:0,container:b})},"left-of-fold":function(c){return!a.rightoffold(c,{threshold:0,container:b})}})})(jQuery,window);
function getBrowser() {
	var browser = {msie : false,firefox : false,opera : false,safari : false,chrome : false,netscape : false,appname : '未知',version : ''},
	userAgent = window.navigator.userAgent.toLowerCase();
	if (/(msie|firefox|opera|chrome|netscape)\D+(\d[\d.]*)/.test(userAgent)) {
		browser[RegExp.$1] = true;
		browser.appname = RegExp.$1;
		browser.version = RegExp.$2;
	} else if (/version\D+(\d[\d.]*).*safari/.test(userAgent)) {
		browser.safari = true;
		browser.appname = 'safari';
		browser.version = RegExp.$2;
	}
	return browser;
}
window.ppAjax = {
	alert : function (data) {
		window.ppData = data = toJson(data);
		if (window.ppExit)
			return;
	},
	submit : function (selector, callback) {
		$(selector).submit(function () {
			ppAjax.post($(this).attr("action"), $(this).serialize(), callback);
			return false;
		});
	},
	post : function (url, param, callback) {
		$.ajax({
			type : "POST",
			cache : false,
			url : url,
			data : param,
			success : callback,
			error : function (html) {
				layer.alert("提交数据失败，代码:" + html.status + "，请稍候再试", {icon : 0,shade : 0.6});
			}
		});
	}
};
function toJson(data) {
	var json = {};
	try {
		json = eval("(" + data + ")");
		if (json.kp_error) {
			ppAjax.debug(json);
			window.ppExit = true;
		} else {
			window.ppExit = false;
		}
	} catch (e) {
		alert(data);
	}
	return json;
}
//jQuery的cookie扩展
$.cookie = function (name, value, options) {
	if (typeof value != 'undefined') {
		options = options || {};
		if (value === null) {
			value = '';
			options.expires = -1;
		}
		var expires = '';
		if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
			var date;
			if (typeof options.expires == 'number') {
				date = new Date();
				date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
			} else {
				date = options.expires;
			}
			expires = '; expires=' + date.toUTCString();
		}
		var path = options.path ? '; path=' + options.path : '';
		var domain = options.domain ? '; domain=' + options.domain : '';
		var secure = options.secure ? '; secure' : '';
		document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
	} else {
		var cookieValue = null;
		if (document.cookie && document.cookie != '') {
			var cookies = document.cookie.split(';');
			for (var i = 0; i < cookies.length; i++) {
				var cookie = jQuery.trim(cookies[i]);
				if (cookie.substring(0, name.length + 1) == (name + '=')) {
					cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
					break;
				}
			}
		}
		return cookieValue;
	}
};
$.fn.scrollFix = function (fix) {
	var position = function (element) {
		var top = element.position().top + fix,
		pos = element.css("position"),
		wd = element.width();
		$(window).scroll(function () {
			var scrolls = $(this).scrollTop();
			if (scrolls > top) {
				if (window.XMLHttpRequest) {
					element.css({'z-index' : 999,position : "fixed",width : wd,top : 0});
				} else {
					element.css({top : scrolls});
				}
			} else {
				element.css({position : pos,top : top});
			}
		});
	};
	return $(this).each(function () {
		position($(this));
	});
}
$(document).ready(function () {
	$('#pro_orderby a').click(function(){
		$.cookie('order', $(this).attr('data'));
		window.location.reload();
	});
	$("#search_form").submit(function(){
		var keyword = $("#search_keyword").val();
		if (!keyword) {
			layer.tips('搜索词不能为空！', '#search_keyword',{tips: 3});
			return false;
		}
	});
	$('img[data-original]').lazyload({});
	window.ctf_form_one = false;
	$("#ctf_submit").click(function(){
		if (window.ctf_form_one){
			return false;
		}
		if($("#ctf_content").length < 1){
			return false;
		}
		var cont = $("#ctf_content");
		if($("#ctf_author").length < 1) {
			var pp_author = '游客';
		}else{
			var pp_author = $("#ctf_author").val();
		}
		var browser = getBrowser();
		if (!browser.firefox){
			$("#ctf_submit").attr("disabled", "disabled");
			setTimeout(function () {
				if (!browser.firefox)
					$("#ctf_submit").removeAttr("disabled");
				window.ctf_form_one = false;
			}, 2000);
		}
		if (!pp_author) {
			layer.msg('请填写昵称！',function(){});
			return false;
		}
		if (cont.val().length < 6) {
			layer.msg('评论内容太短至少6字符！',function(){});
			return false;
		}
		window.ctf_form_one = true;
		var _form = $('#ctf_form');
		$.post(_form.attr("action"), _form.serialize(),function(data){
			try {
				console.log(data);
				var json = eval("(" + data + ")");
				if (json.err==0) {
					layer.msg(json.msg);
					cont.val('');
					setTimeout(function () {
						location.reload();
					}, 1000);
				} else {
					layer.msg(json.msg,function(){});
					window.ctf_form_one = false;
				}
			}catch(e){
			}
		});
		return false;
	});
	// 跳转到评论框
	(function () {
		var Uarr = location.href.split('#');
		if (Uarr[1] == "ctf")
			$("html,body").animate({
				scrollTop : $("#ctf").offset().top
			});
	})();
	//下单啦
	var isuer = $('#show_order').attr('data');
	if (isuer > 0) {
		$('#order_book :input').attr("disabled", false);
	} else {
		$('#order_book :input').attr("disabled", true);
	}
	var book_ordernum = $('#book_ordernum'),
	book_price = $('#book_price'),
	book_totalprice = $('#book_totalprice');
	//改变数量
	book_ordernum.change(function () {
		t_totalprice = $(this).val() * book_price.val();
		book_totalprice.val(t_totalprice);
	});
});
$(document).ready(function () {
	$("#media .item").hover(function () {
		$(this).addClass("item-cur");
	}, function () {
		$(this).removeClass("item-cur");
	});
	$(window).scroll(function () {
		if ($(window).scrollTop() > 100) {
			$("#back-to-top").fadeIn(1500);
		} else {
			$("#back-to-top").fadeOut(1500);
		}
	});
	$("#back-to-top").click(function () {
		$('body,html').animate({
			scrollTop : 0
		}, 1000);
		return false;
	});
	$('li.dropdown').mouseover(function() {   
		 $(this).addClass('open');
	}).mouseout(function() {
		$(this).removeClass('open');
	});
	$(".dropdown-toggle").click(function(){
		if($(this).attr('href')) window.location = $(this).attr('href');
	});
});