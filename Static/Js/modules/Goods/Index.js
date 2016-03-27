define(['avalon', "text!./Index.html", 'jquery', 'pagination', 'Request'], function(avalon, index, $, pagination, Request){
    avalon.templateCache.index = index;
    var Index = avalon.define({
        $id: "Index",
        items: [{"title": '111111', "created": '2016-03-02'},{"title": '22222', "created": '2016-03-02'}]
    });
   avalon.vmodels.main.Page = "index"
   var r = new Request();
    r.successCallBack = function(data) {
        Index.items = data.data;
    };
    r.Get('Goods');
    // 创建分页
    setTimeout(function(){
        $("#Pagination").pagination(10, {
            num_edge_entries: 1, //边缘页数
            num_display_entries: 4, //主体页数
            callback: function (page_index, jq){
                console.log(page_index);
                return false;
            },
            items_per_page:1 //每页显示1项
        });
    }, 300);
});