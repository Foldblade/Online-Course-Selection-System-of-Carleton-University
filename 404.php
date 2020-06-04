<?php http_response_code(404); ?>
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
            <span class="mdui-typo-title mdui-hidden-sm-down" id="pageTitle">404 Not Found</span>
            <div class="mdui-toolbar-spacer mdui-hidden-sm-down"></div>
        </div>
    </nav>
</header>

<div class="mdui-container">
    <div class="mdui-row">
        <div class="mdui-col-xs-12 mdui-col-md-8 mdui-col-offset-md-2">
            <div class="mdui-card mdui-m-y-3">

                <!-- 卡片的媒体内容，可以包含图片、视频等媒体内容，以及标题、副标题 -->
                <div class="mdui-card-media">
                    <img src="img/error-2129569_1280.jpg"/>
                </div>
                <!-- 卡片的标题和副标题 -->
                <div class="mdui-card-primary">
                    <div class="mdui-card-primary-title">404 Not Found</div>
                    <div class="mdui-card-primary-subtitle">您寻找的内容不存在。</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>