/**
 * 单条记录删除
 * @param url 操作路由
 * @param del_id 删除字段ID
 * @returns {boolean}
 */
function delete_one(url, del_id){
    layer.confirm('确定要删除吗？', {
        btn: ['确定','取消']
    }, function(){
        $.ajax({
            type: 'get',
            url:  url,
            dataType: 'json',
            data: {id: del_id},
            success: function(ret){
                console.log(ret);
                if(ret.code == 0) {
                    layer.msg('删除成功',{
                        time:1000, icon: 6,
                        end:function () {
                            location.href = location.href;
                        }
                    })
                } else {
                    layer.msg(ret.msg, {icon: 5,time:1000,});
                    return false;
                }
            }
        });
    }, function(){
    });
    return false;
}

/**
 * 多行删除
 * @param url 删除路径
 * @param del_ids 删除的ID 多个用 逗号 隔开
 * @returns {boolean}
 */
function delete_multiply(url, del_ids){
    layer.confirm('确定要删除吗？', {
        btn: ['确定','取消']
    }, function()
        {
            $.ajax({
                type: 'get',
                url:  url,
                dataType: 'json',
                data: {
                    id : del_ids
                },
                success: function(ret){
                    console.log(ret);
                    if(ret.code == 0) {
                        layer.msg('删除成功',{
                            time:1000, icon: 6,
                            end:function () {
                                // window.history.go(-1);
                                window.location.reload();
                                // location.href = location.href;
                            }
                        })
                    } else {
                        layer.msg(ret.msg, {icon: 5,time:1000,});
                        return false;
                    }
                }
            });
        },
        function() {}
    );
    return false;
}

/**
 *  修改排序
 * @param url
 * @param id
 * @param sort_id
 * @returns {boolean}
 */
function edit_sort(url, id, sort_id) {
    var reg = /^\d+$/;
    if (!(reg.test(sort_id))) {
        alert('排序请输入正整数!');
        return false;
    }
    $.ajax({
        type: 'get',
        url:  url,
        dataType: 'json',
        data: {
            id : id,
            sort_id : parseInt(sort_id),
        },
        success: function(ret){
            console.log(ret);
            if(ret.code == 0) {
                layer.msg('修改成功',{
                    time:1000, icon: 6,
                    end:function () {
                        location.href = location.href;
                    }
                })
            } else {
                layer.msg(ret.msg, {icon: 5,time:1000,});
                return false;
            }
        }
    });
    return false;
}

/**
 * 切换转世状态
 * @param url 路由
 * @param id  当前列id
 * @param type 切换类型，针对一行数据有多个字段需要切换
 * @returns {boolean}
 */
function change_show_status(url, id, type = 1) {
    $.ajax({
        type: 'get',
        url:  url,
        dataType: 'json',
        data: {
            id : id,
            type: type
        },
        success: function(ret){
            console.log(ret);
            if(ret.code == 0) {
                layer.msg('修改成功',{
                    time:1000, icon: 6,
                    end:function () {
                        location.href = location.href;
                    }
                })
            } else {
                layer.msg(ret.msg, {icon: 5,time:1000,});
                return false;
            }
        }
    });
    return false;
}
