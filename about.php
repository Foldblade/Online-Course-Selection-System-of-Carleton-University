<?php require "header.php"; ?>

<script>
    document.getElementById("pageTitle").innerText = "关于";
    document.getElementById("LAbout").classList.add("mdui-list-item-active");
</script>
<div class="mdui-container">
    <div class="mdui-row">
        <div class="mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1">
            <div class="mdui-card mdui-m-y-3">
                <div class="mdui-card-primary">
                    <div class="mdui-card-primary-title">关于</div>
                </div>

                <!-- 卡片的内容 -->
                <div class="mdui-card-content">
                    <div class="mdui-container">
                        <div class="mdui-row mdui-typo">
                            <p class="mdui-col-xs-12 mdui-text-center">您可以在<a href="https://github.com/Foldblade/Online-Course-Selection-System-of-Carleton-University">Github</a>上查看本课设的源代码</p>
                            <p class="mdui-col-xs-12 mdui-text-center">本次课设的完成，离不开<a href="https://www.mdui.org/">MDUI</a>这一前端框架的精美样式</p>
                            <p class="mdui-col-xs-12 mdui-text-center">同时，感谢钱锺书先生的《围城》为本次课设的命名提供了启发</p>
                            <p class="mdui-col-xs-12 mdui-text-center">此外，本次课设中亦用到了一些图片，在此向以下图片作者致谢：</p>
                            <p class="mdui-col-xs-12 mdui-text-center">
                                登录页背景图：Photo by <a href="https://unsplash.com/@jo_photo">Jordan Encarnacao</a> on Unsplash
                                <br />
                                404页用图：Image by <a href="https://pixabay.com/users/aitoff-388338/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=2129569">Andrew Martin</a> from <a href="https://pixabay.com/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=2129569">Pixabay</a>
                                <br />
                                课程详情页默认背景图：<a href="https://pixabay.com/photos/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=336634">Free-Photos</a> from <a href="https://pixabay.com/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=336634">Pixabay</a>
                                <br />
                                “货币银行学(A)”课程测试用背景图：Photo by <a href="https://unsplash.com/@joshappel">Josh Appel</a> on Unsplash
                            </p>
                            <p class="mdui-col-xs-12 mdui-text-center">
                                如果你喜欢我的工作，不妨请我喝一杯Vita柠檬茶：
                            </p>
                            <div class="mdui-col-xs-6">
                                <img src="img/alipay.png" class="mdui-img-fluid mdui-img-rounded mdui-center" style="max-width: 200px;" />
                            </div>
                            <div class="mdui-col-xs-6">
                                <img src="img/weixin.png" class="mdui-img-fluid mdui-img-rounded mdui-center" style="max-width: 200px;" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>