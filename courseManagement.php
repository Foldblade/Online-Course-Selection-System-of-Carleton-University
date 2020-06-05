<?php require_once "func.php"; ?>
<?php auth(); ?>
<?php privilege_check(array("secretary")); ?>
<?php require "header.php"; ?>

<script>
    document.getElementById("pageTitle").innerText = "课程管理";
    document.getElementById("LCourseManagement").classList.add("mdui-list-item-active");
</script>

<div class="mdui-container">
    <div class="mdui-row">
        <div class="mdui-col-xs-12">
            <div class="mdui-panel mdui-m-y-3" mdui-panel>
                <div class="mdui-panel-item mdui-panel-item-open">
                    <div class="mdui-panel-item-header">
                        <div class="mdui-panel-item-title mdui-m-y-1" style="font-size: 18px"><i class="mdui-icon material-icons mdui-m-r-1">post_add</i>添加课程</div>
                        <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                    </div>
                    <div class="mdui-panel-item-body">
                        <div class="mdui-container">
                            <div class="mdui-row">
                            <form id="addForm" enctype="multipart/form-data">
                                <div class="mdui-col-xs-12 mdui-col-md-6 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">collections_bookmark</i>
                                        <label class="mdui-textfield-label">课程名称</label>
                                        <input class="mdui-textfield-input" type="text" name="name" maxlength="30" pattern="^[\u4e00-\u9fa5_a-zA-Z0-9._（）\(\)\s]+$" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-6 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">translate</i>
                                        <label class="mdui-textfield-label">英文课程名称</label>
                                        <input class="mdui-textfield-input" type="text" maxlength="80" name="name_en" pattern="^[a-zA-Z0-9._（）\(\)\s]+$" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-4 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">grade</i>
                                        <label class="mdui-textfield-label">学分</label>
                                        <input class="mdui-textfield-input" type="number" name="score" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-4 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">architecture</i>
                                        <label class="mdui-textfield-label">理论学时</label>
                                        <input class="mdui-textfield-input" type="number" name="theoryTime" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-4 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">construction</i>
                                        <label class="mdui-textfield-label">实践学时</label>
                                        <input class="mdui-textfield-input" type="number" name="practiceTime" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-3 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">domain</i>
                                        <label class="mdui-textfield-label">归属院系</label>
                                        <input class="mdui-textfield-input" type="text" name="attribution" maxlength="20" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-3 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">language</i>
                                        <label class="mdui-textfield-label">授课语言</label>
                                        <input class="mdui-textfield-input" type="text" name="language" maxlength="10" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-6 mdui-col-md-3 mdui-m-b-1 mdui-m-t-2">
                                    <label class="mdui-textfield-label">课程类型</label>
                                    <select name="type" id="typeAdd" class="mdui-select" style="width: 100%">
                                        <option value="专业课" selected>专业课</option>
                                        <option value="校选课">校选课</option>
                                    </select>
                                </div>
                                <div class="mdui-col-xs-6 mdui-col-md-3 mdui-m-b-1 mdui-m-t-2">
                                    <label class="mdui-textfield-label">校选课类别</label>
                                    <select name="category" id="categoryAdd" class="mdui-select" style="width: 100%" disabled>
                                        <option value="" selected>无</option>
                                        <option value="计算机技能类">计算机技能类</option>
                                        <option value="社会科学类">社会科学类</option>
                                        <option value="职业技能类">职业技能类</option>
                                        <option value="自然科学类">自然科学类</option>
                                        <option value="人文艺术类">人文艺术类</option>
                                        <option value="语言技能类">语言技能类</option>
                                    </select>
                                    <script>
                                        $("#typeAdd").change(function() {
                                            if($("#typeAdd").val() == "校选课" && $("#categoryAdd").attr("disabled") == "disabled") {
                                                $("#categoryAdd").removeAttr("disabled");
                                            } else { // 非选中校选课
                                                $('#categoryAdd').val(""); // 选回为空
                                                $("#categoryAdd").attr("disabled", ""); // 禁用
                                            }
                                        });
                                    </script>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-12 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">description</i>
                                        <label class="mdui-textfield-label">课程简介</label>
                                        <textarea class="mdui-textfield-input" type="text" name="brief" maxlength="1000"></textarea>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-2 mdui-m-b-1 mdui-m-t-2">
                                    <button class="mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple mdui-m-y-1"
                                            onclick="startUpload();" id="uploadButton" type="button">
                                        选择图片
                                    </button>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-10 mdui-m-b-1">
                                    <div class="mdui-m-y-1">
                                        <div class="mdui-textfield">
                                            <input type="file" id="fileSelect" class="mdui-hidden" name="img" accept="image/*" onchange="previewFile();"/>
                                            <input class="mdui-textfield-input" type="text" placeholder="文件名" id="fileName" disabled/>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-m-b-1">
                                        <div class="mdui-row">
                                            <div class="mdui-col-sm-8 mdui-col-offset-sm-2">
                                                <img class="mdui-img-fluid" src="" id="imgPreview">
                                                <!-- 图片预览区 -->
                                            </div>
                                        </div>
                                    </div>
                                <script>
                                    // 上传及预览
                                    function startUpload() {
                                        document.getElementById('fileSelect').click();
                                    }
                                    function previewFile() {
                                        let preview = document.getElementById("imgPreview");
                                        let fileDom = document.getElementById("fileSelect");
                                        // 获取得到file 对象
                                        let file = fileDom.files[0];
                                        console.log(file);
                                        // 限制上传图片的大小
                                        if(file.size > 1024 * 1024) {
                                            mdui.alert('图片大小不能超过 1MB!');
                                            // alert('图片大小不能超过 1MB!');
                                            return false;
                                        } else {
                                            // 创建url
                                            let imgUrl = window.URL.createObjectURL(file)
                                            preview.setAttribute("src", imgUrl)
                                            // 更改img url 以后释放 url
                                            preview.onload = function() {
                                                console.log('图片加载成功');
                                                URL.revokeObjectURL(imgUrl);
                                                document.getElementById('uploadButton').innerHTML = "修改图片";
                                                document.getElementById('fileName').value = file.name;
                                                mdui.updateTextFields(document.getElementById('fileName'));
                                            }
                                        }
                                    }
                                </script>

                                <!-- 隐藏的input，传递API路径 -->
                                <input class="mdui-hidden" name="addCourse"/>
                                <!-- 隐藏的清空button -->
                                <button type="reset" class="mdui-hidden" id="addCourseReset"></button> 
                            </form>
                            </div>
                        </div>
                        <div class="mdui-panel-item-actions">
                            <button class="mdui-btn mdui-ripple mdui-text-color-theme-accent" mdui-panel-item-close>收起</button>
                            <button class="mdui-btn mdui-ripple mdui-text-color-theme-accent" id="addCourse">添加</button>
                            <script>
                                // Ajax异步发送表单
                                $("#addCourse").click(function() {
                                    $.ajax({ 
                                        type: "POST",  
                                        url: "api.php",  
                                        data: new FormData($('#addForm')[0]),
                                        contentType:false, // 必须false才会自动加上正确的Content-Type
                                        processData: false, // 必须false才会避开jQuery对 formdata 的默认处理,XMLHttpRequest会对 formdata 进行正确的处理
                                        async: false,  
                                        error: function(request) {
                                            mdui.alert("网络连接失败", function() {}, {"confirmText": "好的"}); 
                                        },  
                                        success: function(data, textStatus) {
                                            var res = JSON.parse(data);
                                            // console.log(res);
                                            if (res["status"] == "success") { // 成功
                                                mdui.alert("添加成功", function() {}, {"confirmText": "好的"});
                                                
                                                $("#addCourseReset").click(); // 清空表单
                                                $("#imgPreview").attr('src',"");
                                            } else {
                                                mdui.alert(res["errorMsg"], function() {}, {"confirmText": "好的"});
                                            }
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="mdui-panel-item">
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

        <div class="mdui-col-xs-12  mdui-hidden" id="courseCard">
            <div class="mdui-card mdui-m-b-3">
                <!-- 卡片的标题和副标题 -->
                <div class="mdui-card-primary">
                  <div class="mdui-card-primary-title">课程列表</div>
                </div>

                <!-- 卡片的内容 -->
                <div class="mdui-card-content">
                    <div class="mdui-container-fluid">
                        <div class="mdui-row">
                            <div class="mdui-col-xs-12">
                                <div class="mdui-progress mdui-hidden" id="loading">
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
        $("#courseCard").removeClass("mdui-hidden");
        $('html, body').animate({scrollTop: $('#courseCard').offset().top-40}, 1000); // 页面移动到卡片顶部
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
                                <td class="mdui-text-right"><a href="editCourse.php?courseID='+res["data"][i]["courseID"]+'" target="_blank" class="mdui-btn mdui-btn-dense mdui-color-theme-accent mdui-ripple">编辑</a></td>\
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
                    mdui.snackbar({message: "查询失败，请稍后再试", position: 'bottom'});
                }
            }
        });
        $("#loading").addClass("mdui-hidden"); // 隐藏进度条
    }
    $("#search").click(function() {
        search();
    });
</script>

<?php require "footer.php"; ?>