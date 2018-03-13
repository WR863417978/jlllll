<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>上新专区</title>
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../library/css/new_area.css">
    <link rel="stylesheet" href="../library/css/mescroll.min.css"></link>
</head>

<body>
    <div class="new-area">
        <!-- 页头 -->
        <div class="order-header">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="title">上新</span>
            <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
        </div>
        <!-- 内容 -->
        <div class="new-title">
            亲，以下是产品上新，数量有限，先到先得哦
        </div>
        <!-- 产品列表 -->
        <div id="mescroll" class="mescroll">

            <div class="pro-items" id="proItems">
                <div class="produce" v-for="pd in pdlist">
                    <div class="img">
                        <img :src="pd.pdImg" alt="">
                    </div>
                    <p>{{pd.pdName}}</p>
                    <p class="price">￥{{pd.pdPrice}}</p>
                </div>
                <!-- <div class="produce">
                    <div class="img">
                        <img src="" alt="">
                    </div>
                    <p>产品名称</p>
                    <p class="price">￥123</p>
                </div>
                <div class="produce">
                    <div class="img">
                        <img src="" alt="">
                    </div>
                    <p>产品名称</p>
                    <p class="price">￥123</p>
                </div>
                <div class="produce">
                    <div class="img">
                        <img src="" alt="">
                    </div>
                    <p>产品名称</p>
                    <p class="price">￥123</p>
                </div> -->
            </div>
        </div>
    </div>

    <script src="../library/js/mescroll.min.js"></script>
    <script src="https://cdn.bootcss.com/vue/2.4.2/vue.min.js"></script>
    <!-- <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script> -->
    <!-- 模拟数据 -->
    <script src="../library/res/pdlist1.js" type="text/javascript" charset="utf-8"></script>

    <script>
        var vm = new Vue({
            el: "#proItems",
            data: {
                mescroll: null,
                pdlist: []
            },
            mounted: function() {
                //创建MeScroll对象,down可以不用配置,因为内部已默认开启下拉刷新,重置列表数据为第一页
                //解析: 下拉回调默认调用mescroll.resetUpScroll(); 而resetUpScroll会将page.num=1,再执行up.callback,从而实现刷新列表数据为第一页;
                var self = this;
                self.mescroll = new MeScroll("mescroll", { //请至少在vue的mounted生命周期初始化mescroll,以确保您配置的id能够被找到
                    up: {
                        callback: self.upCallback, //上拉回调
                        //以下参数可删除,不配置
                        isBounce: false, //此处禁止ios回弹,解析(务必认真阅读,特别是最后一点): http://www.mescroll.com/qa.html#q10
                        //page:{size:8}, //可配置每页8条数据,默认10
                        toTop: { //配置回到顶部按钮
                            src: "../res/img/mescroll-totop.png", //默认滚动到1000px显示,可配置offset修改
                            //html: null, //html标签内容,默认null; 如果同时设置了src,则优先取src
                            //offset : 1000
                        },
                        empty: { //配置列表无任何数据的提示
                            warpId: "dataList",
                            icon: "../res/img/mescroll-empty.png",
                            //						  	tip : "亲,暂无相关数据哦~" , 
                            //						  	btntext : "去逛逛 >" , 
                            //						  	btnClick : function() {
                            //						  		alert("点击了去逛逛按钮");
                            //						  	} 
                        },
                        //vue的案例请勿配置clearId和clearEmptyId,否则列表的数据模板会被清空
                        //vue的案例请勿配置clearId和clearEmptyId,否则列表的数据模板会被清空
                        //						clearId: "dataList",
                        //						clearEmptyId: "dataList"
                    }
                });
            },
            methods: {
                //上拉回调 page = {num:1, size:10}; num:当前页 ,默认从1开始; size:每页数据条数,默认10
                upCallback: function(page) {
                    //联网加载数据
                    var self = this;
                    getListDataFromNet(page.num, page.size, function(curPageData) {
                        //curPageData=[]; //打开本行注释,可演示列表无任何数据empty的配置

                        //如果是第一页需手动制空列表 (代替clearId和clearEmptyId的配置)
                        if (page.num == 1) self.pdlist = [];

                        //更新列表数据
                        self.pdlist = self.pdlist.concat(curPageData);

                        //联网成功的回调,隐藏下拉刷新和上拉加载的状态;
                        //mescroll会根据传的参数,自动判断列表如果无任何数据,则提示空;列表无下一页数据,则提示无更多数据;
                        console.log("page.num=" + page.num + ", page.size=" + page.size + ", curPageData.length=" + curPageData.length + ", self.pdlist.length==" + self.pdlist.length);

                        //方法一(推荐): 后台接口有返回列表的总页数 totalPage
                        //self.mescroll.endByPage(curPageData.length, totalPage); //必传参数(当前页的数据个数, 总页数)

                        //方法二(推荐): 后台接口有返回列表的总数据量 totalSize
                        //self.mescroll.endBySize(curPageData.length, totalSize); //必传参数(当前页的数据个数, 总数据量)

                        //方法三(推荐): 您有其他方式知道是否有下一页 hasNext
                        //self.mescroll.endSuccess(curPageData.length, hasNext); //必传参数(当前页的数据个数, 是否有下一页true/false)

                        //方法四 (不推荐),会存在一个小问题:比如列表共有20条数据,每页加载10条,共2页.如果只根据当前页的数据个数判断,则需翻到第三页才会知道无更多数据,如果传了hasNext,则翻到第二页即可显示无更多数据.
                        self.mescroll.endSuccess(curPageData.length);

                    }, function() {
                        //联网失败的回调,隐藏下拉刷新和上拉加载的状态;
                        self.mescroll.endErr();
                    });
                },
            },
        });

        function getListDataFromNet(pageNum, pageSize, successCallback, errorCallback) {
            //延时一秒,模拟联网
            setTimeout(function() {
                //          	axios.get("xxxxxx", {
                //					params: {
                //						num: pageNum, //页码
                //						size: pageSize //每页长度
                //					}
                //				})
                //				.then(function(response) {
                var data = pdlist1; // 模拟数据: ../res/pdlist1.js
                var listData = []; //模拟分页数据
                for (var i = (pageNum - 1) * pageSize; i < pageNum * pageSize; i++) {
                    if (i == data.length) break;
                    listData.push(data[i]);
                }
                successCallback && successCallback(listData); //成功回调
                //				})
                //				.catch(function(error) {
                //					errorCallback&&errorCallback()//失败回调
                //				});
            }, 500)
        }
    </script>
</body>

</html>