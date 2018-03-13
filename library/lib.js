/********异步提交函数**************************/
//form支持多表单提交，中间用,隔开，url为提交地址
function Sub(form,url){
	//串联表单
	var formName = form.split(",");
	var serialize = "";
	var a = "";
	for(var i=0;i<formName.length;i++){
        if(serialize == ""){
		   a = "";
		}else{
		   a = "&";
		}
		serialize += a + $("[name="+formName[i]+"]").serialize();
	}	
	//异步提交数据
	$.post(url,serialize,function(data){
		//console.log(data);
		if(data.warn == 2){
			if(data.href){//如果异步返回的json参数中定义了重定向url，则跳转到本url

				window.location.href = data.href;
			}else{
				window.location.reload();
			}
		}else{
			console.log('1')
			warn(data.warn);
		}
	},"json");
}
/******************警示弹出层*****************/
function warn(warn){
	$("#warn").show();
	$("#warnWord").html(warn);
}
/******************选择省份，区域，城市*****************/
function region(FormName,Formprovince,Formcity,Formarea,root){
    var province = eval('document.' + FormName + "." + Formprovince);
    var city = eval('document.' + FormName + "." + Formcity);
    var area = eval('document.' + FormName + "." + Formarea);
	//根据省份返回城市
	province.onchange = function(){
	    area.innerHTML = "<option value=''>--区域--</option>";
		$.post(root+"library/libData.php",{ProvincePostCity:this.value},function(data){
		    city.innerHTML = data.city;
		},"json");	
	}
	//根据省份返回城市
	city.onchange = function(){
	    $.post(root+"library/libData.php",{ProvincePostArea:province.value,CityPostArea:this.value},function(data){
		    area.innerHTML = data.area;
		},"json");	
	}
}
/*******获取本地路径*****************/
//sourceId：文件域ID号
function getFileUrl(sourceId){ 
	var url; 
	if(navigator.userAgent.indexOf("MSIE")>=1) { // IE 
		url = document.getElementById(sourceId).value; 
	}else if(navigator.userAgent.indexOf("Firefox")>0) { // Firefox 
		url = window.URL.createObjectURL(document.getElementById(sourceId).files.item(0)); 
	}else if(navigator.userAgent.indexOf("Chrome")>0) { // Chrome 
		url = window.URL.createObjectURL(document.getElementById(sourceId).files.item(0)); 
	}else{
		url = window.URL.createObjectURL(document.getElementById(sourceId).files.item(0)); 
	}
	return url; 
}
