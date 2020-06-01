<?php require "header.php"; ?>

<script>
    document.getElementById("pageTitle").innerText = "登录";
</script>
<div class="mdui-container-fluid">
    <div class="mdui-row">
        <div style="background: url('img/jordan-encarnacao-c0rplvWqyZk-unsplash.jpg') no-repeat top center; background-size: cover;">
            <div style="background-color: rgba(0, 0, 0, 0.64); height: 600px;">
                <div class="mdui-container">
                    <div class="mdui-row mdui-valign" style="height: 600px;">
                        <div class="mdui-col-md-6 mdui-hidden-sm-down">
                            <div class="mdui-typo-display-2 mdui-text-color-white-text mdui-m-y-2" style="font-weight: 300">克莱登大学教务服务系统</div>
                            <div class="mdui-typo-display-1 mdui-text-color-white-secondary mdui-m-y-2" style="font-weight: 100">Educational Service System of Carleton University</div>
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
                                            <div class="mdui-col-xs-10 mdui-col-offset-xs-1">
                                                <div class="mdui-textfield mdui-textfield-floating-label">
                                                    <i class="mdui-icon material-icons">account_circle</i>
                                                    <label class="mdui-textfield-label">用户名</label>
                                                    <input class="mdui-textfield-input" type="email"/>
                                                </div>
                                            </div>
                                            <div class="mdui-col-xs-10 mdui-col-offset-xs-1">
                                                <div class="mdui-textfield mdui-textfield-floating-label">
                                                    <i class="mdui-icon material-icons">vpn_key</i>
                                                    <label class="mdui-textfield-label">密码</label>
                                                    <input class="mdui-textfield-input" type="password"/>
                                                </div>
                                            </div>
                                            <div class="mdui-col-xs-12">
                                                <button class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent mdui-m-y-3 mdui-center">登录</button>
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
</div>

<?php require "footer.php"; ?>



