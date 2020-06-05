<?php require_once "func.php"; ?>
<?php auth(); ?>
<?php privilege_check(array("secretary")); ?>
<?php require "header.php"; ?>

<script>
    document.getElementById("pageTitle").innerText = "账号管理";
    document.getElementById("LAccountManagement").classList.add("mdui-list-item-active");
</script>

<div class="mdui-container">
    <div class="mdui-row">
        <div class="mdui-col-xs-12">
            <div class="mdui-panel mdui-m-y-3" mdui-panel>
                <div class="mdui-panel-item mdui-panel-item-open">
                    <div class="mdui-panel-item-header">
                        <div class="mdui-panel-item-title mdui-m-y-1" style="font-size: 18px"><i class="mdui-icon material-icons mdui-m-r-1">person_add</i>添加账号</div>
                        <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                    </div>
                    <div class="mdui-panel-item-body">
                        <div class="mdui-container">
                            <div class="mdui-row">
                            <form id="addForm" enctype="multipart/form-data">
                                <div class="mdui-col-xs-12 mdui-col-md-6 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">account_box</i>
                                        <label class="mdui-textfield-label">用户名</label>
                                        <input class="mdui-textfield-input" type="text" name="user" maxlength="10" pattern="^[a-zA-Z][a-zA-Z0-9_]{4,9}$" required/>
                                        <div class="mdui-textfield-error">检查您用户名的格式</div>
                                        <div class="mdui-textfield-helper">字母开头，允许5-10位，允许英文字母、数字、下划线</div>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-6 mdui-m-b-5 mdui-m-t-2">
                                    <label class="mdui-textfield-label">用户权限</label>
                                    <select name="privilege" class="mdui-select" style="width: 100%">
                                        <option value="student" selected>学生</option>
                                        <option value="secretary">教学秘书</option>
                                    </select>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-6 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">vpn_key</i>
                                        <label class="mdui-textfield-label">密码</label>
                                        <input class="mdui-textfield-input" type="text" maxlength="18" name="passwd" pattern="^([0-9a-zA-Z_]){6,18}$" required/>
                                        <div class="mdui-textfield-error">检查您输入的密码</div>
                                        <div class="mdui-textfield-helper">长度6-18位，由英文、数字和下划线构成</div>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-6 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">vpn_key</i>
                                        <label class="mdui-textfield-label">请再次输入密码</label>
                                        <input class="mdui-textfield-input" type="text" maxlength="18" name="passwd2" pattern="^([0-9a-zA-Z_]){6,18}$" required/>
                                        <div class="mdui-textfield-error">检查您输入的密码</div>
                                    </div>
                                </div>
                                <!-- 隐藏的input，传递API路径 -->
                                <input class="mdui-hidden" name="addAccount"/>
                                <!-- 隐藏的清空button -->
                                <button type="reset" class="mdui-hidden" id="addAccountReset"></button> 
                            </form>
                            </div>
                        </div>
                        <div class="mdui-panel-item-actions">
                            <button class="mdui-btn mdui-ripple mdui-text-color-theme-accent" mdui-panel-item-close>收起</button>
                            <button class="mdui-btn mdui-ripple mdui-text-color-theme-accent" id="addCourse">添加</button>
                            <script>
                                // Ajax异步发送表单
                                $("#addCourse").click(function() {
                                    if($("#passwd").val() == $("#passwd2").val()) {
                                        $.ajax({ 
                                            type: "POST",  
                                            url: "api.php",  
                                            data: $('#addForm').serialize(),
                                            async: false,  
                                            error: function(request) {
                                                mdui.alert("网络连接失败", function() {}, {"confirmText": "好的"}); 
                                            },  
                                            success: function(data, textStatus) {
                                                var res = JSON.parse(data);
                                                // console.log(res);
                                                if (res["status"] == "success") { // 成功
                                                    mdui.alert("添加成功", function() {}, {"confirmText": "好的"});
                                                    $("#addAccountReset").click(); // 清空表单
                                                } else {
                                                    mdui.alert(res["errorMsg"], function() {}, {"confirmText": "好的"});
                                                }
                                            }
                                        });
                                    } else {
                                        mdui.alert("两次密码不一致", function() {}, {"confirmText": "好的"});
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
                <div class="mdui-panel-item">
                    <div class="mdui-panel-item-header">
                        <div class="mdui-panel-item-title mdui-m-y-1" style="font-size: 18px"><i class="mdui-icon material-icons mdui-m-r-1">people_alt</i>账号管理</div>
                        <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
                    </div>
                    <div class="mdui-panel-item-body">
                        <div class="mdui-container">
                            <div class="mdui-row">
                            <form id="searchForm">
                                <div class="mdui-col-xs-12 mdui-col-md-6 mdui-m-b-1">
                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                        <i class="mdui-icon material-icons">account_box</i>
                                        <label class="mdui-textfield-label">用户名</label>
                                        <input class="mdui-textfield-input" type="text" name="user" maxlength="10" pattern="^[a-zA-Z][a-zA-Z0-9_]{4,9}$"/>
                                        <div class="mdui-textfield-error">检查您用户名的格式</div>
                                        <div class="mdui-textfield-helper">字母开头，允许5-10位，允许英文字母、数字、下划线</div>
                                    </div>
                                </div>
                                <div class="mdui-col-xs-12 mdui-col-md-6 mdui-m-b-5 mdui-m-t-2">
                                    <label class="mdui-textfield-label">用户权限</label>
                                    <select name="privilege" class="mdui-select" style="width: 100%">
                                        <option value="" selected>全部</option>
                                        <option value="student">学生</option>
                                        <option value="secretary">教学秘书</option>
                                    </select>
                                </div>
                                <!-- 隐藏的input，传递API路径 -->
                                <input class="mdui-hidden" name="searchUsers"/>
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

        <div class="mdui-col-xs-12  mdui-hidden" id="userCard">
            <div class="mdui-card mdui-m-b-3">
                <!-- 卡片的标题和副标题 -->
                <div class="mdui-card-primary">
                  <div class="mdui-card-primary-title">用户列表</div>
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
                                                <th class="mdui-table-col-numeric">ID</th>
                                                <th>用户名</th>
                                                <th>权限</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="usersTbody">
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
<div class="mdui-dialog" id="deleteDialog">
    <div class="mdui-dialog-title">确实要删除吗？</div>
    <div class="mdui-dialog-content">你将会删除用户<span id="deleteDialogUsername"></span>。该操作不可逆。</div>
    <form id="deleteForm"><input class="mdui-hidden" name="userID" id="deleteUserID"/><input class="mdui-hidden" name="deleteUser"/></form>
    <div class="mdui-dialog-actions">
        <button class="mdui-btn mdui-ripple" mdui-dialog-close>取消</button>
        <button class="mdui-btn mdui-ripple" mdui-dialog-confirm>确定</button>
    </div>
</div>
<div class="mdui-dialog" id="editDialog">
    <div class="mdui-dialog-title">编辑用户</div>
    <div class="mdui-dialog-content">
        <form id="editForm">
            <div class="mdui-col-xs-12 mdui-col-md-6 mdui-m-b-1">
                <div class="mdui-textfield mdui-textfield-floating-label">
                    <i class="mdui-icon material-icons">account_box</i>
                    <label class="mdui-textfield-label">用户名</label>
                    <input id="editUser" class="mdui-textfield-input" type="text" name="user" maxlength="10" pattern="^[a-zA-Z][a-zA-Z0-9_]{4,9}$"/>
                    <div class="mdui-textfield-error">检查您用户名的格式</div>
                </div>
            </div>
            <div class="mdui-col-xs-12 mdui-col-md-6 mdui-m-b-5 mdui-m-t-2">
                <label class="mdui-textfield-label">用户权限</label>
                <select name="privilege" id="editPrivilege" class="mdui-select" style="width: 100%">
                    <option value="student">学生</option>
                    <option value="secretary">教学秘书</option>
                </select>
            </div>
            <!-- 隐藏的input，传递API路径 -->
            <input class="mdui-hidden" name="updateUser"/>
            <input class="mdui-hidden" name="userID" id="editUserID"/>
        </form>
    </div>
    <div class="mdui-dialog-actions">
        <button class="mdui-btn mdui-ripple" mdui-dialog-close>取消</button>
        <button class="mdui-btn mdui-ripple" mdui-dialog-confirm>确定</button>
    </div>
</div>

<script src="js/mdui.min.js"></script>
<script>
    var Edit = new mdui.Dialog('#editDialog', {"overlay": false});
    var EditDialog = document.getElementById("editDialog", {"overlay": false});
    EditDialog.addEventListener('confirm.mdui.dialog', function () {
        $.ajax({ 
            type: "POST",  
            url: "api.php",  
            data: $("#editForm").serialize(),  
            async: false,  
            error: function(request) {
                mdui.snackbar({message: "网络连接失败", position: 'bottom'});
            },  
            success: function(data, textStatus) {
                var res = JSON.parse(data);
                if (res["status"] == "success") { // 查询成功
                    mdui.snackbar({message: "保存成功", position: 'bottom'});
                    search();
                } else {
                    mdui.snackbar({message: res["errorMsg"], position: 'bottom'});
                }
            }
        });
    });
    var Del = new mdui.Dialog('#deleteDialog', {"overlay": false});
    var DelDialog = document.getElementById("deleteDialog");
    DelDialog.addEventListener('confirm.mdui.dialog', function () {
        $.ajax({ 
            type: "GET",  
            url: "api.php",  
            data: $("#deleteForm").serialize(),  
            async: false,  
            error: function(request) {
                mdui.snackbar({message: "网络连接失败", position: 'bottom'});
            },  
            success: function(data, textStatus) {
                var res = JSON.parse(data);
                if (res["status"] == "success") { // 查询成功
                    mdui.snackbar({message: "删除成功", position: 'bottom'});
                    search();
                } else {
                    mdui.snackbar({message: res["errorMsg"], position: 'bottom'});
                }
            }
        });
    });
    var search = function (page = 1) {
        $("#userCard").removeClass("mdui-hidden");
        $('html, body').animate({scrollTop: $('#userCard').offset().top-40}, 1000); // 页面移动到卡片顶部
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
                    $("#usersTbody").empty(); // 清空表格内容
                    $("#pageButtonAreas").empty(); // 清空页码按钮内容
                    // console.log(res["totalPages"]);
                    if(res["data"].length == 0) {
                        mdui.snackbar({message: "查询无结果，建议修改查询参数再试", position: 'bottom'});
                    } else {
                        for (let i in res["data"]) {
                            // console.log(res["data"][i]["privilege"]);
                            let privilege = "";
                            if(res["data"][i]["privilege"] == "student"){
                                privilege = "学生";
                            } else if(res["data"][i]["privilege"] == "secretary") {
                                privilege = "教学秘书";
                            } else {
                                privilege = res["data"][i]["privilege"];
                            }
                            $("#usersTbody").append('\
                            <tr>\
                                <td>'+res["data"][i]["userID"]+'</td>\
                                <td>'+res["data"][i]["user"]+'</td>\
                                <td>'+privilege+'</td>\
                                <td class="mdui-text-right">\
                                    <button class="mdui-btn mdui-btn-icon mdui-btn-dense mdui-color-theme-accent mdui-ripple" mdui-tooltip="{content: \'修改\', position: \'top\'}" value="'+res["data"][i]["userID"]+'" id="edit'+res["data"][i]["userID"]+'"><i class="mdui-icon material-icons">edit</i></button>\
                                    <button class="mdui-btn mdui-btn-icon mdui-btn-dense mdui-color-theme-accent mdui-ripple" mdui-tooltip="{content: \'删除\', position: \'top\'}" value="'+res["data"][i]["userID"]+'" id="delete'+res["data"][i]["userID"]+'"><i class="mdui-icon material-icons">delete</i></button>\
                                </td>\
                            </tr>\
                            ');
                        }
                        // 编辑按钮绑定事件
                        $("button[id^='edit']").click(function () {
	                        $.ajax({ 
                                type: "GET",  
                                url: "api.php",  
                                data: "getUserDetail=&userID="+$(this).val(),  
                                async: false,  
                                error: function(request) {
                                    mdui.alert("网络连接失败", function() {}, {"confirmText": "好的"}); 
                                },  
                                success: function(data, textStatus) {
                                    var res = JSON.parse(data);
                                    if (res["status"] == "success") { // 查询成功
                                        $("#editUser").val(res["data"]["user"]);
                                        $("#editPrivilege").val(res["data"]["privilege"]);
                                        $("#editUserID").val(res["data"]["userID"]);
                                        mdui.updateTextFields($("#editUser"));
                                        mdui.updateTextFields($("#editPrivilege"));
                                        Edit.open();
                                    } else {
                                        mdui.alert(res["errorMsg"], function() {}, {"confirmText": "好的"});
                                    }
                                }
                            });
	                    });
                        // 删除按钮绑定事件
                        $("button[id^='delete']").click(function () {
	                        $.ajax({ 
                                type: "GET",  
                                url: "api.php",  
                                data: "getUserDetail=&userID="+$(this).val(),  
                                async: false,  
                                error: function(request) {
                                    mdui.alert("网络连接失败", function() {}, {"confirmText": "好的"}); 
                                },  
                                success: function(data, textStatus) {
                                    var res = JSON.parse(data);
                                    if (res["status"] == "success") { // 查询成功
                                        $("#deleteDialogUsername").html(res["data"]["user"]);
                                        $("#deleteUserID").val(res["data"]["userID"]);
                                        Del.open();
                                    } else {
                                        mdui.alert(res["errorMsg"], function() {}, {"confirmText": "好的"});
                                    }
                                }
                            });
	                    });
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
</script>

<?php require "footer.php"; ?>