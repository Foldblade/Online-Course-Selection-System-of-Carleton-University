<?php require "header.php"; ?>
<?php require_once "func.php"; ?>
<?php auth(); ?>

<script>
    document.getElementById("pageTitle").innerText = "课程库";
    document.getElementById("LCoursesLibrary").classList.add("mdui-list-item-active");
</script>
<div class="mdui-container">
    <div class="mdui-row">
        <div class="mdui-col-xs-12">
            <div class="mdui-panel mdui-m-t-3" mdui-panel>
                <div class="mdui-panel-item mdui-panel-item-open">
                    <div class="mdui-panel-item-header">
                        <div class="mdui-panel-item-title mdui-m-y-1" style="font-size: 18px"><i class="mdui-icon material-icons mdui-m-r-1">search</i>课程查询</div>
                        <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                    </div>
                    <div class="mdui-panel-item-body">
                        <div class="mdui-container">
                            <div class="mdui-row">
                            <form id="searchForm">
                                <div class="mdui-col-xs-6 mdui-col-md-3 mdui-m-b-3 mdui-p-x-3">
                                    <label class="mdui-textfield-label">开设院系</label>
                                    <select name="attribution" id="attribution" class="mdui-select" style="width: 100%">
                                        <option value="" selected>全部</option>
                                        <?php 
                                            foreach(listCourseAttribution() as $attribution) {
                                                echo "<option value=\"$attribution\">$attribution</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="mdui-col-xs-6 mdui-col-md-3 mdui-m-b-3 mdui-p-x-3">
                                    <label class="mdui-textfield-label">授课语种</label>
                                    <select name="language" id="language" class="mdui-select" style="width: 100%">
                                        <option value="" selected>全部</option>
                                        <?php 
                                            foreach(listCourseLanguage() as $language) {
                                                echo "<option value=\"$language\">$language</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="mdui-col-xs-6 mdui-col-md-3 mdui-m-b-3 mdui-p-x-3">
                                    <label class="mdui-textfield-label">课程类型</label>
                                    <select name="type" id="type" class="mdui-select" style="width: 100%">
                                        <option value="" selected>全部</option>
                                        <?php 
                                            foreach(listCourseType() as $type) {
                                                echo "<option value=\"$type\">$type</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="mdui-col-xs-6 mdui-col-md-3 mdui-m-b-3 mdui-p-x-3">
                                    <label class="mdui-textfield-label">校选课类别</label>
                                    <select name="category" id="category" class="mdui-select" style="width: 100%" disabled>
                                        <option value="" selected>全部</option>
                                        <?php 
                                            foreach(listCourseCategory() as $category) {
                                                if ($category != "") {
                                                    echo "<option value=\"$category\">$category</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                    <script>
                                        $("#type").change(function() {
                                            if($("#type").val() == "校选课" && $("#category").attr("disabled") == "disabled") {
                                                $("#category").removeAttr("disabled");
                                            } else { // 非选中校选课
                                                $('#category').val(""); // 选回全部
                                                $("#category").attr("disabled", ""); // 禁用
                                            }
                                        });
                                    </script>
                                </div>
                                <div class="mdui-col-xs-12 mdui-m-b-3 mdui-p-x-3">
                                    <div class="mdui-textfield mdui-textfield-floating-label mdui-p-a-0">
                                        <label class="mdui-textfield-label">课程名称</label>
                                        <input class="mdui-textfield-input" name="name" id="name" type="text" pattern="^[\u4e00-\u9fa5_a-zA-Z0-9.]+$"/>
                                    </div>
                                </div>
                                <!-- 隐藏的input，传递API路径 -->
                                <input class="mdui-hidden" name="searchCourse"/>
                            </form>
                            </div>
                        </div>
                        <div class="mdui-panel-item-actions">
                            <button class="mdui-btn mdui-ripple mdui-text-color-theme-accent" mdui-panel-item-close>收起</button>
                            <button class="mdui-btn mdui-ripple mdui-text-color-theme-accent" id="search">查询</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mdui-col-xs-12">
            <div class="mdui-card mdui-m-y-3">
                <!-- 卡片的标题和副标题 -->
                <div class="mdui-card-primary">
                  <div class="mdui-card-primary-title">课程列表</div>
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
                                                <th>课程名</th>
                                                <th class="mdui-table-col-numeric">学分</th>
                                                <th class="mdui-table-col-numeric">总学时</th>
                                                <th>开设院系</th>
                                                <th>授课语言</th>
                                                <th>类型</th>
                                                <th>选修课类别</th>
                                                <th></th>
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
        scrollTo(0,0); // 滚回顶部
        $("#loading").removeClass("mdui-hidden"); // 加载进度条
        $.ajax({ 
            type: "POST",  
            url: "api.php",  
            data: $('#searchForm').serialize()+"&page="+page,  
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
                    // console.log(res["totalPages"]);
                    if(res["data"].length == 0) {
                        mdui.snackbar({message: "查询无结果，建议修改查询参数再试", position: 'bottom'});
                    } else {
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
                                <td class="mdui-text-right"><a href="detail.php?courseID='+res["data"][i]["courseID"]+'" target="_blank" class="mdui-btn mdui-btn-dense mdui-color-theme-accent mdui-ripple">详情</a></td>\
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
                    mdui.alert("查询失败，请稍后再试", function() {}, {"confirmText": "好的"}); 
                }
            }
        });
        $("#loading").addClass("mdui-hidden"); // 隐藏进度条
    }
    $("#search").click(function() {
        search();
    });
    window.onload = function() {
        $("#search").click();
    }
</script>
<?php require "footer.php"; ?>