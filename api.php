<?php
    /**
     * api.php
     * 一个（并不十分优雅的）应用程序接口页
    */

    // 引入自定义函数
    require_once("func.php");

    $response = "";

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