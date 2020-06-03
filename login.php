<!DOCTYPE html>
<html lang="zh-Hans">
<head>
    <meta charset="UTF-8">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- Favicon -->
    <link rel="shortcut icon" href="img/favicon.ico">

    <!-- Import MDUI -->
    <link rel="stylesheet" href="css/mdui.min.css">

    <!-- Import Google Icon Font -->
    <link href="css/icon.css" rel="stylesheet">

    <!-- JQuery-->
    <script src="js/jquery-3.5.1.min.js"></script>

    <title>克莱登大学在线选课系统</title>

</head>
<body class="mdui-theme-layout-light mdui-theme-primary-indigo mdui-theme-accent-pink">
<header>
    <nav class="mdui-appbar">
        <div class="mdui-toolbar mdui-color-theme">
        <span class="mdui-btn mdui-btn-icon" disabled></span>
            <!--<span class="mdui-btn mdui-btn-icon" mdui-drawer="{target: '#left-drawer'}"><i class="mdui-icon material-icons">menu</i></span>-->
            <a href="index.php" class="mdui-typo-headline">克莱登大学在线选课系统</a>
            <span class="mdui-typo-title mdui-hidden-sm-down" id="pageTitle">登录</span>
            <div class="mdui-toolbar-spacer mdui-hidden-sm-down"></div>
        </div>
    </nav>
</header>

<div class="mdui-container-fluid">
    <div class="mdui-row">
        <div style="background: url('img/jordan-encarnacao-c0rplvWqyZk-unsplash.jpg') no-repeat top center; background-size: cover;">
            <div style="background-color: rgba(0, 0, 0, 0.64); height: 600px;">
                <div class="mdui-container">
                    <div class="mdui-row mdui-valign" style="height: 600px;">
                        <div class="mdui-col-md-6 mdui-hidden-sm-down">
                            <div class="mdui-typo-display-2 mdui-text-color-white-text mdui-m-y-2" style="font-weight: 300">克莱登大学在线选课系统</div>
                            <div class="mdui-typo-display-1 mdui-text-color-white-secondary mdui-m-y-2" style="font-weight: 100">Online Course Selection System of Carleton University</div>
                        </div>
                        <div class="mdui-col-md-5 mdui-col-offset-md-1 mdui-col-xs-12">
                            <div class="mdui-card">
                                <!-- 卡片的内容 -->
                                <div class="mdui-card-content">
                                    <div class="mdui-container">
                                        <div class="mdui-row">
                                            <div class="mdui-col-xs-12 mdui-typo">
                                                <div class="mdui-typo-headline mdui-center">登录</div>
                                            </div>
                                            <form id="loginForm">
                                                <div class="mdui-col-xs-10 mdui-col-offset-xs-1">
                                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                                        <i class="mdui-icon material-icons">account_circle</i>
                                                        <label class="mdui-textfield-label">用户名</label>
                                                        <input class="mdui-textfield-input" type="text" name="user" pattern="^[a-zA-Z0-9]{3,10}$" required/>
                                                        <div class="mdui-textfield-error">用户名不能为空</div>
                                                    </div>
                                                </div>
                                                <div class="mdui-col-xs-10 mdui-col-offset-xs-1">
                                                    <div class="mdui-textfield mdui-textfield-floating-label">
                                                        <i class="mdui-icon material-icons">vpn_key</i>
                                                        <label class="mdui-textfield-label">密码</label>
                                                        <input class="mdui-textfield-input" type="password" name="passwd" required/>
                                                        <div class="mdui-textfield-error">密码不能为空</div>
                                                    </div>
                                                </div>
                                                <div class="mdui-col-xs-10 mdui-col-offset-xs-1">
                                                    <label class="mdui-checkbox">
                                                        <input type="checkbox" name="remember"/>
                                                        <i class="mdui-checkbox-icon"></i>
                                                        保持登陆状态
                                                    </label>
                                                </div>
                                                <div class="mdui-col-xs-12">
                                                    <!-- 隐藏的input，传递API路径 -->
                                                    <input class="mdui-hidden" name="login"/>
                                                    <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent mdui-m-y-3 mdui-center" type="button" id="login">登录</button>
                                                </div>
                                            </form>
                                            <script>
                                                // Ajax异步发送表单
                                                $("#login").click(function() {
                                                    $.ajax({ 
                                                        type: "POST",  
                                                        url: "api.php",  
                                                        data: $('#loginForm').serialize(),  
                                                        async: false,  
                                                        error: function(request) {
                                                            mdui.alert("网络连接失败", function() {}, {"confirmText": "好的"}); 
                                                        },  
                                                        success: function(data, textStatus) {
                                                            var res = JSON.parse(data);
                                                            if (res["status"] == "success") { // 登陆成功
                                                                // 重定向到首页
                                                                window.location.href='index.php';
                                                            } else {
                                                                mdui.alert(res["errorMsg"], function() {}, {"confirmText": "好的"});
                                                            }
                                                        }
                                                    });
                                                });
                                                window.onload = function() {
                                                    <?php
                                                        if(isset($_GET["error"])) {
                                                            if ($_GET["error"] == "notLoggedIn") {
                                                                echo "mdui.snackbar({message: '请登陆后再进行访问',position: 'bottom'});";
                                                            }
                                                            if ($_GET["error"] == "privilegeError") {
                                                                echo "mdui.snackbar({message: '权限错误，请重新登陆或联系管理员',position: 'bottom'});";
                                                            }
                                                            if ($_GET["error"] == "loggedOut") {
                                                                echo "mdui.snackbar({message: '您已成功登出',position: 'bottom'});";
                                                            }
                                                        }
                                                    ?>
                                                }
                                            </script>
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

<?php require "footer.php"; ?>



