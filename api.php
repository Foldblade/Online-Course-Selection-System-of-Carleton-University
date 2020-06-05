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
         *      status: 成功为success，失败为failed，禁止登录为blocked
         *      errorMsg: 仅失败时存在。中文的失败信息
        */
        if(isset($_POST["login"])) {
            if(isset($_POST["user"]) && isset($_POST["passwd"])) {
                $sql = "SELECT * FROM `user` WHERE `user` = '{$_POST["user"]}'";
                $res = mysqli_query($con, $sql);
                $count = mysqli_num_rows($res);
                $userData = mysqli_fetch_all($res, MYSQLI_ASSOC);

                // 清除登录失败表中24h以上的记录
                $time = time() - (3600 * 24);
                $sql = "DELETE FROM `loginFail` WHERE `loginFail`.`time` < {$time} AND `userName` = '{$_POST["user"]}')";
                $res = mysqli_query($con, $sql);
                
                // 查询登录失败次数
                $sql = "SELECT * FROM `loginFail` WHERE `userName` = '{$_POST["user"]}'";
                $res = mysqli_query($con, $sql);
                $failCount = mysqli_num_rows($res);

                if ($failCount >= 3) {
                    $response = array("status" => "blocked", "errorMsg" => "24小时内登录失败次数超过上限");
                } else if ($count == 1 && $userData[0]["password"] == sha1($_POST["passwd"].$userData[0]["salt"])) {
                    // 密码校验通过
                    // 签发一个Cookie,base64编码的JSON格式[用户名+用户ID+GUID+生命周期]作为登陆凭据,同时入库
                    $GUID = GUID();
                    // 删除曾经存储的status
                    $sql = "DELETE FROM `status` WHERE `user` = '{$_POST["user"]}'";
                    $res = mysqli_query($con, $sql);
                    if(isset($_POST["remember"])) { // 记住登录
                        $expireTime = time()+3600*24*14; // 两周生命周期
                        setcookie("Carleton_Status", urlencode(base64_encode(json_encode(array(
                                "user" => $_POST["user"], 
                                "userID" => $userData[0]["userID"],
                                "GUID" => $GUID,
                                "expireTime"=> $expireTime
                        ), JSON_UNESCAPED_UNICODE))), time()+3600*24*14);
                    } else { // 不用记住
                        $expireTime = time()+3600*24; // 一天生命周期
                        setcookie("Carleton_Status", urlencode(base64_encode(json_encode(array(
                            "user"=>$_POST["user"], 
                            "userID" => $userData[0]["userID"],
                            "GUID"=>$GUID,
                            "expireTime"=> time()+3600*24
                        ), JSON_UNESCAPED_UNICODE))));
                    }
                    // 状态入库
                    $sql = "INSERT INTO `status` (`userID`, `user`, `GUID`, `expireTime`) VALUES ('{$userData[0]["userID"]}', '{$_POST["user"]}', '{$GUID}', '{$expireTime}')";
                    $res = mysqli_query($con, $sql);
                    
                    // 清除登陆失败表的记录
                    $sql = "DELETE FROM `loginFail` WHERE `loginFail`.`userName` = '{$_POST["user"]}';";
                    $res = mysqli_query($con, $sql);
                    // 完成,返回成功
                    $response = array("status" => "success");
                } else { // 密码校验失败
                    // 写入登录失败表
                    $time = time();
                    $sql = "INSERT INTO `loginFail` (`userName`, `time`) VALUES ('{$_POST["user"]}', {$time})";
                    $res = mysqli_query($con, $sql);

                    $remainedTimes = 3 - ($failCount + 1);
                    if ($remainedTimes == 0) {
                        $response = array("status" => "blocked", "errorMsg" => "24小时内登录失败次数超过上限");
                    } else {
                        $response = array("status" => "failed", "errorMsg" => "用户名 / 密码错误，你还有{$remainedTimes}次机会");
                    }
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
                $response = array("status" => "success");
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
                $response = array("status" => "success");
            } else {
                $response = array("status" => "failed", "errorMsg" => "参数不完整");
            }
        }

        /** 添加账户表单 
         *  参数: 
         *      addAccount: API判别名,字段必须
         *      user: 用户名，必须
         *      passwd: 用户密码，必须
         *      passwd2: 用户密码第二遍，必须
         *      privilege: 用户权限等级，必须
         *  返回: 
         *      status: 成功为success，失败为failed
         *      errorMsg: 仅失败时存在。中文的失败信息
        */
        if(isset($_POST["addAccount"])) {
            privilege_check(array("secretary"));
            if(isset($_POST["user"]) && isset($_POST["passwd"]) && isset($_POST["passwd2"]) && isset($_POST["privilege"])) {
                if($_POST["passwd"] == $_POST["passwd2"]) {
                    $salt = GUID(); // 取salt
                    $passwd = sha1($_POST["passwd"].$salt); // 加密密码
                    $sql = "INSERT INTO `user` (`user`, `privilege`, `password`, `salt`) 
                    VALUES ('{$_POST["user"]}', '{$_POST["privilege"]}', '$passwd', '$salt')";
                    $res = mysqli_query($con, $sql);
                    $response = array("status" => "success");
                } else {
                    $response = array("status" => "failed", "errorMsg" => "两次密码不一致");
                }
            } else {
                $response = array("status" => "failed", "errorMsg" => "上传参数不完整");
            }
        }

        /** 用户查询表单
         *  参数: 
         *      searchUsers: API判别名,字段必须
         *      user: 用户名，必须，可为空
         *      privilege: 用户权限，必须，可为空
         *  返回: 
         *      status: 成功为success，失败为failed
         *      data: 成功时存在。查询到的数据。
         *      totalPages: 成功时存在。该查询以20页一页进行分页所需的总页数
        */
        if(isset($_POST["searchUsers"])) {
            $page = 1;
            if(isset($_POST["user"]) && isset($_POST["privilege"])) {
                if(isset($_POST["page"])) {
                    $page = strval($_POST["page"]);
                }
                $user = $_POST["user"];
                $privilege = $_POST["privilege"];
                $select = "SELECT `userID`, `user`, `privilege` FROM `user` ";
                $where = 'WHERE `userID` != "" '; // 这个条件毫无意义，只是为了拼凑其他条件方便一点
                if($user != "") {
                    $where = $where."AND `user` LIKE '%{$user}%' ";
                }
                if($privilege != "") {
                    $where = $where."AND `privilege` = '{$privilege}' ";
                }
                $start = 20 * ($page-1);
                $sql = $select.$where."LIMIT {$start}, 20";
                $res = mysqli_query($con, $sql);
                $searchData = mysqli_fetch_all($res, MYSQLI_ASSOC);
                $select = "SELECT COUNT(*) AS `count` FROM `user` ";
                $sql = $select.$where;
                $res = mysqli_query($con, $sql);
                $data = mysqli_fetch_all($res, MYSQLI_ASSOC);
                $totalPages = ceil(intval($data[0]["count"]) / 20); // 向上取整，获得总页数
                $response = array("status" => "success", "data" => $searchData, "totalPages" => $totalPages, "thisPage" => $page);
            } else {
                $response = array("status" => "failed");
            }
        }

        /** 更新用户表单
         *  参数: 
         *      updateUser: API判别名,字段必须
         *      user: 用户名，必须
         *      privilege: 用户权限，必须
         *  返回: 
         *      status: 成功为success，失败为failed
         *      data: 成功时存在。查询到的数据。
        */
        if(isset($_POST["updateUser"])) {
            if(isset($_POST["userID"]) && isset($_POST["user"]) && isset($_POST["privilege"])) {
                $sql = "UPDATE `user` SET `user` = '{$_POST["user"]}', `privilege` = '{$_POST["privilege"]}' WHERE `userID` = {$_POST["userID"]}";
                $res = mysqli_query($con, $sql);
                $response = array("status" => "success");
            } else {
                $response = array("status" => "failed", "errorMsg" => "参数不全面");
            }
        }

        /** 添加选课
         *  参数: 
         *      addToMyChoice: API判别名,字段必须
         *      userID: 用户ID，必须
         *      courseID: 课程ID，必须
         *  返回: 
         *      status: 成功为success，失败为failed
         *      data: 成功时存在。查询到的数据。
        */
        if(isset($_POST["addToMyChoice"])) {
            if(isset($_POST["userID"]) && isset($_POST["courseID"])) {
                $sql = "SELECT `courseID` FROM `selectedCourse` WHERE `userID` = {$_POST["userID"]} AND `courseID` = {$_POST["courseID"]}";
                $res = mysqli_query($con, $sql);
                $count = mysqli_num_rows($res);
                if($count >= 1) {
                    $response = array("status" => "failed", "errorMsg" => "您已选过该课程");
                } else {
                    $sql = "INSERT INTO `selectedCourse` (`userID`, `courseID`) VALUES ({$_POST["userID"]}, {$_POST["courseID"]})";
                    $res = mysqli_query($con, $sql);
                    $response = array("status" => "success");
                }
            } else {
                $response = array("status" => "failed", "errorMsg" => "参数不全面");
            }
        }

        /** 提交审核请求
         *  参数: 
         *      deleteCourse: API判别名,字段必须
         *      userID: 用户ID，必须
         *  返回: 
         *      status: 成功为success，失败为failed
         *      data: 成功时存在。查询到的数据。
        */
        if(isset($_POST["submitToAudit"])) {
            auth();
            if(isset($_POST["userID"])) {
                $sql = "SELECT COUNT(*) AS `count` FROM `aduitQuery` WHERE `userID` = {$_POST["userID"]}";
                $res = mysqli_query($con, $sql);
                $data = mysqli_fetch_all($res, MYSQLI_ASSOC);
                $count = intval($data[0]["count"]);
                if($count == 0) { // 不存在，inset
                    $sql = "INSERT INTO `aduitQuery` (`userID`) VALUES ({$_POST["userID"]})";
                } else { // 存在，update
                    $sql = "UPDATE `aduitQuery` SET `audited` = 0 WHERE `aduitQuery`.`userID` = {$_POST["userID"]}";
                }
                $res = mysqli_query($con, $sql);
                $response = array("status" => "success", "sql" => $sql);
            } else {
                $response = array("status" => "failed", "errorMsg" => "参数不全面");
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

        /** 查询用户信息
         *  参数: 
         *      getUserDetail: API判别名,字段必须
         *      userID: 需查询的用户名，必须
         *  返回: 
         *      status: 成功为success，失败为failed
         *      errorMsg: 仅失败时存在。中文的失败信息
        */
        if(isset($_GET["getUserDetail"])) {
            auth();
            if(isset($_GET["userID"])){
                $response = array("status" => "success", "data" => getUserDetail($_GET["userID"]));
            } else {
                $response = array("status" => "failed", "errorMsg" => "参数错误，查询失败");
            }
        }

        /** 查询课程详情
         *  参数: 
         *      getCourseDetail: API判别名,字段必须
         *      courseID: 需查询的课程ID，必须
         *  返回: 
         *      status: 成功为success，失败为failed
         *      errorMsg: 仅失败时存在。中文的失败信息
        */
        if(isset($_GET["getCourseDetail"])) {
            auth();
            if(isset($_GET["courseID"])){
                $response = array("status" => "success", "data" => getCourseDetail($_GET["courseID"]));
            } else {
                $response = array("status" => "failed", "errorMsg" => "参数错误，查询失败");
            }
        }

        /** 查询已选课程
         *  参数: 
         *      getSelectedCourses: API判别名,字段必须
         *      userID: 需查询的用户ID，必须
         *  返回: 
         *      status: 成功为success，失败为failed
         *      errorMsg: 仅失败时存在。中文的失败信息
        */
        if(isset($_GET["getSelectedCourses"])) {
            auth();
            if(isset($_GET["userID"])){
                $sql = "SELECT `course`.* FROM `selectedCourse`, `course` 
                        WHERE `selectedCourse`.`userID` = {$_GET["userID"]} AND `selectedCourse`.`courseID` = `course`.`courseID`";
                $res = mysqli_query($con, $sql);
                $data = mysqli_fetch_all($res, MYSQLI_ASSOC);
                $response = array("status" => "success", "data" => $data);
            } else {
                $response = array("status" => "failed", "errorMsg" => "参数错误，查询失败");
            }
        }

        /** 删除用户表单
         *  参数: 
         *      deleteUser: API判别名,字段必须
         *      userID: 用户ID，必须
         *  返回: 
         *      status: 成功为success，失败为failed
         *      data: 成功时存在。查询到的数据。
        */
        if(isset($_GET["deleteUser"])) {
            auth();
            if(isset($_GET["userID"])) {
                $sql = "DELETE FROM `user` WHERE `user`.`userID` = {$_GET["userID"]}";
                $res = mysqli_query($con, $sql);
                $response = array("status" => "success");
            } else {
                $response = array("status" => "failed", "errorMsg" => "参数不全面");
            }
        }

        /** 删除课程表单
         *  参数: 
         *      deleteCourse: API判别名,字段必须
         *      courseID: 课程ID，必须
         *  返回: 
         *      status: 成功为success，失败为failed
         *      data: 成功时存在。查询到的数据。
        */
        if(isset($_GET["deleteCourse"])) {
            auth();
            if(isset($_GET["courseID"])) {
                $sql = "DELETE FROM `selectedCourse` WHERE `selectedCourse`.`courseID` = {$_GET["courseID"]}";
                $res = mysqli_query($con, $sql);
                $response = array("status" => "success");
            } else {
                $response = array("status" => "failed", "errorMsg" => "参数不全面");
            }
        }

    }
    $responseJSON = json_encode($response, JSON_UNESCAPED_UNICODE);
    exit($responseJSON);
?>