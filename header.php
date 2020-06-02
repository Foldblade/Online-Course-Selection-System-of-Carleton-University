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
<body class="mdui-theme-layout-light mdui-theme-primary-indigo mdui-theme-accent-pink mdui-drawer-body-left mdui-appbar-with-toolbar">
<header>
    <div class="mdui-appbar mdui-appbar-fixed mdui-appbar-inset">
        <div class="mdui-toolbar mdui-color-theme">
            <span class="mdui-btn mdui-btn-icon" mdui-drawer="{target: '#left-drawer'}"><i class="mdui-icon material-icons">menu</i></span>
            <a href="index.php" class="mdui-typo-headline">克莱登大学在线选课系统</a>
            <span class="mdui-typo-title mdui-hidden-sm-down" id="pageTitle"></span>
            <div class="mdui-toolbar-spacer mdui-hidden-sm-down"></div>
            <?php
                require_once "func.php";
                $userName = getUserName();
                if($userName != "") {
                    echo <<< EOF
                    <span>$userName</span>
                    <a href="javascript:;" id="logout">登出</a>
                    <script>
                        $("#logout").click(function() {
                            $.ajax({ 
                                type: "GET",  
                                url: "api.php",  
                                data: "user=$userName&logout=",  
                                async: false,  
                                error: function(request) {
                                    mdui.alert("网络连接失败", function() {}, {"confirmText": "好的"});
                                },  
                                success: function(data, textStatus) {
                                    var res = JSON.parse(data);
                                    if (res["status"] == "success") { // 退出成功
                                        // 重定向到登陆页
                                        window.location.href='login.php?error=loggedOut';
                                    } else {
                                        mdui.alert(res["errorMsg"], function() {}, {"confirmText": "好的"});
                                    }
                                }
                            });
                        });
                    </script>
EOF;
                }
            ?>
            
        </div>
    </div>
    <div class="mdui-drawer mdui-drawer-full-height" id="left-drawer">
        <div class="mdui-container">
            <div class="mdui-row">
                <div class="mdui-col-xs-6 mdui-col-offset-xs-3">
                    <img src="img/logo.png" class="mdui-img-fluid mdui-m-y-3" />
                </div>
            </div>
        </div>
        <ul class="mdui-list">
            <li class="mdui-subheader-inset">个人中心</li>
                <li class="mdui-list-item mdui-ripple">
                    <i class="mdui-list-item-icon mdui-icon material-icons">collections_bookmark</i>
                    <a href="#" class="mdui-list-item-content">课程库</a>
                </li>
                <?php 
                    if (privilege() == "student") {
                        echo <<< EOF
                        <li class="mdui-list-item mdui-ripple">
                            <i class="mdui-list-item-icon mdui-icon material-icons">wysiwyg</i>
                            <a href="#" class="mdui-list-item-content">我的选课</a>
                        </li>
                        <li class="mdui-list-item mdui-ripple">
                            <i class="mdui-list-item-icon mdui-icon material-icons">event_available</i>
                            <a href="#" class="mdui-list-item-content">选课结果</a>
                        </li>
EOF;
                    } else if (privilege() == "secretary") {
                        echo <<< EOF
                        <li class="mdui-subheader-inset">管理中心</li>
                        <li class="mdui-list-item mdui-ripple">
                            <i class="mdui-list-item-icon mdui-icon material-icons">build_circle</i>
                            <a href="#" class="mdui-list-item-content">课程管理</a>
                        </li>
                        <li class="mdui-list-item mdui-ripple">
                            <i class="mdui-list-item-icon mdui-icon material-icons">flaky</i>
                            <a href="#" class="mdui-list-item-content">选课审核</a>
                        </li>
EOF;
                    }
                    
            ?>
            
            
            
            
        </ul>
    </div>
</header>