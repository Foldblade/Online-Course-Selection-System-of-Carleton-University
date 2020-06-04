<?php
    /**
     * api.php
     * 一个（并不十分优雅的）应用程序接口页
    */

    // 引入自定义函数
    require_once("func.php");

    $response = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // POST请求

        /** 登陆表单 
         *  参数: 
         *      login: API判别名,字段必须
         *      user: 用户名，必须
         *      passwd: 密码，必须
         *      remember: 是否记住登陆，可选
         *  返回: 
         *      status: 成功为success，失败为failed
         *      errorMsg: 仅失败时存在。中文的失败信息
        */
        if(isset($_POST["login"])) {
            if(isset($_POST["user"]) && isset($_POST["passwd"])) {
                $sql = "SELECT * FROM `user` WHERE `user` = '{$_POST["user"]}'";
                $res = mysqli_query($con, $sql);
                $count = mysqli_num_rows($res);
                $userData = mysqli_fetch_all($res, MYSQLI_ASSOC);
                if($count == 1 && $userData[0]["password"] == sha1($_POST["passwd"].$userData[0]["salt"])) {
                    // 密码校验通过
                    // 签发一个Cookie,base64编码的JSON格式[用户名+GUID+生命周期]作为登陆凭据,同时入库
                    $GUID = GUID();
                    // 删除曾经存储的status
                    $sql = "DELETE FROM `status` WHERE `user` = '{$_POST["user"]}'";
                    $res = mysqli_query($con, $sql);
                    if(isset($_POST["remember"])) { // 记住登录
                        $expireTime = time()+3600*24*14; // 两周生命周期
                        setcookie("Carleton_Status", urlencode(base64_encode(json_encode(array(
                                "user" => $_POST["user"], 
                                "GUID" => $GUID,
                                "expireTime"=> $expireTime
                        ), JSON_UNESCAPED_UNICODE))), time()+3600*24*14);
                    } else { // 不用记住
                        $expireTime = time()+3600*24; // 一天生命周期
                        setcookie("Carleton_Status", urlencode(base64_encode(json_encode(array(
                            "user"=>$_POST["user"], 
                            "GUID"=>$GUID,
                            "expireTime"=> time()+3600*24
                        ), JSON_UNESCAPED_UNICODE))));
                    }
                    // 状态入库
                    $sql = "INSERT INTO `status` (`user`, `GUID`, `expireTime`) VALUES ('{$_POST["user"]}', '{$GUID}', '{$expireTime}')";
                    $res = mysqli_query($con, $sql);
                    // 完成,返回成功
                    $response = array("status" => "success");
                } else { // 密码校验失败
                    $response = array("status" => "failed", "errorMsg" => "用户名 / 密码错误");
                }
            } else {
                $response = array("status" => "failed", "errorMsg" => "不全面的登陆信息");
            }
        }

        /** 课程查询表单
         *  参数: 
         *      searchCourse: API判别名,字段必须
         *      attribution: 开设院系，必须，可为空
         *      language: 授课语种，必须，可为空
         *      type: 课程类型，必须，可为空
         *      category: 校选课类别，可选，默认为空
         *      name: 查询的课程名，必须，可为空
         *      page: 可选，分页用，默认为1
         *  返回: 
         *      status: 成功为success，失败为failed
         *      data: 成功时存在。查询到的数据。
         *      totalPages: 成功时存在。该查询以20页一页进行分页所需的总页数
        */
        if(isset($_POST["searchCourse"])) {
            $page = 1;
            $category = "";
            if(isset($_POST["attribution"]) && isset($_POST["language"]) && isset($_POST["type"])) {
                if(isset($_POST["page"])) {
                    $page = strval($_POST["page"]);
                }
                if(isset($_POST["category"])) {
                    $category = $_POST["category"];
                }
                $attribution = $_POST["attribution"];
                $language = $_POST["language"];
                $type = $_POST["type"];
                $name = $_POST["name"];
                $select = "SELECT `courseID`, `name`, `score`, `totalTime`, 
                        `attribution`, `language`, `type`, `category` FROM `course` ";
                $where = 'WHERE `courseID` != "" '; // 这个条件毫无意义，只是为了拼凑其他条件方便一点
                if($attribution != "") {
                    $where = $where."AND `attribution` = '{$attribution}' ";
                }
                if($language != "") {
                    $where = $where."AND `language` = '{$language}' ";
                }
                if($type != "") {
                    $where = $where."AND `type` = '{$type}' ";
                }
                if($category != "") {
                    $where = $where."AND `category` = '{$category}' ";
                }
                if($name != "") {
                    $where = $where."AND `name` LIKE '%{$name}%' ";
                }
                $start = 20 * ($page-1);
                $sql = $select.$where."LIMIT {$start}, 20";
                $res = mysqli_query($con, $sql);
                $searchData = mysqli_fetch_all($res, MYSQLI_ASSOC);
                $select = "SELECT COUNT(*) AS `count` FROM `course` ";
                $sql = $select.$where;
                $res = mysqli_query($con, $sql);
                $data = mysqli_fetch_all($res, MYSQLI_ASSOC);
                $totalPages = ceil(intval($data[0]["count"]) / 20); // 向上取整，获得总页数
                $response = array("status" => "success", "data" => $searchData, "totalPages" => $totalPages, "thisPage" => $page);
            } else {
                $response = array("status" => "failed");
            }
        }

        /** 添加课程表单 
         *  参数: 
         *      addCourse: API判别名,字段必须
         *      name: 课程名，必须
         *      name_en: 英文课程名，必须
         *      score: 学分，必须
         *      theoryTime: 理论学时，必须
         *      practiceTime: 实践学时，必须
         *      attribution: 开设院系，必须
         *      language: 授课语种，必须
         *      type: 课程类型，必须
         *      category: 校选课类别，可选，可为空
         *      brief: 课程简介，必须，可为空
         *  返回: 
         *      status: 成功为success，失败为failed
         *      errorMsg: 仅失败时存在。中文的失败信息
        */
        if(isset($_POST["addCourse"])) {
            if(isset($_POST["name"]) && isset($_POST["name_en"]) && isset($_POST["score"]) 
            && isset($_POST["theoryTime"]) && isset($_POST["practiceTime"]) && isset($_POST["attribution"])
            && isset($_POST["language"]) && isset($_POST["type"]) && isset($_POST["brief"])) {
                if (isset($_POST["category"])) {
                    $category = $_POST["category"];
                } else {
                    $category = "";
                }
                $newFileName = "";
                if(preg_match("#^image/.*$#", $_FILES["img"]["type"]) != 0) {
                    $uploaddir = 'img/';
                    $newFileName = shortUUID().".".pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);
                    if(is_uploaded_file($_FILES['img']['tmp_name'])) {
                        move_uploaded_file($_FILES["img"]["tmp_name"], "img/".$newFileName);
                    }
                }
                $totalTime = intval($_POST["theoryTime"]) + intval($_POST["practiceTime"]);
                $brief = htmlentities($_POST["brief"]);
                $brief = str_replace(' ', "&nbsp;", $brief);
                $brief = str_replace("\r\n", "<br />", $brief);
                $brief = str_replace("\r", "<br />", $brief);
                $brief = str_replace("\n", "<br />", $brief);
                $sql = "INSERT INTO `course` (`name`, `name_en`, `score`, `theoryTime`, `practiceTime`, `totalTime`, `attribution`, `language`, `type`, `category`, `brief`, `img`) 
                        VALUES ('{$_POST["name"]}', '{$_POST["name_en"]}', '{$_POST["score"]}', '{$_POST["theoryTime"]}', '{$_POST["practiceTime"]}', '$totalTime', 
                        '{$_POST["attribution"]}', '{$_POST["language"]}', '{$_POST["type"]}', '$category', '$brief', '$newFileName')";
                $res = mysqli_query($con, $sql);
                $response = array("status" => "success", "files" => $_FILES);
            } else {
                $response = array("status" => "failed", "errorMsg" => "上传参数不完整");
            }
        }

        /** 更新课程表单 
         *  参数: 
         *      updateCourse: API判别名,字段必须
         *      courseID: 课程ID，必须
         *      name: 课程名，必须
         *      name_en: 英文课程名，必须
         *      score: 学分，必须
         *      theoryTime: 理论学时，必须
         *      practiceTime: 实践学时，必须
         *      attribution: 开设院系，必须
         *      language: 授课语种，必须
         *      type: 课程类型，必须
         *      category: 校选课类别，可选，可为空
         *      brief: 课程简介，必须，可为空
         *  返回: 
         *      status: 成功为success，失败为failed
         *      errorMsg: 仅失败时存在。中文的失败信息
        */
        if(isset($_POST["updateCourse"])) {
            if(isset($_POST["courseID"]) && isset($_POST["name"]) && isset($_POST["name_en"]) && isset($_POST["score"]) 
            && isset($_POST["theoryTime"]) && isset($_POST["practiceTime"]) && isset($_POST["attribution"])
            && isset($_POST["language"]) && isset($_POST["type"]) && isset($_POST["brief"])) {
                if (isset($_POST["category"])) {
                    $category = $_POST["category"];
                } else {
                    $category = "";
                }

                if(preg_match("#^image/.*$#", $_FILES["img"]["type"]) != 0) {
                    $uploaddir = 'img/';
                    $newFileName = shortUUID().".".pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);
                    if(is_uploaded_file($_FILES['img']['tmp_name'])) {
                        move_uploaded_file($_FILES["img"]["tmp_name"], "img/".$newFileName);
                        $sql = "UPDATE `course` SET `img` = '$newFileName' 
                                WHERE `course`.`courseID` = {$_POST["courseID"]}";
                        $res = mysqli_query($con, $sql);
                    }
                }
                $totalTime = intval($_POST["theoryTime"]) + intval($_POST["practiceTime"]);
                $brief = htmlentities($_POST["brief"]);
                $brief = str_replace(' ', "&nbsp;", $brief);
                $brief = str_replace("\r\n", "<br />", $brief);
                $brief = str_replace("\r", "<br />", $brief);
                $brief = str_replace("\n", "<br />", $brief);
                $sql = "UPDATE `course` SET `name` = '{$_POST["name"]}', `name_en` = '{$_POST["name_en"]}', `score` = '{$_POST["score"]}', 
                `theoryTime` = '{$_POST["theoryTime"]}', `practiceTime` = '{$_POST["practiceTime"]}', `totalTime` = '$totalTime', 
                `attribution` = '{$_POST["attribution"]}', `language` = '{$_POST["language"]}', `type` = '{$_POST["type"]}', 
                `category` = '$category', `brief` = '$brief'
                WHERE `course`.`courseID` = {$_POST["courseID"]}";

                $res = mysqli_query($con, $sql);
                $response = array("status" => "success", "files" => $_FILES, "sql" => $sql);
            } else {
                $response = array("status" => "failed", "errorMsg" => "参数不完整");
            }
        }

    } else if ($_SERVER['REQUEST_METHOD'] == 'GET') { // GET请求

        /** 退出登录
         *  参数: 
         *      logout: API判别名,字段必须
         *      user: 需退出的用户名，必须
         *  返回: 
         *      status: 成功为success，失败为failed
         *      errorMsg: 仅失败时存在。中文的失败信息
        */
        if(isset($_GET["logout"])) {
            if(isset($_GET["user"]) && 
                isset($_COOKIE["Carleton_Status"]) 
                && $_GET["user"] == json_decode(base64_decode(urldecode($_COOKIE["Carleton_Status"])), true)["user"] ){ // 唯有存在Cookie方可退出(防止其他人伪造退出请求)
                // 数据库删除状态
                $sql = "DELETE FROM `status` WHERE `user` = '{$_GET["user"]}'";
                $res = mysqli_query($con, $sql);
                // 清除Cookie
                setcookie("Carleton_Status", "", time()-1);
                // 完成,返回成功
                $response = array("status" => "success");
            } else {
                $response = array("status" => "failed", "errorMsg" => "参数错误，退出登陆失败");
            }
        }

    }
    $responseJSON = json_encode($response, JSON_UNESCAPED_UNICODE);
    exit($responseJSON);
?>