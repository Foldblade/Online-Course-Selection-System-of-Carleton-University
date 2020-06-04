<?php require_once "func.php"; ?>
<?php auth(); ?>
<?php privilege_check(array("secretary")); ?>
<?php
    $detail = array();
    if(isset($_GET["courseID"])) {
        $detail = getCourseDetail($_GET["courseID"]);
    }
    if(count($detail) == 0) {
        header('Location: 404.php');
    }
?>
<?php require "header.php"; ?>

<script>
    document.getElementById("pageTitle").innerText = "编辑课程";
</script>
<div class="mdui-container">
    <div class="mdui-row">
        <div class="mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1">
            <div class="mdui-card mdui-m-y-3">
                <div class="mdui-card-primary">
                    <div class="mdui-card-primary-title">编辑课程</div>
                </div>
                <!-- 卡片的内容 -->
                <div class="mdui-card-content">
                    <div class="mdui-container">
                        <div class="mdui-row">
                            <form id="editForm" enctype="multipart/form-data">
                                <div class="mdui-col-xs-12 mdui-col-md-6 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">collections_bookmark</i>
                                        <label class="mdui-textfield-label">课程名称</label>
                                        <input class="mdui-textfield-input" type="text" value="<?php echo $detail["name"]; ?>" name="name" maxlength="30" pattern="^[\u4e00-\u9fa5_a-zA-Z0-9._（）\(\)\s]+$" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-6 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">translate</i>
                                        <label class="mdui-textfield-label">英文课程名称</label>
                                        <input class="mdui-textfield-input" type="text" maxlength="80" value="<?php echo $detail["name_en"]; ?>" name="name_en" pattern="^[a-zA-Z0-9._（）\(\)\s]+$" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-4 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">grade</i>
                                        <label class="mdui-textfield-label">学分</label>
                                        <input class="mdui-textfield-input" type="number" value="<?php echo $detail["score"]; ?>" name="score" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-4 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">architecture</i>
                                        <label class="mdui-textfield-label">理论学时</label>
                                        <input class="mdui-textfield-input" type="number" value="<?php echo $detail["theoryTime"]; ?>" name="theoryTime" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-4 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">construction</i>
                                        <label class="mdui-textfield-label">实践学时</label>
                                        <input class="mdui-textfield-input" type="number" value="<?php echo $detail["practiceTime"]; ?>" name="practiceTime" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-3 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">domain</i>
                                        <label class="mdui-textfield-label">归属院系</label>
                                        <input class="mdui-textfield-input" type="text" value="<?php echo $detail["attribution"]; ?>" name="attribution" maxlength="20" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-3 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">language</i>
                                        <label class="mdui-textfield-label">授课语言</label>
                                        <input class="mdui-textfield-input" type="text" value="<?php echo $detail["language"]; ?>" name="language" maxlength="10" required/>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-6 mdui-col-md-3 mdui-m-b-1 mdui-m-t-2">
                                    <label class="mdui-textfield-label">课程类型</label>
                                    <select name="type" id="type" class="mdui-select" style="width: 100%">
                                        <option value="专业课" selected>专业课</option>
                                        <option value="校选课">校选课</option>
                                    </select>
                                </div>
                                <div class="mdui-col-xs-6 mdui-col-md-3 mdui-m-b-1 mdui-m-t-2">
                                    <label class="mdui-textfield-label">校选课类别</label>
                                    <select name="category" id="category" class="mdui-select" style="width: 100%" disabled>
                                        <option value="" selected>无</option>
                                        <option value="计算机技能类">计算机技能类</option>
                                        <option value="社会科学类">社会科学类</option>
                                        <option value="职业技能类">职业技能类</option>
                                        <option value="自然科学类">自然科学类</option>
                                        <option value="人文艺术类">人文艺术类</option>
                                        <option value="语言技能类">语言技能类</option>
                                    </select>
                                    <script>
                                        $("#type").change(function() {
                                            if($("#type").val() == "校选课" && $("#category").attr("disabled") == "disabled") {
                                                $("#category").removeAttr("disabled");
                                            } else { // 非选中校选课
                                                $('#category').val(""); // 选回为空
                                                $("#category").attr("disabled", ""); // 禁用
                                            }
                                        });
                                    </script>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-12 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">description</i>
                                        <label class="mdui-textfield-label">课程简介</label>
                                        <textarea class="mdui-textfield-input" type="text" name="brief" maxlength="1000"><?php echo str_replace("<br />", "\r\n", $detail["brief"]); ?></textarea>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-2 mdui-m-b-1 mdui-m-t-2">
                                    <button class="mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple mdui-m-y-1"
                                            onclick="startUpload();" id="uploadButton" type="button">
                                        更新图片
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
                                <div class="mdui-col-xs-12 mdui-m-b-1">
                                    <button type="button" id="updateClass" class="mdui-btn mdui-btn-dense mdui-color-theme-accent mdui-ripple mdui-btn-block">更新课程信息</button>
                                    <script>
                                        // Ajax异步发送表单
                                        $("#updateClass").click(function() {
                                            $.ajax({ 
                                                type: "POST",  
                                                url: "api.php",  
                                                data: new FormData($('#editForm')[0]),
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
                                                        mdui.alert("更新成功", function() {
                                                            // 重定向到详情页
                                                            window.location.href='detail.php?courseID=<?php echo $detail["courseID"]; ?>';
                                                        }, {"confirmText": "好的"});
                                                    } else {
                                                        mdui.alert(res["errorMsg"], function() {}, {"confirmText": "好的"});
                                                    }
                                                }
                                            });
                                        });
                                    </script>
                                </div>
                                <!-- 隐藏的input，传递API路径 -->
                                <input class="mdui-hidden" name="updateCourse"/>
                                <input class="mdui-hidden" name="courseID" value="<?php echo $detail["courseID"]; ?>"/>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- 卡片的按钮 -->
                <div class="mdui-card-actions">
                    
                    <script>
                        // Ajax异步发送表单
                        $("#addCourse").click(function() {
                            $.ajax({ 
                                type: "POST",  
                                url: "api.php",  
                                data: new FormData($('#editForm')[0]),
                                contentType:false, // 必须false才会自动加上正确的Content-Type
                                processData: false, // 必须false才会避开jQuery对 formdata 的默认处理,XMLHttpRequest会对 formdata 进行正确的处理
                                async: false,  
                                error: function(request) {
                                    mdui.alert("网络连接失败", function() {}, {"confirmText": "好的"}); 
                                },  
                                success: function(data, textStatus) {
                                    var res = JSON.parse(data);
                                    console.log(res);
                                    if (res["status"] == "success") { // 成功
                                        mdui.alert("添加成功", function() {}, {"confirmText": "好的"});
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
    </div>
</div>
<script>

    window.onload = function() {
        $('#type').val("<?php echo $detail["type"]; ?>"); // 选回为数据库中记录的数据
        $('#category').val("<?php echo $detail["category"]; ?>"); 
        if($('#category').val() != "") {
            $("#category").removeAttr("disabled");
        }
        mdui.mutation();
        mdui.updateTextFields();
    }
</script>
<?php require "footer.php"; ?>