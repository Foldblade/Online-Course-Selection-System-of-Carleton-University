<?php require_once "func.php"; ?>
<?php auth(); ?>
<?php privilege_check(array("secretary")); ?>
<?php require "header.php"; ?>

<script>
    document.getElementById("pageTitle").innerText = "已审核列表";
    document.getElementById("LAuditedCourse").classList.add("mdui-list-item-active");
</script>
<div class="mdui-container">
    <div class="mdui-row">
        <div class="mdui-col-xs-12" id="courseCard">
            <div class="mdui-card mdui-m-y-3">
                <!-- 卡片的标题和副标题 -->
                <div class="mdui-card-primary">
                  <div class="mdui-card-primary-title">已审核列表</div>
                </div>

                <!-- 卡片的内容 -->
                <div class="mdui-card-content">
                    <div class="mdui-container-fluid">
                        <div class="mdui-row">
                            <div class="mdui-col-xs-12">
                                <div class="mdui-progress" id="loading">
                                    <div class="mdui-progress-indeterminate"></div>
                                </div>
                                <div class="mdui-table-fluid">
                                    <table class="mdui-table mdui-table-hoverable">
                                        <thead>
                                            <tr>
                                                <th>用户名</th>
                                                <th class="mdui-table-col-numeric">数量</th>
                                                <th>选课内容</th>
                                                <th>审核状态</th>
                                            </tr>
                                        </thead>
                                        <tbody id="courseTbody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mdui-col-xs-12">
                                <div class="mdui-container mdui-p-t-5">
                                    <div class="mdui-col-xs-12 mdui-text-center">
                                        <div class="mdui-btn-group" id="pageButtonAreas">
                                            <!--页码按钮区-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var search = function (page = 1) {
        $('html, body').animate({scrollTop: $('#courseCard').offset().top-40}, 1000); // 页面移动到卡片顶部
        $("#loading").removeClass("mdui-hidden"); // 加载进度条
        $.ajax({ 
            type: "GET",  
            url: "api.php",  
            data: "getAuditedQuery=&page="+page,  
            async: false,  
            error: function(request) {
                mdui.alert("网络连接失败", function() {}, {"confirmText": "好的"}); 
            },  
            success: function(data, textStatus) {
                // console.log(data);
                let res = JSON.parse(data);
                if (res["status"] == "success") { // 查询成功
                    $("#courseTbody").empty(); // 清空表格内容
                    $("#pageButtonAreas").empty(); // 清空页码按钮内容
                    console.log(res);
                    if(res["data"].length == 0) {
                        mdui.snackbar({message: "暂时没用审核过的内容", position: 'bottom'});
                    } else {
                        for (let i in res["data"]) {
                            let status = "";
                            if(res["data"][i]["audited"] == "1") {
                                status = "通过";
                            } else if (res["data"][i]["audited"] == "0") {
                                status = "打回";
                            }
                            $("#courseTbody").append('\
                            <tr>\
                                <td>'+res["data"][i]["user"]+'<input class="mdui-hidden" value="'+res["data"][i]["userID"]+'"></td>\
                                <td>'+res["data"][i]["count"]+'</td>\
                                <td>'+res["data"][i]["choices"]+'</td>\
                                <td>'+status+'</td>\
                            </tr>\
                            ');
                        }

                        mdui.snackbar({message: "查询成功", position: 'bottom'});
                        mdui.updateTables();

                        /* 添加页码按钮 */
                        $("#pageButtonAreas").append('<button type="button" class="mdui-btn mdui-text-color-theme" id="firstPageButton" value="1" mdui-tooltip="{content: \'第一页\', position: \'top\'}"><i class="mdui-icon material-icons">first_page</i></button>');
                        if (res["totalPages"] <= 5) { // 总页数小于等于5
                            for(let i = 1; i <= res["totalPages"]; i++) {
                                $("#pageButtonAreas").append('<button type="button" class="mdui-btn mdui-text-color-theme" id="PageButton'+i+'" value="'+i+'">'+i+'</button>');
                            }
                        } else { // 总页数大于5
                            if(res["thisPage"] >= (res["totalPages"]-2)) { // 在最末3页中
                                for (let i = res["totalPages"]-4; i <= res["totalPages"]; i++) {
                                    $("#pageButtonAreas").append('<button type="button" class="mdui-btn mdui-text-color-theme" id="PageButton'+i+'" value="'+i+'">'+i+'</button>');
                                }
                            } else if (res["thisPage"] <= 3) {  // 在最前3页中
                                for (let i = 1; i <= 5; i++) {
                                    $("#pageButtonAreas").append('<button type="button" class="mdui-btn mdui-text-color-theme" id="PageButton'+i+'" value="'+i+'">'+i+'</button>');
                                }
                            } else {
                                for (let i = res["thisPage"]-2; i <= res["thisPage"]+2; i++) {
                                    $("#pageButtonAreas").append('<button type="button" class="mdui-btn mdui-text-color-theme" id="PageButton'+i+'" value="'+i+'">'+i+'</button>');
                                }
                            }
                        }
                        $("#pageButtonAreas").append('<button type="button" class="mdui-btn mdui-text-color-theme" id="lastPageButton" value="'+res["totalPages"]+'" mdui-tooltip="{content: \'最末页\', position: \'top\'}"><i class="mdui-icon material-icons">last_page</i></button>');
                        
                        /* 为当前页码按钮添加active */
                        $("#PageButton"+res["thisPage"]).addClass("mdui-btn-active");

                        /* 为所有页码按钮绑定查询 */
                        $("#pageButtonAreas").children().click(function(){
                            search($(this).val());
                        });
                    }
                } else {
                    mdui.snackbar({message: "暂时没有需要审核的内容", position: 'bottom'}); 
                }
            }
        });
        $("#loading").addClass("mdui-hidden"); // 隐藏进度条
    }
    window.onload = function() {
        search();
    }
</script>
<?php require "footer.php"; ?>