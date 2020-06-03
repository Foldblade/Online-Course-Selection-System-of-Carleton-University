<?php require_once "func.php"; ?>
<?php auth(); ?>
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
    document.getElementById("pageTitle").innerText = "课程详情";
</script>
<div class="mdui-container">
    <div class="mdui-row">
        <div class="mdui-col-xs-12 mdui-col-md-10 mdui-col-offset-md-1">
            <div class="mdui-card mdui-m-y-3">
                <div class="mdui-card-media">
                    <?php
                        if ($detail["img"] != "") {
                            echo "<img src=\"img/{$detail["img"]}\" />";
                        } else {
                            echo "<img src=\"img/notebook-336634_1280.jpg\" />";
                        }
                    ?>
                    <div class="mdui-card-media-covered">
                        <!-- 卡片的标题 -->
                        <div class="mdui-card-primary">
                            <div class="mdui-card-primary-title"><?php echo $detail["name"] ?></div>
                        </div>
                    </div>
                </div>

                <!-- 卡片的内容 -->
                <div class="mdui-card-content">
                    <div class="mdui-container">
                        <div class="mdui-row">
                            <div class="mdui-typo">
                                <div class="mdui-col-xs-12  mdui-typo-title-opacity mdui-m-t-3">课程信息</div>
                                <div class="mdui-col-xs-12 mdui-table-fluid mdui-m-y-1">
                                    <table class="mdui-table">
                                      <thead>
                                        <tr>
                                            <th class="mdui-table-col-numeric">ID</th>
                                            <th>名称</th>
                                            <th>英文名称</th>
                                            <th class="mdui-table-col-numeric">学分</th>
                                            <th>类型</th>
                                            <th>校选课类别</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                            <td><?php echo $detail["courseID"] ?></div></td>
                                            <td><?php echo $detail["name"] ?></div></td>
                                            <td><?php echo $detail["name_en"] ?></div></td>
                                            <td><?php echo $detail["score"] ?></div></td>
                                            <td><?php echo $detail["type"] ?></div></td>
                                            <td><?php echo $detail["category"] ?></div></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </div>
                                <div class="mdui-col-xs-12 mdui-typo-title-opacity mdui-m-t-3">学时统计</div>
                                <div class="mdui-col-xs-12 mdui-col-md-6 mdui-table-fluid mdui-m-y-1">
                                    <table class="mdui-table">
                                      <thead>
                                        <tr>
                                            <th class="mdui-table-col-numeric">合计</th>
                                            <th class="mdui-table-col-numeric">理论学时</th>
                                            <th class="mdui-table-col-numeric">实践学时</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                            <td><?php echo $detail["totalTime"] ?></div></td>
                                            <td><?php echo $detail["theoryTime"] ?></div></td>
                                            <td><?php echo $detail["practiceTime"] ?></div></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </div>
                                <div class="mdui-col-xs-12 mdui-typo-title-opacity mdui-m-t-3">开设院系</div>
                                <div class="mdui-col-xs-12 mdui-typo-body-1-opacity"><?php echo $detail["attribution"] ?></div>
                                <div class="mdui-col-xs-12 mdui-typo-title-opacity mdui-m-t-3">授课语言</div>
                                <div class="mdui-col-xs-12 mdui-typo-body-1-opacity"><?php echo $detail["language"] ?></div>
                                <div class="mdui-col-xs-12 mdui-typo-title-opacity mdui-m-t-3">课程简介</div>
                                <div class="mdui-col-xs-12 mdui-typo-body-1-opacity"><?php echo $detail["brief"] ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>