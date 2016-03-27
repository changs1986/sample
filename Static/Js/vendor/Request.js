/**
 * Created by chanceJaw on 15/11/12.
 */
define(function(){
    //可在实例化时传入 {user_id:xx, token:yy}
    var Request = function() {
        this.host = 'http://' + document.domain+'/sample';
        this.apiPrefix = this.host + '/api.php/';

        var setting = {};
        if (typeof(arguments[0]) != 'undefined') {
            setting = arguments[0];
        }
        var user_id = '';
        var token   = '';

        this.mySetting = $.extend({user_id: user_id,token: token}, setting);
    };

    /**
     * 请求中
     */

    Request.prototype.beforeCallback = function(e,xhr,o){

    };

    /**
     * 请求完成
     */

    Request.prototype.completeCallback = function(msg){

    };

    /**
     * 成功回调函数
     * @param data
     */
    Request.prototype.successCallBack = function(data) {

    };

    /**
     * 失败回调函数
     * @param data
     */
    Request.prototype.failCallBack = function(data){

    };

    Request.prototype.buildParam = function(method, data) {
        data = typeof(data) == 'undefined' ? new Array() : data;
        data['user_id'] = this.mySetting.user_id;
        data['token'] = this.mySetting.token;
        if (method == 'GET') {
            var dataStr = '';
            for (var k in data)
            {
                if (!data.hasOwnProperty(k))
                {
                    continue;
                }
                dataStr = dataStr + '&' + encodeURIComponent(k) + '=' + encodeURIComponent(data[k]);
            }
            return dataStr.substring(1);
        }
        return data;
    };

    /**
     * 发出请求
     * @param method
     * @param url
     * @param data
     */
    Request.prototype.quest = function(method, url, data) {
        var obj = this;
        $.ajax({
            type : method,
            headers: {
                Accept: "application/json; charset=utf-8"
            },
            beforeSend: this.beforeCallback,
            complete: this.completeCallback,
            url : this.apiPrefix + url,
            data : this.buildParam(method, data)
        }).done(function(data){
            if (typeof obj.successCallBack == 'function')
            {
                obj.successCallBack(data);
            }
        }).fail(function(data){
            //layer.alert("请求发生错误，请稍后重试");
            var responseContent = JSON.parse(data.responseText);
            if (data.status == 403 ) {
                //未登录或登录过期
                alert(responseContent.description);
                setInterval(function () {
                    var url = window.location.href;
                    if (0 <= url.indexOf('Login')) {
                        return false;
                    }
                    if (0 <= url.indexOf('admin')) {
                        window.location.replace(obj.host + '/admin.php/Login/Login.html');
                    } else {
                        window.location.replace(obj.host + '/index.php/Index/Login');
                    }
                }, 1000);
            } else if (data.status == 401 || data.ret_code == 400 || data.ret_code == 405) {
                //没有操作权限或参数错误
                alert(responseContent.description);
                obj.failCallBack(data);
                return;
            }
            console.log(data);
            obj.failCallBack(data);
        });
    };

    Request.prototype.Get = function(url, data) {
        this.quest('GET', url, data);
    };

    Request.prototype.Post = function(url, data) {
        this.quest('POST', url, data);
    };

    Request.prototype.Put = function(url, data) {
        this.quest('PUT', url, data);
    };

    Request.prototype.Delete = function(url, data) {
        this.quest('DELETE', url, data);
    };

    return Request;
});
