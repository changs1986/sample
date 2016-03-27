require.config({
    baseUrl: 'Js',
    paths:{
        avalon: 'vendor/avalon',
        domReady:'vendor/require/domReady',
        text:'vendor/require/text',
        mmHistory: 'vendor/mmHistory',
        mmRouter: 'vendor/mmRouter',
        jquery: 'vendor/jquery.min',
        pagination: 'vendor/jquery.pagination',
        Request: 'vendor/Request'
    },
    priority: ['text'],
    shim:{
        jquery: {
            exports: "$"
        },
        avalon: { exports: "avalon" },
        mmHistory:{ deps: ['avalon']},
        mmRouter:{ deps: ['avalon']},
        Request: { deps: ['jquery']},
        pagination : {
            deps : ["jquery"]
        }
    }
});

require([ 'mmHistory','mmRouter',"domReady!"], function() {
    var vm = avalon.define({
       $id: 'main',
       idx: 1,
       currPath: "",
       Page: "empty",
    });

    avalon.router.get("/*path", function () {
        console.log(this.path)
        if(this.path === "/Goods"){
            require(['./modules/Goods/Index'], function() {//第三块，加载其他模块
                avalon.log("加载其他完毕")
            });
        }else if (this.path === "/Order") {
            require(['./modules/Order/Index'], function() {//第三块，加载其他模块
                avalon.log("加载订单完毕")
            });
        }
    });
    avalon.history.start({
        basepath: "/sample/Static"
    })
    avalon.scan();
});