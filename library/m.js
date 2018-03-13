/***********************导航栏变色****************************/
function changeNav(){
	var mUrl = location.href;
	if(mUrl.indexOf("mIndex") != -1){
		$(".footer li").eq(0).find("img").attr("class","gray-on");
                $(".footer li").eq(0).find("p").css("color","#E0B55E");
	}else if(mUrl.indexOf("mGoodsClass") != -1){
		$(".footer li").eq(1).find("img").attr("class","gray-on");
                $(".footer li").eq(1).find("p").css("color","#E0B55E");
	}else if(mUrl.indexOf("mNeed") != -1){
		$(".footer li").eq(2).find("img").attr("class","gray-on");
                $(".footer li").eq(2).find("p").css("color","#E0B55E");
	}else if(mUrl.indexOf("mBuyCar") != -1){
		$(".footer li").eq(3).find("img").attr("class","gray-on");
                $(".footer li").eq(3).find("p").css("color","#E0B55E");
	}else if(mUrl.indexOf("mUser") != -1){
		$(".footer li").eq(4).find("img").attr("class","gray-on");
                $(".footer li").eq(4).find("p").css("color","#E0B55E");
	}
}
/***************************菜单显隐********************************/
function nav(){
	$("#nav-meun-btn").on("click",function(){
		$(".nav-meun-more").toggle();
		var this_arrow = $(this).find("img");
		if(this_arrow.hasClass("rotate1")){
			this_arrow.removeClass("rotate1");
			 this_arrow.addClass("rotate");
		}else{
			this_arrow.removeClass("rotate"); 
            this_arrow.addClass("rotate1");
		}
	});
}
/**
* 写cookies
* @param name 名字
* @param value 值
* @author He Hui
* */
function setCookie(name,value)
{
    var exp = new Date();
    exp.setTime(exp.getTime() + 10*60*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
/**
* 读取cookies
* @param name 名字
* @author He Hui
* */
function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg)) return unescape(arr[2]);
    else return null;
}
/**
* 删除cookies
* @param name 名字
* @author He Hui
* */
function delCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}
/*****************************回退******************************/
function windowBack(){
    window.history.back(-1);
}
/***************************自动加载***************************/
/**
 * dropload
 * 西门(http://ons.me/526.html)
 * 0.9.1(161205)
 */

;(function($){
    'use strict';
    var win = window;
    var doc = document;
    var $win = $(win);
    var $doc = $(doc);
    $.fn.dropload = function(options){
        return new MyDropLoad(this, options);
    };
    var MyDropLoad = function(element, options){
        var me = this;
        me.$element = element;
        // 上方是否插入DOM
        me.upInsertDOM = false;
        // loading状态
        me.loading = false;
        // 是否锁定
        me.isLockUp = false;
        me.isLockDown = false;
        // 是否有数据
        me.isData = true;
        me._scrollTop = 0;
        me._threshold = 0;
        me.init(options);
    };

    // 初始化
    MyDropLoad.prototype.init = function(options){
        var me = this;
        me.opts = $.extend(true, {}, {
            scrollArea : me.$element,                                            // 滑动区域
            domUp : {                                                            // 上方DOM
                domClass   : 'dropload-up',
                domRefresh : '<div class="dropload-refresh">↓下拉刷新</div>',
                domUpdate  : '<div class="dropload-update">↑释放更新</div>',
                domLoad    : '<div class="dropload-load"><span class="loading"></span>加载中...</div>'
            },
            domDown : {                                                          // 下方DOM
                domClass   : 'dropload-down',
                domRefresh : '<div class="dropload-refresh">↑上拉加载更多</div>',
                domLoad    : '<div class="dropload-load"><span class="loading"></span>加载中...</div>',
                domNoData  : '<div class="dropload-noData">暂无数据</div>'
            },
            autoLoad : true,                                                     // 自动加载
            distance : 50,                                                       // 拉动距离
            threshold : '',                                                      // 提前加载距离
            loadUpFn : '',                                                       // 上方function
            loadDownFn : ''                                                      // 下方function
        }, options);

        // 如果加载下方，事先在下方插入DOM
        if(me.opts.loadDownFn != ''){
            me.$element.append('<div class="'+me.opts.domDown.domClass+'">'+me.opts.domDown.domRefresh+'</div>');
            me.$domDown = $('.'+me.opts.domDown.domClass);
        }

        // 计算提前加载距离
        if(!!me.$domDown && me.opts.threshold === ''){
            // 默认滑到加载区2/3处时加载
            me._threshold = Math.floor(me.$domDown.height()*1/3);
        }else{
            me._threshold = me.opts.threshold;
        }

        // 判断滚动区域
        if(me.opts.scrollArea == win){
            me.$scrollArea = $win;
            // 获取文档高度
            me._scrollContentHeight = $doc.height();
            // 获取win显示区高度  —— 这里有坑
            me._scrollWindowHeight = doc.documentElement.clientHeight;
        }else{
            me.$scrollArea = me.opts.scrollArea;
            me._scrollContentHeight = me.$element[0].scrollHeight;
            me._scrollWindowHeight = me.$element.height();
        }
        fnAutoLoad(me);

        // 窗口调整
        $win.on('resize',function(){
            clearTimeout(me.timer);
            me.timer = setTimeout(function(){
                if(me.opts.scrollArea == win){
                // 重新获取win显示区高度
                me._scrollWindowHeight = win.innerHeight;
                }else{
                    me._scrollWindowHeight = me.$element.height();
                }
                fnAutoLoad(me);
            },150);
            
        });

        // 绑定触摸
        me.$element.on('touchstart',function(e){
            if(!me.loading){
                fnTouches(e);
                fnTouchstart(e, me);
            }
        });
        me.$element.on('touchmove',function(e){
            if(!me.loading){
                fnTouches(e, me);
                fnTouchmove(e, me);
            }
        });
        me.$element.on('touchend',function(){
            if(!me.loading){
                fnTouchend(me);
            }
        });

        // 加载下方
        me.$scrollArea.on('scroll',function(){
            me._scrollTop = me.$scrollArea.scrollTop();

            // 滚动页面触发加载数据
            if(me.opts.loadDownFn != '' && !me.loading && !me.isLockDown && (me._scrollContentHeight - me._threshold) <= (me._scrollWindowHeight + me._scrollTop)){
                loadDown(me);
            }
        });
    };

    // touches
    function fnTouches(e){
        if(!e.touches){
            e.touches = e.originalEvent.touches;
        }
    }

    // touchstart
    function fnTouchstart(e, me){
        me._startY = e.touches[0].pageY;
        // 记住触摸时的scrolltop值
        me.touchScrollTop = me.$scrollArea.scrollTop();
    }

    // touchmove
    function fnTouchmove(e, me){
        me._curY = e.touches[0].pageY;
        me._moveY = me._curY - me._startY;

        if(me._moveY > 0){
            me.direction = 'down';
        }else if(me._moveY < 0){
            me.direction = 'up';
        }

        var _absMoveY = Math.abs(me._moveY);

        // 加载上方
        if(me.opts.loadUpFn != '' && me.touchScrollTop <= 0 && me.direction == 'down' && !me.isLockUp){
            e.preventDefault();

            me.$domUp = $('.'+me.opts.domUp.domClass);
            // 如果加载区没有DOM
            if(!me.upInsertDOM){
                me.$element.prepend('<div class="'+me.opts.domUp.domClass+'"></div>');
                me.upInsertDOM = true;
            }
            
            fnTransition(me.$domUp,0);

            // 下拉
            if(_absMoveY <= me.opts.distance){
                me._offsetY = _absMoveY;
                // todo：move时会不断清空、增加dom，有可能影响性能，下同
                me.$domUp.html(me.opts.domUp.domRefresh);
            // 指定距离 < 下拉距离 < 指定距离*2
            }else if(_absMoveY > me.opts.distance && _absMoveY <= me.opts.distance*2){
                me._offsetY = me.opts.distance+(_absMoveY-me.opts.distance)*0.5;
                me.$domUp.html(me.opts.domUp.domUpdate);
            // 下拉距离 > 指定距离*2
            }else{
                me._offsetY = me.opts.distance+me.opts.distance*0.5+(_absMoveY-me.opts.distance*2)*0.2;
            }

            me.$domUp.css({'height': me._offsetY});
        }
    }

    // touchend
    function fnTouchend(me){
        var _absMoveY = Math.abs(me._moveY);
        if(me.opts.loadUpFn != '' && me.touchScrollTop <= 0 && me.direction == 'down' && !me.isLockUp){
            fnTransition(me.$domUp,300);

            if(_absMoveY > me.opts.distance){
                me.$domUp.css({'height':me.$domUp.children().height()});
                me.$domUp.html(me.opts.domUp.domLoad);
                me.loading = true;
                me.opts.loadUpFn(me);
            }else{
                me.$domUp.css({'height':'0'}).on('webkitTransitionEnd mozTransitionEnd transitionend',function(){
                    me.upInsertDOM = false;
                    $(this).remove();
                });
            }
            me._moveY = 0;
        }
    }

    // 如果文档高度不大于窗口高度，数据较少，自动加载下方数据
    function fnAutoLoad(me){
        if(me.opts.loadDownFn != '' && me.opts.autoLoad){
            if((me._scrollContentHeight - me._threshold) <= me._scrollWindowHeight){
                loadDown(me);
            }
        }
    }

    // 重新获取文档高度
    function fnRecoverContentHeight(me){
        if(me.opts.scrollArea == win){
            me._scrollContentHeight = $doc.height();
        }else{
            me._scrollContentHeight = me.$element[0].scrollHeight;
        }
    }

    // 加载下方
    function loadDown(me){
        me.direction = 'up';
        me.$domDown.html(me.opts.domDown.domLoad);
        me.loading = true;
        me.opts.loadDownFn(me);
    }

    // 锁定
    MyDropLoad.prototype.lock = function(direction){
        var me = this;
        // 如果不指定方向
        if(direction === undefined){
            // 如果操作方向向上
            if(me.direction == 'up'){
                me.isLockDown = true;
            // 如果操作方向向下
            }else if(me.direction == 'down'){
                me.isLockUp = true;
            }else{
                me.isLockUp = true;
                me.isLockDown = true;
            }
        // 如果指定锁上方
        }else if(direction == 'up'){
            me.isLockUp = true;
        // 如果指定锁下方
        }else if(direction == 'down'){
            me.isLockDown = true;
            // 为了解决DEMO5中tab效果bug，因为滑动到下面，再滑上去点tab，direction=down，所以有bug
            me.direction = 'up';
        }
    };

    // 解锁
    MyDropLoad.prototype.unlock = function(){
        var me = this;
        // 简单粗暴解锁
        me.isLockUp = false;
        me.isLockDown = false;
        // 为了解决DEMO5中tab效果bug，因为滑动到下面，再滑上去点tab，direction=down，所以有bug
        me.direction = 'up';
    };

    // 无数据
    MyDropLoad.prototype.noData = function(flag){
        var me = this;
        if(flag === undefined || flag == true){
            me.isData = false;
        }else if(flag == false){
            me.isData = true;
        }
    };

    // 重置
    MyDropLoad.prototype.resetload = function(){
        var me = this;
        if(me.direction == 'down' && me.upInsertDOM){
            me.$domUp.css({'height':'0'}).on('webkitTransitionEnd mozTransitionEnd transitionend',function(){
                me.loading = false;
                me.upInsertDOM = false;
                $(this).remove();
                fnRecoverContentHeight(me);
            });
        }else if(me.direction == 'up'){
            me.loading = false;
            // 如果有数据
            if(me.isData){
                // 加载区修改样式
                me.$domDown.html(me.opts.domDown.domRefresh);
                fnRecoverContentHeight(me);
                fnAutoLoad(me);
            }else{
                // 如果没数据
                me.$domDown.html(me.opts.domDown.domNoData);
            }
        }
    };

    // css过渡
    function fnTransition(dom,num){
        dom.css({
            '-webkit-transition':'all '+num+'ms',
            'transition':'all '+num+'ms'
        });
    }
})(window.Zepto || window.jQuery);

//日期控件
/**
 @Name : jeDate v6.0.2 日期控件
 @Author: chen guojun
 @Date: 2017-11-02
 @QQ群：516754269
 @官网：http://www.jemui.com/ 或 https://github.com/singod/jeDate
 */
!function(a,b){"function"==typeof define&&define.amd?define(["jquery"],b):"object"==typeof exports?module.exports=b():a.jeDate=b()}(this,function(){function DateTime(){var a=new Date,b=this;b.reDate=function(){return new DateTime},b.GetValue=function(){return a},b.GetFullYear=function(){return a.getFullYear()},b.GetMonth=function(){return a.getMonth()+1},b.GetHours=function(){return a.getHours()},b.GetDate=function(){return a.getDate()},b.GetMinutes=function(){return a.getMinutes()},b.GetSeconds=function(){return a.getSeconds()}}function jeDate(a,b){this.opts=b,this.valCell=a,this.format=this.opts.format,this.initdates()}var jet,doc,regymdzz,gr,regymd,parseInt,config,searandom,jedfn,jefix,matArr;return $&&$.fn&&$.fn.jquery?(jet={},doc=document,regymdzz="YYYY|MM|DD|hh|mm|ss|zz",gr=/\-/g,regymd="YYYY|MM|DD|hh|mm|ss|zz".replace("|zz",""),parseInt=function(a){return window.parseInt(a,10)},config={skinCell:"jedateblue",language:{name:"cn",month:["01","02","03","04","05","06","07","08","09","10","11","12"],weeks:["日","一","二","三","四","五","六"],times:["小时","分钟","秒数"],titText:"请选择日期时间",clear:"清空",today:"现在",yes:"确定",close:"关闭"},range:!1,trigger:"click",format:"YYYY-MM-DD hh:mm:ss",minDate:"1900-01-01 00:00:00",maxDate:"2099-12-31 23:59:59"},$.fn.jeDate=function(a){return new jeDate($(this),a||{})},$.extend({jeDate:function(a,b){return new jeDate($(a),b||{})}}),jet.isObj=function(a){for(var b in a)return!0;return!1},jet.reMatch=function(a){return a.match(/\w+|d+/g)},jet.docScroll=function(a){return a=a?"scrollLeft":"scrollTop",document.body[a]|document.documentElement[a]},jet.docArea=function(a){return document.documentElement[a?"clientWidth":"clientHeight"]},jet.isLeap=function(a){return 0!==a%100&&0===a%4||0===a%400},jet.digit=function(a){return 10>a?"0"+(0|a):a},jet.isNum=function(a){return/^[+-]?\d*\.?\d*$/.test(a)?!0:!1},jet.getDaysNum=function(a,b){var c=31;switch(parseInt(b)){case 2:c=jet.isLeap(a)?29:28;break;case 4:case 6:case 9:case 11:c=30}return c},jet.getYM=function(a,b,c){var d=new Date(a,b-1);return d.setMonth(b-1+c),{y:d.getFullYear(),m:d.getMonth()+1}},jet.prevMonth=function(a,b,c){return jet.getYM(a,b,0-(c||1))},jet.nextMonth=function(a,b,c){return jet.getYM(a,b,c||1)},jet.parse=function(a,b){return b.replace(new RegExp(regymdzz,"g"),function(b){return"zz"==b?"00":jet.digit(a[b])})},jet.isparmat=function(a){var b=jet.reMatch(a),c=regymdzz.split("|"),d=[];return $.each(c,function(a,c){$.each(b,function(a,b){c==b&&d.push(b)})}),d.join("-")},jet.parseOld=function(a,b,c){a=a.concat(b);var d={},f=(regymdzz.split("|"),jet.reMatch(c));return $.each(a,function(a,b){d[f[a]]=parseInt(b)}),c.replace(new RegExp(regymdzz,"g"),function(a){return"zz"==a?"00":jet.digit(d[a])})},jet.checkFormat=function(a){var b=[];return a.replace(new RegExp(regymdzz,"g"),function(a){b.push(a)}),b.join("-")},jet.splMatch=function(a){var b=a.split(" ");return jet.reMatch(b[0])},jet.mlen=function(a){var b=a.match(/\w+|d+/g).length,c="hh"==a.substring(0,2),d=c&&3>=b?7:b;return d},jet.checkDate=function(a){var b=jet.reMatch(a);if(isNaN(b[0])||isNaN(b[1])||isNaN(b[2]))return!1;if(b[1]>12||b[1]<1)return!1;if(b[2]<1||b[2]>31)return!1;if((4==b[1]||6==b[1]||9==b[1]||11==b[1])&&b[2]>30)return!1;if(2==b[1]){if(b[2]>29)return!1;if((0==b[0]%100&&0!=b[0]%400||0!=b[0]%4)&&b[2]>28)return!1}return!0},jet.GetDateTime=function(a,b){var c,d,e,f;return b=b||"YYYY-MM-DD hh:mm:ss",c=$.extend({YYYY:null,MM:null,DD:null,hh:0,mm:0,ss:0},a),d={YYYY:"FullYear",MM:"Month",DD:"Date",hh:"Hours",mm:"Minutes",ss:"Seconds"},e=(new DateTime).reDate(),$.each(["ss","mm","hh","DD","MM","YYYY"],function(a,b){if(!jet.isNum(parseInt(c[b])))return null;var f=e.GetValue();(parseInt(c[b])||0==parseInt(c[b]))&&f["set"+d[b]](e["Get"+d[b]]()+("MM"==b?-1:0)+parseInt(c[b]))}),f=jet.parse({YYYY:e.GetFullYear(),MM:e.GetMonth(),DD:e.GetDate(),hh:e.GetHours(),mm:e.GetMinutes(),ss:e.GetSeconds()},b)},jet.isValHtml=function(a){return/textarea|input/.test(a[0].tagName.toLocaleLowerCase())},jet.isBool=function(a){return void 0==a||1==a?!0:!1},searandom=function(){var c,a="",b=[1,2,3,4,5,6,7,8,9,0];for(c=0;8>c;c++)a+=b[Math.round(Math.random()*(b.length-1))];return a},jedfn=jeDate.prototype,jefix="jefixed",matArr=jet.reMatch(regymdzz),jedfn.initdates=function(){var i,k,j,l,m,a=this,b=a.opts,d=(new Date,void 0!=b.trigger?b.trigger:config.trigger),e=void 0==b.zIndex?1e4:b.zIndex,f=void 0==b.isinitVal||0==b.isinitVal?!1:!0,g="#jedatebox"+searandom(),h=jet.isBool(b.isShow);a.areaVal=[],b.range=b.range||config.range,a.fixed=jet.isBool(b.fixed),i=function(c,d){var j,k,l,f=$("<div/>",{id:d.replace(/\#/g,""),"class":"jedatebox "+(b.skinCell||config.skinCell)}),i=h?1==a.fixed?"absolute":"fixed":"relative";f.attr("author","chen guojun").css({"z-index":"#jedatebox"!=d?"":e,position:i}),"#jedatebox"!=d&&f.attr({jeformat:b.format||config.format,jefixed:g}),j=config.minDate.split(" "),k=config.maxDate.split(" "),jet.minDate=(/\-/g.test(b.minDate)||void 0==b.minDate?b.minDate:j[0]+" "+b.minDate)||config.minDate,jet.maxDate=(/\-/g.test(b.maxDate)||void 0==b.maxDate?b.maxDate:k[0]+" "+b.maxDate)||config.maxDate,jet.boxelem=h?"#jedatebox":d,a.format=h?b.format||config.format:f.attr("jeformat"),l=a.getValue({}),$(c).append(f),a.renderHtml(l[0].YYYY,l[0].MM,l[0].DD,b,jet.boxelem)},f&&d&&(j=b.initDate||[],j[1]?(l=jet.reMatch(jet.GetDateTime(j[0])),k=[{YYYY:l[0],MM:jet.digit(l[1]),DD:jet.digit(l[2]),hh:jet.digit(l[3]),mm:jet.digit(l[4]),ss:jet.digit(l[5])}]):k=a.getValue(jet.isObj(j[0])?j[0]:{}),a.setValue(k[0],b.format||config.format)),h?(m=["body","#jedatebox"],d?a.valCell.on(d,function(a){a.stopPropagation(),$(m[1]).length>0||i(m[0],m[1])}):i(m[0],m[1])):i(a.valCell,g)},jedfn.parseFormat=function(a,b){return jet.parse(a,b)},jedfn.parseValue=function(a,b){var j,k,c=this,d=[],e=c.opts,f="",g=$(jet.boxelem),h=void 0==b?$(g.attr(jefix)).length>0?g.attr("jeformat"):c.format:b,i=$.isFunction(a)?a():a;return(""!=i||i.length>0)&&(j=0!=e.range,k=new Array(j?2:1),$.each(k,function(a){var b=2==k.length,f={},g=jet.reMatch(h),j=b?i.split(e.range):i;b&&$.each(jet.reMatch(j[a]),function(a,b){f[7==jet.mlen(c.format)?g[a]:matArr[a]]=b}),d.push(c.parseFormat(b?f:j,h)),f={}}),f=d.join(j?e.range:"")),f},jedfn.setValue=function(a,b,c){var f,g,h,i,j,d=this,e=d.valCell;return"string"==typeof a&&""!=a&&0==d.opts.range?(g=jet.reMatch(a),h={},$.each(jet.reMatch(d.format),function(a,b){h[b]=parseInt(g[a])}),f=h):f=a,i=jet.isValHtml(e)?"val":"text",j=d.parseValue(f,b),0!=c&&e[i](j),j},jedfn.getValue=function(a){var e,m,n,o,p,q,r,s,t,u,v,w,b=this,c=b.valCell,d=b.opts,f=(new DateTime).reDate(),g=f.GetFullYear(),h=f.GetMonth(),i=f.GetDate(),j=f.GetHours(),k=f.GetMinutes(),l=f.GetSeconds();return void 0==a&&jet.isBool(d.isShow)?(m=jet.isValHtml(c)?"val":"text",e=c[m]()):(n=jet.isBool(d.isShow)?""==b.getValue():!jet.isBool(d.isShow),o=$.extend({YYYY:null,MM:null,DD:null},a||{}),p=[],q=new Array(2),r=function(a){return[void 0==o[a]||null==o[a],o[a]]},s=[{YYYY:g,MM:h,DD:i,hh:j,mm:k,ss:l,zz:0},{YYYY:g,MM:h,DD:i,hh:j,mm:k,ss:l,zz:0}],n?$.each(q,function(a){var b={};$.each(matArr,function(c,d){b[d]=parseInt(r(d)[0]?s[a][d]:r(d)[1])}),p.push($.extend(s[a],b))}):(t=0!=d.range,u=b.getValue(),v=u.split(d.range),w=jet.reMatch(b.format),$.each(q,function(b){var e,c={},d=t?jet.reMatch(v[b]):jet.reMatch(u);$.each(w,function(a,b){c[b]=parseInt(d[a])}),e=$.extend(c,a||{}),p.push($.extend(s[b],e))})),e=p),e},jedfn.renderHtml=function(a,b,c,d,e){var p,q,r,s,t,u,v,w,x,y,z,A,B,C,E,F,G,f=this,g=$(e),h=d.language||config.language,i=0!=d.range,j=jet.isBool(d.isShow),k=jet.minDate.replace(/\s+/g," ").split(" "),l=jet.maxDate.replace(/\s+/g," ").split(" "),m=f.getValue({YYYY:a,MM:b,DD:c}),n=m[0],o=m[1];f.format=j?f.format:g.attr("jeformat"),p=jet.mlen(f.format),q=/\hh/.test(f.format),r="cn"==h.name?j?h.clear:"重置":j?h.clear:"Reset",s="<div class='arthead'></div><div class='artcont'></div>",t=$("<div/>",{"class":"maincont"}),u=$("<div/>",{"class":"mainfoot"}),v=$("<div/>",{"class":"daybox"}).append(s),w=$("<div/>",{"class":"ymsbox"}).append(s),x=$("<div/>",{"class":"timebox"}).append(s),t.append(w).append(v).append(1==p||2==p?"":x),g.empty().append(t.children().hide()).append(u),y=function(){var a="<em></em><i>:</i><em></em><i>:</i><em></em>";return i?a+"<span> ~ </span>"+a:a},z='<span class="clear">'+r+'</span><span class="today">'+h.today+'</span><span class="setok">'+h.yes+"</span>",A=$("<div/>",{"class":"timecon"}).append(y()),B=$("<div/>",{"class":"btnscon"}).append(z),u.append(A).append(B),g.append($("<div/>",{"class":"jedate-tips"}).hide()),f.maincon=function(a,b){return g.find(a+" > "+(0==b?".arthead":".artcont"))},q?(C=/\s/.test(jet.minDate)?k[1]:k[0],/\s/.test(jet.maxDate)?l[1]:l[0],E=jet.reMatch(C),F=[n.hh,n.mm,n.ss],G=[],G=i?""==f.getValue()?7==p?E.concat(E):E.concat([0,0,0]):F.concat([o.hh,o.mm,o.ss]):F,$.each(u.find(".timecon em"),function(a){$(this).text(jet.digit(G[a]))})):u.find(".timecon").hide(),7==p?(f.maincon(".timebox",0).html(h.titText),g.find(".timebox").show(),f.eachHms(d,g)):p>=3&&6>=p&&(f.maincon(".daybox",0).append('<em class="yearprev yprev"></em><em class="monthprev mprev"></em><em class="monthnext mnext"></em><em class="yearnext ynext"></em>'),g.find(".daybox").show(),f.eachDays(n.YYYY,n.MM,n.DD,d,g),q&&(f.maincon(".timebox",1).attr("cont","no"),f.maincon(".timebox",0).html(h.titText+'<em class="close"></em>'),g.find(".timecon").on("click",function(){"no"==f.maincon(".timebox",1).attr("cont")&&(f.maincon(".timebox",1).attr("cont","yes"),g.find(".ymsbox,.daybox").hide(),g.find(".timebox").show(),f.eachHms(d,g),f.dateOrien(g,f.valCell))}),f.maincon(".timebox",0).on("click",".close",function(){f.maincon(".timebox",1).html("").attr("cont","no"),g.find(".ymsbox,.timebox").hide(),g.find(".daybox").show(),f.dateOrien(g,f.valCell)}),A.css({cursor:"pointer"})),f.maincon(".ymsbox",0).append('<em class="yearprev yprev"></em><em class="yearnext ynext"></em><em class="close"></em>').addClass("ymfix"),f.eachYM(n.YYYY,n.MM,d,g,".fixcon")),(1==p||2==p)&&(f.maincon(".ymsbox",0).append('<em class="yearprev yprev"></em><em class="yearnext ynext"></em>'),g.find(".ymsbox").show(),f.eachYM(n.YYYY,n.MM,d,g,".jedate-cont")),jet.isBool(d.isTime)&&j||u.find(".timecon").hide(),j||u.find(".today").hide(),f.eventsDate(d,g),setTimeout(function(){d.success&&d.success(g)},50)},jedfn.createYMHtml=function(a,b,c){var d=parseInt(a),e=parseInt(b),f=this.maincon(".daybox",0),g=$("<p/>").css({width:jet.isBool(c.multiPane)?"":"50%"}),h="<span class='ymbtn'>"+e+"月 "+d+"年</span>";return f.append(g.html(h)),d+"-"+e},jedfn.eachYM=function(a,b,c){var p,q,r,s,t,u,f=this,g=new Array(15),h=new Date,i=c.language||config.language,j=f.maincon(".ymsbox",1),k=jet.isBool(c.multiPane),l=jet.mlen(f.format),m=f.getValue({}),n=/\hh/.test(f.format),o=1==l;j.find(".ymcon").length>0&&j.find(".ymcon").remove(),$.each(new Array(k?1:2),function(b){var l,d=function(a,c,d){var e=a.replace(gr,""),g=c.replace(gr,""),h=d.replace(gr,"");return/YYYY-MM-DD/g.test(jet.isparmat(f.format))?parseInt(e)==parseInt(g)?0==b?"actdate":"":"":parseInt(e)==parseInt(g)?(n||(f.areaVal.push(a),f.areaStart=!0),"actdate"):parseInt(e)>parseInt(g)&&parseInt(e)<parseInt(h)?"contain":parseInt(e)==parseInt(h)?(n||(f.areaVal.push(a),f.areaStart=!0),"actdate"):""},e=$("<div/>",{"class":"ymcon"}).addClass(1==b?"spaer":""),k=[];$.each(o?g:i.month,function(e,i){var l,n,p,q,r,s,t,u,v,w,x,y,j=1==b?a+(o?g.length:1):a;e=1==b?o?15+e:12+e:e,o?(n=jet.splMatch(jet.minDate),p=jet.splMatch(jet.maxDate),q=n[0],r=p[0],s=j-7+e,t=""==f.getValue()&&jet.isBool(c.isShow)?h.getFullYear():f.getValue(),q>s||s>r?k.push({style:"disabled",ym:s,idx:e}):(l=d(s.toString(),t.toString(),m[1].YYYY.toString()),k.push({style:l,ym:s,idx:e}))):(n=jet.splMatch(jet.minDate),p=jet.splMatch(jet.maxDate),u=parseInt(j+""+jet.digit(i)+"01"),v=parseInt(n[0]+""+jet.digit(n[1])+jet.digit(n[2])),w=parseInt(p[0]+""+jet.digit(p[1])+jet.digit(p[2])),v>u||u>w?k.push({style:"disabled",ym:j+"-"+jet.digit(i),idx:e}):(x=j+"-"+jet.digit(i),y=m[0].YYYY+"-"+jet.digit(m[0].MM),l=d(x,y,m[1].YYYY+"-"+jet.digit(m[1].MM)),k.push({style:l,ym:j+"-"+jet.digit(i),idx:e})))}),l=$("<table/>",{"class":o?"yul":"ymul"}),$.each(new Array(o?5:4),function(){var b=$("<tr/>");$.each(new Array(3),function(){var a=$("<td/>");l.append(b.append(a))})}),$.each(k,function(a,b){l.find("td").eq(a).addClass(b.style).attr({idx:b.idx,"je-val":b.ym}).html(b.ym)}),j.append(e.append(l))}),p=j.find("td"),q=f.maincon(".ymsbox",0),r=o?k?14:29:k?11:23,s=p.eq(0).text(),t=p.eq(r).text(),u=[o?s:s.substring(0,4),o?t:t.substring(0,4)],q.find("p").remove(),q.append("<p>"+s+" ~ "+t+"</p>").attr({min:u[0],max:u[1]})},jedfn.dateRegExp=function(valArr){var i,enval=valArr.split(",")||[],re="",doExp=function(val){var arr,tmpEval,re=/#?\{(.*?)\}/;for(val+="";null!=(arr=re.exec(val));)arr.lastIndex=arr.index+arr[1].length+arr[0].length-arr[1].length-1,tmpEval=parseInt(eval(arr[1])),0>tmpEval&&(tmpEval="9700"+-tmpEval),val=val.substring(0,arr.index)+tmpEval+val.substring(arr.lastIndex+1);return val};if(enval&&enval.length>0){for(i=0;i<enval.length;i++)re+=doExp(enval[i]),i!=enval.length-1&&(re+="|");re=re?new RegExp("(?:"+re+")"):null}else re=null;return re},jedfn.eachDays=function(a,b,c,d){var f=this,g=jet.isBool(d.isShow),h=parseInt(a),i=parseInt(b),j=f.valCell,k=d.language||config.language,l=d.valiDate||[],m=jet.reMatch(jet.minDate),n=parseInt(m[0]+""+jet.digit(m[1])+jet.digit(m[2])),o=jet.reMatch(jet.maxDate),p=parseInt(o[0]+""+jet.digit(o[1])+jet.digit(o[2])),q=jet.isBool(d.multiPane),r=f.getValue(g?{}:{YYYY:a,MM:b,DD:c}),s=""!=(j.val()||j.text())&&0!=d.range,t=parseInt(r[0].YYYY+""+jet.digit(r[0].MM)+jet.digit(r[0].DD)),u=function(a,b,c){var e=d.marks,f=function(a,b){for(var c=a.length;c--;)if(a[c]===b)return!0;return!1};return $.isArray(e)&&e.length>0&&f(e,a+"-"+jet.digit(b)+"-"+jet.digit(c))?'<i class="marks"></i>':""},v=function(a,b,c){var e,g,h,i;return 1==d.festival&&"cn"==k.name?(g=f.jeLunar(a,b-1,c),h=g.solarFestival||g.lunarFestival,i=""!=(h&&g.jieqi)?h:g.jieqi||g.showInLunar,e='<p><span class="solar">'+c+'</span><span class="lunar">'+i+"</span></p>"):e='<p class="nolunar">'+c+"</p>",e},w=function(a,b,c,d){var e=parseInt(a+""+jet.digit(b)+jet.digit(c));if(d){if(e>=n&&p>=e)return!0}else if(n>e||e>p)return!0},x=function(a,b){var l,m,n,o,p,q,x,y,z,A,B,C,c=0,d=[],e=new Date(a,b-1,1).getDay()||7,g=jet.getDaysNum(a,b),h=0,i=jet.prevMonth(a,b),j=jet.getDaysNum(a,i.m),k=jet.nextMonth(a,b);for(l=j-e+1;j>=l;l++,c++)m=u(i.y,i.m,l),n=w(i.y,i.m,l,!1)?"disabled":"other",d.push({style:n,ymd:i.y+"-"+i.m+"-"+l,day:l,d:v(i.y,i.m,l)+m,idx:h++});for(o=1;g>=o;o++,c++)p=u(a,b,o),n="",q=parseInt(a+""+jet.digit(b)+jet.digit(o)),x=parseInt(r[1].YYYY+""+jet.digit(r[1].MM)+jet.digit(r[1].DD)),y=q>t,z=x>q,w(a,b,o,!0)?q==t?(n="actdate",f.areaVal.push(a+"-"+jet.digit(b)+"-"+jet.digit(o)),f.areaStart=!0):y&&z&&s?n="contain":q==x&&s?(n="actdate",f.areaVal.push(a+"-"+jet.digit(b)+"-"+jet.digit(o)),f.areaEnd=!0):n="":n="disabled",d.push({style:n,ymd:a+"-"+b+"-"+o,day:o,d:v(a,b,o)+p,idx:h++});for(A=1,B=42-c;B>=A;A++)C=u(k.y,k.m,A),n=w(k.y,k.m,A,!1)?"disabled":"other",d.push({style:n,ymd:k.y+"-"+k.m+"-"+A,day:A,d:v(k.y,k.m,A)+C,idx:h++});return d},y=function(a){var b=jet.reMatch(a),c=[];return $.each(b,function(a,b){c.push(jet.digit(b))}),c.join("-")},z=new Array(q?1:2),A=i+1>12,B=[];$.each(z,function(a){var m,n,o,p,c=$("<table/>",{"class":"daysul"}),e=$("<thead/>"),g=$("<tbody/>"),j=1==a?42:0;c.append(e).append(g),$.each(new Array(7),function(a){var b=$("<tr/>");$.each(new Array(7),function(){var c=$("<th/>"),d=$("<td/>");b.append(0==a?c:d.attr("idx",j++)),0==a?e.append(b):g.append(b)})}),m=A&&1==a?h+1:h,n=A&&1==a?1:1==a?i+1:i,o=x(m,n),p=$("<div/>",{"class":"contlist"}),$.each(k.weeks,function(a,b){c.find("th").eq(a).text(b)}),B.push(f.createYMHtml(m,n,d)),$.each(o,function(a,b){var e,g,h,i,j,d=b.style;l.length>0&&""!=l[0]&&(/\%/g.test(l[0])?(e=l[0].replace(/\%/g,"").split(","),g=[],$.each(e,function(a,b){g.push(jet.digit(parseInt(b)))}),h=-1==$.inArray(jet.digit(b.day),g),d=jet.isBool(l[1])?h?"disabled":d:h?d:"disabled"):(i=f.dateRegExp(l[0]),j=i.test(jet.digit(b.day)),d=jet.isBool(l[1])?j?"disabled":b.style:j?b.style:"disabled")),c.find("td").eq(a).addClass(d).attr("je-val",y(b.ymd)).html(b.d)}),f.maincon(".daybox",1).append(p.append(c)).addClass(1==a?"spaer":"")}),f.maincon(".daybox",0).attr("je-ym",B.join(","))},jedfn.eachHms=function(a,b){var w,c=this,d=a.language||config.language,e=jet.isBool(a.multiPane),f=c.getValue({}),g=""==c.getValue(),h=0==a.range,i=jet.minDate.replace(/\s+/g," ").split(" "),j=jet.maxDate.replace(/\s+/g," ").split(" "),k=/YYYY-MM-DD/g.test(jet.isparmat(c.format))&&/\hh/.test(c.format),l=jet.reMatch(i[1]),m=jet.reMatch(j[1]),n=c.maincon(".timebox",1),o=["action","disabled"],p=b.find(".mainfoot .timecon em"),q=new Date,r=q.getHours(),s=q.getMinutes(),t=q.getSeconds(),u=[f[0].hh||r,f[0].mm||s,f[0].ss||t],v=[f[1].hh||r,f[1].mm||s,f[1].ss||t];0==a.range&&b.find(".timelist").length>0||($.each(new Array(h?1:2),function(b){var c=$("<div/>",{"class":"timelist"}).css({width:h?"100%":"50%","float":h?"":"left"}),f=$("<div/>",{"class":"contime"}),i=$("<div/>",{"class":"textbox"}),j=i.append("<p>"+d.times[0]+"</p><p>"+d.times[1]+"</p><p>"+d.times[2]+"</p>");c.append(j),n.addClass(1==b?"spaer":""),$.each([24,60,60],function(d,e){var q,r,h="",i=$("<ul/>").attr("idx",1==b?3+d:d),j=p.eq(d).text();for(q=0;e>q;q++)r=$("<li/>"),h=0!=a.range?k?0==b?q>=l[d]?q==(g?l[d]:u[d])?o[0]:"":o[1]:q>m[d]?o[1]:q==(g?0:v[d])?o[0]:"":q>=l[d]?q==(g?l[d]:0==b?u[d]:v[d])?o[0]:"":o[1]:q>=l[d]&&q<=m[d]?j<l[d]?q==l[d]?o[0]:"":j>m[d]?q==m[d]?o[0]:"":q==j?o[0]:"":o[1],r.text(jet.digit(q)).addClass(h),n.append(c.append(f.append(i.append(r))))}),0==e&&h&&c.css({"padding-left":c.outerWidth()/2+12,"padding-right":c.outerWidth()/2+12})}),c.locateScroll(n.find("ul")),c.clickTime(a,b),w=[],$.each(l,function(a,b){parseInt(b)>parseInt(m[a])&&w.push("不能大于最大"+d.times[a])}),w.length>0&&c.tips(w.join("<br/>"),4.5))},jedfn.eventsDate=function(a,b){var e,c=this;jet.isBool(a.multiPane),c.clickYM(a,b),c.clickDays(a,b),c.clickBtn(a,b),jet.isBool(a.isShow)&&(e=a.position||[],e.length>0?b.css({top:e[0],left:e[1]}):(c.dateOrien(b,c.valCell),$(window).on("resize",function(){c.dateOrien(b,c.valCell)}))),$(document).on("mouseup",function(a){if(a.stopPropagation(),"#jedatebox"==jet.boxelem){var b=$(jet.boxelem);b&&"none"!==b.css("display")&&c.dateClose(),$("#jedatetipscon").length>0&&$("#jedatetipscon").remove(),delete c.areaStart,delete c.areaEnd,c.areaVal=[]}}),$(jet.boxelem).on("mouseup",function(a){a.stopPropagation()})},jedfn.clickYM=function(a,b){var t,c=this,d=c.maincon(".ymsbox",0),e=c.valCell,f=d.find(".yprev"),g=d.find(".ynext"),h=c.maincon(".daybox",0),i=jet.isBool(a.isShow),j=h.find(".yprev"),k=h.find(".ynext"),l=h.find(".mprev"),m=h.find(".mnext"),n=jet.mlen(c.format),o=2==n,p=1==n,q=["actdate","contain"],r=new Date,s=function(){var b=c.maincon(".ymsbox",1).find(".ymcon"),d=b.find("td");d.on("click",function(){var b=$(this),e=b.attr("je-val");b.hasClass("disabled")||(0==a.range?(d.removeClass(q[0]),b.addClass(q[0]),c.maincon(".ymsbox",0).attr("data-val",b.text())):c.areaStart&&void 0==c.areaEnd?(b.addClass(q[0]),c.areaEnd=!0,c.areaVal.push(e),d.each(function(){var g,a=$(this),b=a.attr("je-val").replace(gr,""),d=[c.areaVal[0].replace(gr,""),c.areaVal[1].replace(gr,"")],e=Math.min.apply(null,d),f=Math.max.apply(null,d);a.hasClass("other")||(g=parseInt(b)>parseInt(e)&&parseInt(b)<parseInt(f),g&&a.addClass(q[1]))})):c.areaStart&&c.areaEnd&&(c.delAreaAttr(),d.removeClass(q[0]).removeClass(q[1]),b.addClass(q[0]),c.areaVal.push(e),c.areaStart=!0))})};o||p?(s(),$.each([f,g],function(d,f){f.on("click",function(){var l,m,n,g=$(this),h=r.getMonth()+1,i=parseInt(g.parent().attr("min")),j=parseInt(g.parent().attr("max")),k=p?0==d?i:j:0==d?--i:++j;c.renderHtml(k,h,null,a,b),0==a.range&&(l=p?{YYYY:k}:{YYYY:k,MM:h},m=c.parseValue(l),n={YYYY:k,MM:h,DD:r.getDate(),hh:r.getHours(),mm:r.getMinutes(),ss:r.getSeconds()},$.isFunction(a.toggle)&&a.toggle(e,m,n))})})):($.each([j,k],function(d,f){f.on("click",function(f){var g,h,i,j,k,l,m;f.stopPropagation(),g=jet.reMatch($(this).parent().attr("je-ym")),h=parseInt(g[0]),i=parseInt(g[1]),j=0==d?--h:++h,c.renderHtml(j,i,null,a,b),0==a.range&&(k=c.getValue({})[0],l=c.parseValue({YYYY:j,MM:i,DD:k.DD}),m={YYYY:j,MM:i,DD:r.getDate(),hh:r.getHours(),mm:r.getMinutes(),ss:r.getSeconds()},$.isFunction(a.toggle)&&a.toggle({elem:e,val:l,date:m}))})}),$.each([l,m],function(d,f){f.on("click",function(f){var g,h,i,j,k,l,m,n,o,p;f.stopPropagation(),g=jet.reMatch($(this).parent().attr("je-ym")),h=parseInt(g[0]),i=parseInt(g[1]),j=jet.prevMonth(h,i),k=jet.nextMonth(h,i),0==d?c.renderHtml(j.y,j.m,null,a,b):c.renderHtml(k.y,k.m,null,a,b),l=0==d?j.y:k.y,m=0==d?j.m:k.m,0==a.range&&(n=c.getValue({})[0],o=c.parseValue({YYYY:l,MM:m,DD:n.DD}),p={YYYY:l,MM:m,DD:r.getDate(),hh:r.getHours(),mm:r.getMinutes(),ss:r.getSeconds()},$.isFunction(a.toggle)&&a.toggle({elem:e,val:o,date:p}))})})),n>=3&&6>=n&&(c.maincon(".daybox",0).on("click",".ymbtn",function(){b.children(".ymsbox").show(),b.children(".daybox,.mainfoot").hide(),i&&c.dateOrien(b,c.valCell)}),t=function(){var d=b.find(".ymcon"),e=d.find("td");e.on("click",function(){var d=$(this),f=jet.reMatch(d.attr("je-val"));e.removeClass(q[0]),d.addClass(q[0]),b.children(".jedate-contfix").show(),b.children(".jedate-jedatewrap").hide(),c.renderHtml(f[0],f[1],null,a,b)})},$.each([f,g],function(d,e){e.on("click",function(){var f=r.getMonth()+1,g=parseInt($(this).parent().attr("min")),h=parseInt($(this).parent().attr("max")),j=p?0==d?g:h:0==d?--g:++h;c.eachYM(j,f,a,b,".jedate-contfix"),t(),i&&c.dateOrien(b,c.valCell),$.isFunction(a.toggle)&&a.toggle()})}),d.on("click",".close",function(){b.children(".daybox,.mainfoot").show(),b.children(".ymsbox").hide(),i&&c.dateOrien(b,c.valCell)}),t())},jedfn.gethmsVal=function(a){var b={};return a.find(".timecon em").each(function(a){var c=$(this).attr("disabled");void 0==c&&(b[matArr[3+a]]=$(this).text())}),b},jedfn.clickDays=function(a,b){var c=this,d=c.valCell,e="je-val",f=b.find(".daysul"),g=f.find("td"),h=a.language||config.language,i=["actdate","contain"];g.on("click",function(f){var m,n,h=$(this),j=h.attr(e),k=jet.reMatch(j),l=[];h.hasClass("disabled")||(f.stopPropagation(),m=function(){var f,j,m,n;$.each(k,function(a,b){l.push(parseInt(b))}),$(b.attr(jefix)).length>0?c.renderHtml(l[0],l[1],l[2],a,b):jet.isBool(a.onClose)?(g.removeClass(i[0]),h.addClass(i[0])):(f={},j=jet.reMatch(h.attr(e)),$.each(j,function(a,b){f[matArr[a]]=b}),m=/\hh/.test(c.format)?$.extend(f,c.gethmsVal(b)):f,n=c.setValue(m),c.dateClose(),($.isFunction(a.okfun)||null!=a.okfun)&&a.okfun&&a.okfun({elem:d,val:n,date:m}))},n=function(){c.areaStart&&void 0==c.areaEnd?(h.addClass(i[0]),c.areaEnd=!0,c.areaVal.push(j),g.each(function(){var g,a=$(this),b=a.attr("je-val").replace(gr,""),d=[c.areaVal[0].replace(gr,""),c.areaVal[1].replace(gr,"")],e=Math.min.apply(null,d),f=Math.max.apply(null,d);a.hasClass("other")||a.hasClass("disabled")||(g=parseInt(b)>parseInt(e)&&parseInt(b)<parseInt(f),g&&a.addClass(i[1]))})):c.areaStart&&c.areaEnd&&(c.delAreaAttr(),g.removeClass(i[0]).removeClass(i[1]),h.addClass(i[0]),c.areaVal.push(j),c.areaStart=!0)},0==a.range?m():n())}),a.festival&&"cn"==h.name&&(b.addClass("grid"),g.on("mouseover",function(){var b,d,f,g,h,i,j,k,l;$("#jedatetipscon").length>0&&$("#jedatetipscon").remove(),b=$(this),d=jet.reMatch(b.attr(e)),f=$("<div/>",{id:"jedatetipscon","class":"jedatetipscon"}),g=c.jeLunar(parseInt(d[0]),parseInt(d[1])-1,parseInt(d[2])),h="<p>"+g.solarYear+"年"+g.solarMonth+"月"+g.solarDate+"日 "+g.inWeekDays+'</p><p class="red">农历：'+g.shengxiao+"年 "+g.lnongMonth+"月"+g.lnongDate+"</p><p>"+g.ganzhiYear+"年 "+g.ganzhiMonth+"月 "+g.ganzhiDate+"日</p>",i=""!=(g.solarFestival||g.lunarFestival)?'<p class="red">'+("节日："+g.solarFestival+g.lunarFestival)+"</p>":"",j=""!=g.jieqi?'<p class="red">'+(""!=g.jieqi?"节气："+g.jieqi:"")+"</p>":"",k=""!=(g.solarFestival||g.lunarFestival||g.jieqi)?i+j:"",$("body").append(f),f.html(h+k),l=jedfn.lunarOrien(f,b),f.css({"z-index":void 0==a.zIndex?10005:a.zIndex+5,top:l.top,left:l.left,position:"absolute",display:"block"})}).on("mouseout",function(){$("#jedatetipscon").remove()}))},jedfn.clickBtn=function(a,b){var c=this,d=c.valCell,e=jet.isBool(a.isShow),f=7==jet.mlen(c.format),h=(jet.isBool(a.multiPane),2==jet.mlen(c.format)),i=1==jet.mlen(c.format);b.on("click",".clear",function(f){var g,h,i,j;f.stopPropagation(),e?(g=jet.isValHtml(c.valCell)?"val":"text",h=c.valCell[g](),i=c.setValue(""),c.dateClose(),""!=h&&(jet.isBool(a.clearRestore)&&(jet.minDate=a.startMin||jet.minDate,jet.maxDate=a.startMax||jet.maxDate),($.isFunction(a.clearfun)||null!=a.clearfun)&&a.clearfun({elem:d,val:i}))):(j=c.getValue({}),c.renderHtml(j[0].YYYY,j[0].MM,j[0].DD,a,b)),0!=a.range&&c.delAreaAttr()}),0!=a.range&&b.find(".today").hide(),b.on("click",".today",function(){var b=new Date,e={YYYY:b.getFullYear(),MM:jet.digit(b.getMonth()+1),DD:jet.digit(b.getDate()),hh:jet.digit(b.getHours()),mm:jet.digit(b.getMinutes()),ss:jet.digit(b.getSeconds())},f=c.setValue(e);c.dateClose(),($.isFunction(a.okfun)||null!=a.okfun)&&a.okfun({elem:d,val:f,date:e})}),b.on("click",".setok",function(g){var k,l,m,j,n,o,p,q,r,s,t,u;g.stopPropagation(),j=new Date,0==a.range?(n=c.gethmsVal(b),o=function(){var f,a={},d=h||i?".ymcon":".daysul",e=jet.reMatch(b.find(d).find("td.actdate").attr("je-val"));return $.each(e,function(b,c){a[matArr[b]]=c}),f=/\hh/.test(c.format)?$.extend(a,n):a},k=f?n:o()):(p={},q=[],r=[[],[]],b.find(".timecon em").each(function(a){var b=$(this).attr("disabled");void 0==b&&r[a>2?1:0].push($(this).text())}),7==jet.mlen(c.format)?0!=a.range&&$.each(r,function(a,b){var c=b.join("");p[c]=b.join(":"),q.push(c)}):$.each(c.areaVal,function(a,b){var d=b+(/\hh/.test(c.format)?" "+r[a].join(":"):""),e=d.replace(/\s|-|:/g,"");p[e]=d,q.push(e)}),s=Math.min.apply(null,q),t=Math.max.apply(null,q),k=p[s]+a.range+p[t]),e?(l=c.setValue(k),c.dateClose()):l=c.setValue(k,c.format,!1),0==a.range?m={YYYY:k.YYYY||j.getFullYear(),MM:jet.digit(k.MM||j.getMonth()+1),DD:jet.digit(k.DD||j.getDate()),hh:jet.digit(k.hh||j.getHours()),mm:jet.digit(k.mm||j.getMinutes()),ss:jet.digit(k.ss||j.getSeconds())}:(u=c.setValue(k,c.format,!1),m=[],$.each(new Array(2),function(b){var e={},f=jet.reMatch(u.split(a.range)[b]);$.each(jet.reMatch(c.format),function(a,b){e[b]=f[a]}),m.push(e)})),($.isFunction(a.okfun)||null!=a.okfun)&&a.okfun({elem:d,val:l,date:m})})},jedfn.clickTime=function(a,b){var d,c=this;/\hh/.test(c.format)&&(d=c.maincon(".timebox",1).find("ul"),d.on("click","li",function(){var a=$(this),e=a.parent().attr("idx"),f=a.text();a.hasClass("disabled")||(a.addClass("action").siblings().removeClass("action"),b.find(".timecon em").eq(e).text(f),c.locateScroll(d))}))},jedfn.locateScroll=function(a){$.each(a,function(){var a=$(this),b=a.find(".action"),c=b.length>0?b[0].offsetTop-114:0;a[0].scrollTop=c})},jedfn.lunarOrien=function(a,b,c){var d,e,f,g,h=b[0].getBoundingClientRect();return e=h.right+a[0].offsetWidth/1.5>=jet.docArea(1)?h.right-a[0].offsetWidth:h.left+(c?0:jet.docScroll(1)),d=h.bottom+a[0].offsetHeight/1<=jet.docArea()?h.bottom-1:h.top>a[0].offsetHeight/1.5?h.top-a[0].offsetHeight-1:jet.docArea()-a[0].offsetHeight,f=Math.max(d+(c?0:jet.docScroll())+1,1)+"px",g=e+"px",{top:f,left:g}},jedfn.dateOrien=function(a,b,c){var g,h,j,k,d=this,i=d.fixed?b[0].getBoundingClientRect():a[0].getBoundingClientRect(),f=i.left,e=i.bottom;d.fixed?(j=a.outerWidth(),k=a.outerHeight(),f+j>jet.docArea(!0)&&(f=jet.docArea(!0)-j),e+k>jet.docArea()&&(e=i.top>k?i.top-k-2:jet.docArea()-k-1),g=Math.max(e+(c?0:jet.docScroll())+1,1)+"px",h=f+"px"):(g="50%",h="50%",a.css({"margin-top":-(i.height/2),"margin-left":-(i.width/2)})),a.css({top:g,left:h})},jedfn.tips=function(a,b){var c=this,d=$(jet.boxelem).find(".jedate-tips");d.html("").html(a||"").show(),clearTimeout(c.tipTime),c.tipTime=setTimeout(function(){d.html("").hide()},1e3*(b||2.5))},jedfn.dateClose=function(){0==$($(jet.boxelem).attr(jefix)).length&&$(jet.boxelem).remove()},jedfn.dateContrast=function(a,b){var c=a.split("-"),d=b.split("-"),e=parseInt(c[0]+""+jet.digit(parseInt(c[1])-1)+jet.digit(c[2]||"01")),f=parseInt(d[0]+""+jet.digit(parseInt(d[1])-1)+jet.digit(c[2]||"01"));return e>=f?!1:!0},jedfn.delAreaAttr=function(){delete this.areaStart,delete this.areaEnd,this.areaVal=[]},jedfn.jeLunar=function(a,b,c){function o(a){var w,b=function(a,b){var c=new Date(31556925974.7*(a-1900)+6e4*e[b]+Date.UTC(1900,0,6,2,5));return c.getUTCDate()},c=function(a){var b,c=348;for(b=32768;b>8;b>>=1)c+=d[a-1900]&b?1:0;return c+p(a)},o=function(a){return f.charAt(a%10)+g.charAt(a%12)},p=function(a){var b=q(a)?65536&d[a-1900]?30:29:0;return b},q=function(a){return 15&d[a-1900]},r=function(a,b){return d[a-1900]&65536>>b?30:29},s=function(a){var b,d=0,e=0,f=new Date(1900,0,31),g=(a-f)/864e5;for(this.dayCyl=g+40,this.monCyl=14,b=1900;2050>b&&g>0;b++)e=c(b),g-=e,this.monCyl+=12;for(0>g&&(g+=e,b--,this.monCyl-=12),this.year=b,this.yearCyl=b-1864,d=q(b),this.isLeap=!1,b=1;13>b&&g>0;b++)d>0&&b==d+1&&0==this.isLeap?(--b,this.isLeap=!0,e=p(this.year)):e=r(this.year,b),1==this.isLeap&&b==d+1&&(this.isLeap=!1),g-=e,0==this.isLeap&&this.monCyl++;0==g&&d>0&&b==d+1&&(this.isLeap?this.isLeap=!1:(this.isLeap=!0,--b,--this.monCyl)),0>g&&(g+=e,--b,--this.monCyl),this.month=b,this.day=g+1},t=function(a){return 10>a?"0"+(0|a):a},u=function(a,b){var c=a;return b.replace(/dd?d?d?|MM?M?M?|yy?y?y?/g,function(a){switch(a){case"yyyy":var b="000"+c.getFullYear();return b.substring(b.length-4);case"dd":return t(c.getDate());case"d":return c.getDate().toString();case"MM":return t(c.getMonth()+1);case"M":return c.getMonth()+1}})},v=function(a,b){var c;switch(b){case 10:c="初十";break;case 20:c="二十";break;case 30:c="三十";break;default:c=k.charAt(Math.floor(b/10)),c+=j.charAt(b%10)}return c};this.isToday=!1,this.isRestDay=!1,this.solarYear=u(a,"yyyy"),this.solarMonth=u(a,"M"),this.solarDate=u(a,"d"),this.solarWeekDay=a.getDay(),this.inWeekDays="星期"+j.charAt(this.solarWeekDay),w=new s(a),this.lunarYear=w.year,this.shengxiao=h.charAt((this.lunarYear-4)%12),this.lunarMonth=w.month,this.lunarIsLeapMonth=w.isLeap,this.lnongMonth=this.lunarIsLeapMonth?"闰"+l[w.month-1]:l[w.month-1],this.lunarDate=w.day,this.showInLunar=this.lnongDate=v(this.lunarMonth,this.lunarDate),1==this.lunarDate&&(this.showInLunar=this.lnongMonth+"月"),this.ganzhiYear=o(w.yearCyl),this.ganzhiMonth=o(w.monCyl),this.ganzhiDate=o(w.dayCyl++),this.jieqi="",this.restDays=0,b(this.solarYear,2*(this.solarMonth-1))==u(a,"d")&&(this.showInLunar=this.jieqi=i[2*(this.solarMonth-1)]),b(this.solarYear,2*(this.solarMonth-1)+1)==u(a,"d")&&(this.showInLunar=this.jieqi=i[2*(this.solarMonth-1)+1]),"清明"==this.showInLunar&&(this.showInLunar="清明节",this.restDays=1),this.solarFestival=m[u(a,"MM")+u(a,"dd")],"undefined"==typeof this.solarFestival?this.solarFestival="":/\*(\d)/.test(this.solarFestival)&&(this.restDays=parseInt(RegExp.$1),this.solarFestival=this.solarFestival.replace(/\*\d/,"")),this.showInLunar=""==this.solarFestival?this.showInLunar:this.solarFestival,this.lunarFestival=n[this.lunarIsLeapMonth?"00":t(this.lunarMonth)+t(this.lunarDate)],"undefined"==typeof this.lunarFestival?this.lunarFestival="":/\*(\d)/.test(this.lunarFestival)&&(this.restDays=this.restDays>parseInt(RegExp.$1)?this.restDays:parseInt(RegExp.$1),this.lunarFestival=this.lunarFestival.replace(/\*\d/,"")),12==this.lunarMonth&&this.lunarDate==r(this.lunarYear,12)&&(this.lunarFestival=n["0100"],this.restDays=1),this.showInLunar=""==this.lunarFestival?this.showInLunar:this.lunarFestival}var d=[19416,19168,42352,21717,53856,55632,91476,22176,39632,21970,19168,42422,42192,53840,119381,46400,54944,44450,38320,84343,18800,42160,46261,27216,27968,109396,11104,38256,21234,18800,25958,54432,59984,28309,23248,11104,100067,37600,116951,51536,54432,120998,46416,22176,107956,9680,37584,53938,43344,46423,27808,46416,86869,19872,42448,83315,21200,43432,59728,27296,44710,43856,19296,43748,42352,21088,62051,55632,23383,22176,38608,19925,19152,42192,54484,53840,54616,46400,46496,103846,38320,18864,43380,42160,45690,27216,27968,44870,43872,38256,19189,18800,25776,29859,59984,27480,21952,43872,38613,37600,51552,55636,54432,55888,30034,22176,43959,9680,37584,51893,43344,46240,47780,44368,21977,19360,42416,86390,21168,43312,31060,27296,44368,23378,19296,42726,42208,53856,60005,54576,23200,30371,38608,19415,19152,42192,118966,53840,54560,56645,46496,22224,21938,18864,42359,42160,43600,111189,27936,44448],e=[0,21208,43467,63836,85337,107014,128867,150921,173149,195551,218072,240693,263343,285989,308563,331033,353350,375494,397447,419210,440795,462224,483532,504758],f="甲乙丙丁戊己庚辛壬癸",g="子丑寅卯辰巳午未申酉戌亥",h="鼠牛虎兔龙蛇马羊猴鸡狗猪",i=["小寒","大寒","立春","雨水","惊蛰","春分","清明","谷雨","立夏","小满","芒种","夏至","小暑","大暑","立秋","处暑","白露","秋分","寒露","霜降","立冬","小雪","大雪","冬至"],j="日一二三四五六七八九十",k="初十廿卅",l=["正","二","三","四","五","六","七","八","九","十","十一","腊"],m={"0101":"*1元旦节","0202":"湿地日","0214":"情人节","0308":"妇女节","0312":"植树节","0315":"消费者权益日","0401":"愚人节","0422":"地球日","0501":"*1劳动节","0504":"青年节","0512":"护士节","0518":"博物馆日","0520":"母亲节","0601":"儿童节","0623":"奥林匹克日","0630":"父亲节","0701":"建党节","0801":"建军节","0903":"抗战胜利日","0910":"教师节",1001:"*3国庆节",1201:"艾滋病日",1224:"平安夜",1225:"圣诞节"},n={"0100":"除夕","0101":"*2春节","0115":"元宵节","0505":"*1端午节","0707":"七夕节","0715":"中元节","0815":"*1中秋节","0909":"*1重阳节",1015:"下元节",1208:"腊八节",1223:"小年"};
    return new o(new Date(a,b,c))},$.dateVer="6.0.2",$.nowDate=function(a,b){return b=b||"YYYY-MM-DD hh:mm:ss","number"==typeof a&&(a={DD:a}),jet.GetDateTime(a,b)},$.timeStampDate=function(a,b){var c,d,e,f,g,h;if(b=b||"YYYY-MM-DD hh:mm:ss",c=/^(-)?\d{1,10}$/.test(a)||/^(-)?\d{1,13}$/.test(a),/^[1-9]*[1-9][0-9]*$/.test(a)&&c){if(d=parseInt(a),/^(-)?\d{1,10}$/.test(d))d=1e3*d;else if(/^(-)?\d{1,13}$/.test(d))d=1e3*d;else{if(!/^(-)?\d{1,14}$/.test(d))return alert("时间戳格式不正确"),void 0;d=100*d}return e=new Date(d),jet.parse({YYYY:e.getFullYear(),MM:jet.digit(e.getMonth()+1),DD:jet.digit(e.getDate()),hh:jet.digit(e.getHours()),mm:jet.digit(e.getMinutes()),ss:jet.digit(e.getSeconds())},b)}return f=jet.reMatch(a),g=new Date(f[0],parseInt(f[1])-1,f[2],f[3]||0,f[4]||0,f[5]||0),h=Math.round(g.getTime()/1e3),h},$.splitDate=function(a){var b=a.match(/\w+|d+/g);return{YYYY:parseInt(b[0]),MM:parseInt(b[1])||0,DD:parseInt(b[2])||0,hh:parseInt(b[3])||0,mm:parseInt(b[4])||0,ss:parseInt(b[5])||0}},$.getLunar=function(a,b){var d,e,f,c=this;return b=b||"YYYY-MM-DD hh:mm:ss",/YYYY-MM-DD/g.test(jet.isparmat(b))?(d=a.substr(0,4).replace(/^(\d{4})/g,"$1,")+a.substr(4).replace(/(.{2})/g,"$1,"),e=jet.isNum(a)?jet.reMatch(d):jet.reMatch(a),f=c.jeLunar(e[0],e[1]-1,e[2]),{nMonth:f.lnongMonth,nDays:f.lnongDate,yYear:parseInt(f.solarYear),yMonth:parseInt(f.solarMonth),yDays:parseInt(f.solarDate),cWeek:f.inWeekDays,nWeek:f.solarWeekDay}):void 0},jeDate):(alert("在引用jquery.jedate.js之前，先引用jQuery，否则无法使用 jeDate"),void 0)});
/** 分享接口 **/
!function(a,b){"function"==typeof define&&(define.amd||define.cmd)?define(function(){return b(a)}):b(a,!0)}(this,function(a,b){function c(b,c,d){a.WeixinJSBridge?WeixinJSBridge.invoke(b,e(c),function(a){h(b,a,d)}):k(b,d)}function d(b,c,d){a.WeixinJSBridge?WeixinJSBridge.on(b,function(a){d&&d.trigger&&d.trigger(a),h(b,a,c)}):d?k(b,d):k(b,c)}function e(a){return a=a||{},a.appId=D.appId,a.verifyAppId=D.appId,a.verifySignType="sha1",a.verifyTimestamp=D.timestamp+"",a.verifyNonceStr=D.nonceStr,a.verifySignature=D.signature,a}function f(a){return{timeStamp:a.timestamp+"",nonceStr:a.nonceStr,"package":a["package"],paySign:a.paySign,signType:a.signType||"SHA1"}}function g(a){return a.postalCode=a.addressPostalCode,delete a.addressPostalCode,a.provinceName=a.proviceFirstStageName,delete a.proviceFirstStageName,a.cityName=a.addressCitySecondStageName,delete a.addressCitySecondStageName,a.countryName=a.addressCountiesThirdStageName,delete a.addressCountiesThirdStageName,a.detailInfo=a.addressDetailInfo,delete a.addressDetailInfo,a}function h(a,b,c){"openEnterpriseChat"==a&&(b.errCode=b.err_code),delete b.err_code,delete b.err_desc,delete b.err_detail;var d=b.errMsg;d||(d=b.err_msg,delete b.err_msg,d=i(a,d),b.errMsg=d),c=c||{},c._complete&&(c._complete(b),delete c._complete),d=b.errMsg||"",D.debug&&!c.isInnerInvoke&&alert(JSON.stringify(b));var e=d.indexOf(":"),f=d.substring(e+1);switch(f){case"ok":c.success&&c.success(b);break;case"cancel":c.cancel&&c.cancel(b);break;default:c.fail&&c.fail(b)}c.complete&&c.complete(b)}function i(a,b){var c=a,d=q[c];d&&(c=d);var e="ok";if(b){var f=b.indexOf(":");e=b.substring(f+1),"confirm"==e&&(e="ok"),"failed"==e&&(e="fail"),-1!=e.indexOf("failed_")&&(e=e.substring(7)),-1!=e.indexOf("fail_")&&(e=e.substring(5)),e=e.replace(/_/g," "),e=e.toLowerCase(),("access denied"==e||"no permission to execute"==e)&&(e="permission denied"),"config"==c&&"function not exist"==e&&(e="ok"),""==e&&(e="fail")}return b=c+":"+e}function j(a){if(a){for(var b=0,c=a.length;c>b;++b){var d=a[b],e=p[d];e&&(a[b]=e)}return a}}function k(a,b){if(!(!D.debug||b&&b.isInnerInvoke)){var c=q[a];c&&(a=c),b&&b._complete&&delete b._complete,console.log('"'+a+'",',b||"")}}function l(a){if(!(v||w||D.debug||"6.0.2">A||C.systemType<0)){var b=new Image;C.appId=D.appId,C.initTime=B.initEndTime-B.initStartTime,C.preVerifyTime=B.preVerifyEndTime-B.preVerifyStartTime,I.getNetworkType({isInnerInvoke:!0,success:function(a){C.networkType=a.networkType;var c="https://open.weixin.qq.com/sdk/report?v="+C.version+"&o="+C.isPreVerifyOk+"&s="+C.systemType+"&c="+C.clientVersion+"&a="+C.appId+"&n="+C.networkType+"&i="+C.initTime+"&p="+C.preVerifyTime+"&u="+C.url;b.src=c}})}}function m(){return(new Date).getTime()}function n(b){x&&(a.WeixinJSBridge?b():r.addEventListener&&r.addEventListener("WeixinJSBridgeReady",b,!1))}function o(){I.invoke||(I.invoke=function(b,c,d){a.WeixinJSBridge&&WeixinJSBridge.invoke(b,e(c),d)},I.on=function(b,c){a.WeixinJSBridge&&WeixinJSBridge.on(b,c)})}if(!a.jWeixin){var p={config:"preVerifyJSAPI",onMenuShareTimeline:"menu:share:timeline",onMenuShareAppMessage:"menu:share:appmessage",onMenuShareQQ:"menu:share:qq",onMenuShareWeibo:"menu:share:weiboApp",onMenuShareQZone:"menu:share:QZone",previewImage:"imagePreview",getLocation:"geoLocation",openProductSpecificView:"openProductViewWithPid",addCard:"batchAddCard",openCard:"batchViewCard",chooseWXPay:"getBrandWCPayRequest",openEnterpriseRedPacket:"getRecevieBizHongBaoRequest",startSearchBeacons:"startMonitoringBeacons",stopSearchBeacons:"stopMonitoringBeacons",onSearchBeacons:"onBeaconsInRange",consumeAndShareCard:"consumedShareCard",openAddress:"editAddress"},q=function(){var a={};for(var b in p)a[p[b]]=b;return a}(),r=a.document,s=r.title,t=navigator.userAgent.toLowerCase(),u=navigator.platform.toLowerCase(),v=!(!u.match("mac")&&!u.match("win")),w=-1!=t.indexOf("wxdebugger"),x=-1!=t.indexOf("micromessenger"),y=-1!=t.indexOf("android"),z=-1!=t.indexOf("iphone")||-1!=t.indexOf("ipad"),A=function(){var a=t.match(/micromessenger\/(\d+\.\d+\.\d+)/)||t.match(/micromessenger\/(\d+\.\d+)/);return a?a[1]:""}(),B={initStartTime:m(),initEndTime:0,preVerifyStartTime:0,preVerifyEndTime:0},C={version:1,appId:"",initTime:0,preVerifyTime:0,networkType:"",isPreVerifyOk:1,systemType:z?1:y?2:-1,clientVersion:A,url:encodeURIComponent(location.href)},D={},E={_completes:[]},F={state:0,data:{}};n(function(){B.initEndTime=m()});var G=!1,H=[],I={config:function(a){D=a,k("config",a);var b=D.check===!1?!1:!0;n(function(){if(b)c(p.config,{verifyJsApiList:j(D.jsApiList)},function(){E._complete=function(a){B.preVerifyEndTime=m(),F.state=1,F.data=a},E.success=function(a){C.isPreVerifyOk=0},E.fail=function(a){E._fail?E._fail(a):F.state=-1};var a=E._completes;return a.push(function(){l()}),E.complete=function(b){for(var c=0,d=a.length;d>c;++c)a[c]();E._completes=[]},E}()),B.preVerifyStartTime=m();else{F.state=1;for(var a=E._completes,d=0,e=a.length;e>d;++d)a[d]();E._completes=[]}}),D.beta&&o()},ready:function(a){0!=F.state?a():(E._completes.push(a),!x&&D.debug&&a())},error:function(a){"6.0.2">A||(-1==F.state?a(F.data):E._fail=a)},checkJsApi:function(a){var b=function(a){var b=a.checkResult;for(var c in b){var d=q[c];d&&(b[d]=b[c],delete b[c])}return a};c("checkJsApi",{jsApiList:j(a.jsApiList)},function(){return a._complete=function(a){if(y){var c=a.checkResult;c&&(a.checkResult=JSON.parse(c))}a=b(a)},a}())},onMenuShareTimeline:function(a){d(p.onMenuShareTimeline,{complete:function(){c("shareTimeline",{title:a.title||s,desc:a.title||s,img_url:a.imgUrl||"",link:a.link||location.href,type:a.type||"link",data_url:a.dataUrl||""},a)}},a)},onMenuShareAppMessage:function(a){d(p.onMenuShareAppMessage,{complete:function(){c("sendAppMessage",{title:a.title||s,desc:a.desc||"",link:a.link||location.href,img_url:a.imgUrl||"",type:a.type||"link",data_url:a.dataUrl||""},a)}},a)},onMenuShareQQ:function(a){d(p.onMenuShareQQ,{complete:function(){c("shareQQ",{title:a.title||s,desc:a.desc||"",img_url:a.imgUrl||"",link:a.link||location.href},a)}},a)},onMenuShareWeibo:function(a){d(p.onMenuShareWeibo,{complete:function(){c("shareWeiboApp",{title:a.title||s,desc:a.desc||"",img_url:a.imgUrl||"",link:a.link||location.href},a)}},a)},onMenuShareQZone:function(a){d(p.onMenuShareQZone,{complete:function(){c("shareQZone",{title:a.title||s,desc:a.desc||"",img_url:a.imgUrl||"",link:a.link||location.href},a)}},a)},startRecord:function(a){c("startRecord",{},a)},stopRecord:function(a){c("stopRecord",{},a)},onVoiceRecordEnd:function(a){d("onVoiceRecordEnd",a)},playVoice:function(a){c("playVoice",{localId:a.localId},a)},pauseVoice:function(a){c("pauseVoice",{localId:a.localId},a)},stopVoice:function(a){c("stopVoice",{localId:a.localId},a)},onVoicePlayEnd:function(a){d("onVoicePlayEnd",a)},uploadVoice:function(a){c("uploadVoice",{localId:a.localId,isShowProgressTips:0==a.isShowProgressTips?0:1},a)},downloadVoice:function(a){c("downloadVoice",{serverId:a.serverId,isShowProgressTips:0==a.isShowProgressTips?0:1},a)},translateVoice:function(a){c("translateVoice",{localId:a.localId,isShowProgressTips:0==a.isShowProgressTips?0:1},a)},chooseImage:function(a){c("chooseImage",{scene:"1|2",count:a.count||9,sizeType:a.sizeType||["original","compressed"],sourceType:a.sourceType||["album","camera"]},function(){return a._complete=function(a){if(y){var b=a.localIds;b&&(a.localIds=JSON.parse(b))}},a}())},getLocation:function(a){},previewImage:function(a){c(p.previewImage,{current:a.current,urls:a.urls},a)},uploadImage:function(a){c("uploadImage",{localId:a.localId,isShowProgressTips:0==a.isShowProgressTips?0:1},a)},downloadImage:function(a){c("downloadImage",{serverId:a.serverId,isShowProgressTips:0==a.isShowProgressTips?0:1},a)},getLocalImgData:function(a){G===!1?(G=!0,c("getLocalImgData",{localId:a.localId},function(){return a._complete=function(a){if(G=!1,H.length>0){var b=H.shift();wx.getLocalImgData(b)}},a}())):H.push(a)},getNetworkType:function(a){var b=function(a){var b=a.errMsg;a.errMsg="getNetworkType:ok";var c=a.subtype;if(delete a.subtype,c)a.networkType=c;else{var d=b.indexOf(":"),e=b.substring(d+1);switch(e){case"wifi":case"edge":case"wwan":a.networkType=e;break;default:a.errMsg="getNetworkType:fail"}}return a};c("getNetworkType",{},function(){return a._complete=function(a){a=b(a)},a}())},openLocation:function(a){c("openLocation",{latitude:a.latitude,longitude:a.longitude,name:a.name||"",address:a.address||"",scale:a.scale||28,infoUrl:a.infoUrl||""},a)},getLocation:function(a){a=a||{},c(p.getLocation,{type:a.type||"wgs84"},function(){return a._complete=function(a){delete a.type},a}())},hideOptionMenu:function(a){c("hideOptionMenu",{},a)},showOptionMenu:function(a){c("showOptionMenu",{},a)},closeWindow:function(a){a=a||{},c("closeWindow",{},a)},hideMenuItems:function(a){c("hideMenuItems",{menuList:a.menuList},a)},showMenuItems:function(a){c("showMenuItems",{menuList:a.menuList},a)},hideAllNonBaseMenuItem:function(a){c("hideAllNonBaseMenuItem",{},a)},showAllNonBaseMenuItem:function(a){c("showAllNonBaseMenuItem",{},a)},scanQRCode:function(a){a=a||{},c("scanQRCode",{needResult:a.needResult||0,scanType:a.scanType||["qrCode","barCode"]},function(){return a._complete=function(a){if(z){var b=a.resultStr;if(b){var c=JSON.parse(b);a.resultStr=c&&c.scan_code&&c.scan_code.scan_result}}},a}())},openAddress:function(a){c(p.openAddress,{},function(){return a._complete=function(a){a=g(a)},a}())},openProductSpecificView:function(a){c(p.openProductSpecificView,{pid:a.productId,view_type:a.viewType||0,ext_info:a.extInfo},a)},addCard:function(a){for(var b=a.cardList,d=[],e=0,f=b.length;f>e;++e){var g=b[e],h={card_id:g.cardId,card_ext:g.cardExt};d.push(h)}c(p.addCard,{card_list:d},function(){return a._complete=function(a){var b=a.card_list;if(b){b=JSON.parse(b);for(var c=0,d=b.length;d>c;++c){var e=b[c];e.cardId=e.card_id,e.cardExt=e.card_ext,e.isSuccess=e.is_succ?!0:!1,delete e.card_id,delete e.card_ext,delete e.is_succ}a.cardList=b,delete a.card_list}},a}())},chooseCard:function(a){c("chooseCard",{app_id:D.appId,location_id:a.shopId||"",sign_type:a.signType||"SHA1",card_id:a.cardId||"",card_type:a.cardType||"",card_sign:a.cardSign,time_stamp:a.timestamp+"",nonce_str:a.nonceStr},function(){return a._complete=function(a){a.cardList=a.choose_card_info,delete a.choose_card_info},a}())},openCard:function(a){for(var b=a.cardList,d=[],e=0,f=b.length;f>e;++e){var g=b[e],h={card_id:g.cardId,code:g.code};d.push(h)}c(p.openCard,{card_list:d},a)},consumeAndShareCard:function(a){c(p.consumeAndShareCard,{consumedCardId:a.cardId,consumedCode:a.code},a)},chooseWXPay:function(a){c(p.chooseWXPay,f(a),a)},openEnterpriseRedPacket:function(a){c(p.openEnterpriseRedPacket,f(a),a)},startSearchBeacons:function(a){c(p.startSearchBeacons,{ticket:a.ticket},a)},stopSearchBeacons:function(a){c(p.stopSearchBeacons,{},a)},onSearchBeacons:function(a){d(p.onSearchBeacons,a)},openEnterpriseChat:function(a){c("openEnterpriseChat",{useridlist:a.userIds,chatname:a.groupName},a)}},J=1,K={};return r.addEventListener("error",function(a){if(!y){var b=a.target,c=b.tagName,d=b.src;if("IMG"==c||"VIDEO"==c||"AUDIO"==c||"SOURCE"==c){var e=-1!=d.indexOf("wxlocalresource://");if(e){a.preventDefault(),a.stopPropagation();var f=b["wx-id"];if(f||(f=J++,b["wx-id"]=f),K[f])return;K[f]=!0,wx.ready(function(){wx.getLocalImgData({localId:d,success:function(a){b.src=a.localData}})})}}}},!0),r.addEventListener("load",function(a){if(!y){var b=a.target,c=b.tagName;b.src;if("IMG"==c||"VIDEO"==c||"AUDIO"==c||"SOURCE"==c){var d=b["wx-id"];d&&(K[d]=!1)}}},!0),b&&(a.wx=a.jWeixin=I),I}});