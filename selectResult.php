<?php require_once "func.php"; ?>
<?php auth(); ?>
<?php privilege_check(array("student")); ?>
<?php require "header.php"; ?>

<script>
    document.getElementById("pageTitle").innerText = "选课结果";
    document.getElementById("LSelectResult").classList.add("mdui-list-item-active");
</script>
<div class="mdui-container">
    <div class="mdui-row">
        <div class="mdui-col-xs-12" id="courseCard">
            <div class="mdui-card mdui-m-y-3">
                <!-- 卡片的标题和副标题 -->
                <div class="mdui-card-primary">
                  <div class="mdui-card-primary-title">选课结果</div>
                </div>

                <!-- 卡片的内容 -->
                <div class="mdui-card-content">
                    <div class="mdui-container-fluid">
                        <div class="mdui-row">
                            <div class="mdui-col-xs-12 mdui-typo mdui-m-y-2">
                                <div>
                                    <i class="mdui-icon material-icons">access_time</i>
                                    <span class="mdui-typo-body-2-opacity">选课内容最后修改时间：</span>
                                    <span class="mdui-typo-body-1-opacity" id="lastModifyTime"></span>
                                </div>
                                <div>
                                    <i class="mdui-icon material-icons">check_circle_outline</i>
                                    <span class="mdui-typo-body-2-opacity">审核状态最后更新时间：</span>
                                    <span class="mdui-typo-body-1-opacity" id="lastAuditTime"></span>
                                </div>
                                <div>
                                    <i class="mdui-icon material-icons">report</i>
                                    <span class="mdui-typo-body-2-opacity">最新审核状态：</span>
                                    <span class="mdui-typo-body-1-opacity" id="auditStatus"></span>
                                </div>
                            </div>
                            <div class="mdui-col-xs-12">
                                <div class="mdui-progress" id="loading">
                                    <div class="mdui-progress-indeterminate"></div>
                                </div>
                                <div class="mdui-table-fluid">
                                    <table class="mdui-table mdui-table-hoverable">
                                        <thead>
                                            <tr>
                                                <th>课程名</th>
                                                <th class="mdui-table-col-numeric">学分</th>
                                                <th class="mdui-table-col-numeric">总学时</th>
                                                <th>开设院系</th>
                                                <th>授课语言</th>
                                                <th>类型</th>
                                                <th>校选课类别</th>
                                                <th>详情</th>
                                            </tr>
                                        </thead>
                                        <tbody id="courseTbody">
                                        </tbody>
                                    </table>
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
            data: "getSelectResult=&userID=<?php echo getUserID(); ?>",  
            async: false,  
            error: function(request) {
                mdui.alert("网络连接失败", function() {}, {"confirmText": "好的"}); 
            },  
            success: function(data, textStatus) {
                // console.log(data);
                let res = JSON.parse(data);
                if (res["status"] == "success") { // 查询成功
                    $("#courseTbody").empty(); // 清空表格内容
                    // console.log(res);
                    $("#lastAuditTime").html(res["lastAuditTime"]);
                    $("#lastModifyTime").html(res["lastModifyTime"]);
                    if(res["data"].length == 0) {
                        $("#auditStatus").html("<b>请进行选课操作</b>");
                        mdui.snackbar({message: "查询无结果，请进行选课操作", position: 'bottom'});
                    } else {
                        if(res["audited"] == null) { // 尚未审核或尚未提交审核
                            if(res["lastAuditTime"] == null) {
                                $("#auditStatus").html("<b>选课尚未提交审核，请前往<a href='selectCourse.php'>我的选课</a>页面提交审核。</b>");
                                mdui.snackbar({message: "选课尚未提交审核", position: 'bottom'});
                            } else {
                                $("#auditStatus").html("<b>选课尚未进行审核</b>");
                            }
                        } else if(res["audited"] == 0) {  // 未通过
                            $("#auditStatus").html("<b>选课审核未通过。请前往<a href='selectCourse.php'>我的选课</a>页面修改选课内容后重新提交审核。</b>");
                        } else if(res["audited"] == 1) {  // 通过审核
                            //把字符串格式转化为日期类
                            let lastAuditTime = new Date(Date.parse(res["lastAuditTime"]));
                            let lastModifyTime = new Date(Date.parse(res["lastModifyTime"]));
                            //进行比较
                            if(lastAuditTime > lastModifyTime) {
                                $("#auditStatus").html("选课审核已通过");
                                for (let i in res["data"]) {
                                    $("#courseTbody").append('\
                                        <tr>\
                                            <td>'+res["data"][i]["name"]+'</td>\
                                            <td>'+res["data"][i]["score"]+'</td>\
                                            <td>'+res["data"][i]["totalTime"]+'</td>\
                                            <td>'+res["data"][i]["attribution"]+'</td>\
                                            <td>'+res["data"][i]["language"]+'</td>\
                                            <td>'+res["data"][i]["type"]+'</td>\
                                            <td>'+res["data"][i]["category"]+'</td>\
                                            <td class="mdui-text-right"><a href="detail.php?courseID='+res["data"][i]["courseID"]+'" target="_blank" class="mdui-btn mdui-btn-icon mdui-btn-dense mdui-color-theme-accent mdui-ripple"><i class="mdui-icon material-icons">more_horiz</i></a></td>\
                                        </tr>\
                                    ');
                                }
                            } else {
                                $("#auditStatus").html("<b>在审核结束后修改了选课。请前往<a href='selectCourse.php'>我的选课</a>页面提交审核。</b>");
                            }
                        }
                        mdui.snackbar({message: "查询成功", position: 'bottom'});
                        mdui.updateTables();
                    }
                } else {
                    mdui.snackbar({message: "查询失败，请稍后再试", position: 'bottom'}); 
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