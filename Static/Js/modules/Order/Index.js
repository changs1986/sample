define(['avalon', "text!./Index.html", 'jquery', 'pagination'], function(avalon, index, $, pagination){
    avalon.templateCache.orderIndex = index;
    var Index = avalon.define({
        $id: "OrderIndex",
        items: [{"title": '订单1', "created": '2016-03-02'},{"title": '订单2', "created": '2016-03-02'}]
    });
   avalon.vmodels.main.Page = "orderIndex"
    // 创建分页
    setTimeout(function(){
        $("#Pagination").pagination(10, {
            num_edge_entries: 1, //边缘页数
            num_display_entries: 5, //主体页数
            callback: function (page_index, jq){
                console.log(page_index);
                return false;
            },
            items_per_page:1 //每页显示1项
        });
    }, 300);
});