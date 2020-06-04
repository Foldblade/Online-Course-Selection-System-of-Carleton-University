<?php
    /**
     * func.php
     * 程序中所用到函数（和配置）的集合页
    */


    // 读取配置文件
    $config_json = file_get_contents(".sql_config.json"); // 记得在此配置文件中修改你的数据库连接信息
    $sqlconfig = json_decode($config_json, true);

    // $config_json = file_get_contents(".config.json"); // 配置文件，含有Salt
    // $config = json_decode($config_json, true);

    // 建立数据库连接
    $con = mysqli_connect($sqlconfig["host"], $sqlconfig["user"], $sqlconfig["password"], $sqlconfig["database"]);
    mysqli_query($con, "SET NAMES UTF8"); 


    /**
     * 生成GUID
     * 借鉴自 http://guid.us/GUID/PHP
     * @return string GUID
     */
    function GUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = // chr(123)// "{"
                substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
                // .chr(125);// "}"
            return $uuid;
        }
    }

    /**
     * 生成无连接线(-)的GUID
     * 借鉴自 http://guid.us/GUID/PHP
     * @return string 无连接线GUID
     */
    function shortUUID(){
        mt_srand((double)microtime()*10000);
        $charid = strtoupper(md5(uniqid(rand(), true)));
        return $charid;
    }

    /**
     * 验证登陆状态
     * @return string 用户权限
     */
    function auth() {
        global $con;
        if(isset($_COOKIE["Carleton_Status"])) {
            $cookieStatus = json_decode(base64_decode(urldecode($_COOKIE["Carleton_Status"])), true);
            $user = $cookieStatus["user"];
            $GUID = $cookieStatus["GUID"];
            $sql = "SELECT * FROM `status` WHERE `GUID` = '{$GUID}'";
            $res = mysqli_query($con, $sql);
            $count = mysqli_num_rows($res);
            $statusData = mysqli_fetch_all($res, MYSQLI_ASSOC);
            if($count == 1 && intval($statusData[0]["expireTime"]) > time() && $statusData[0]["user"] == $user) {
                ; // 通过
            } else {
                header("Location: login.php?error=notLoggedIn"); // 跳转到登录页面
            }
        } else {
            header("Location: login.php?error=notLoggedIn"); // 跳转到登录页面
        }
    }

    /**
     * 获得用户权限
     */
    function privilege() {
        global $con;
        if(isset($_COOKIE["Carleton_Status"])) {
            $cookieStatus = json_decode(base64_decode(urldecode($_COOKIE["Carleton_Status"])), true);
            $user = $cookieStatus["user"];
            $sql = "SELECT * FROM `user` WHERE `user` = '{$user}'";
            $res = mysqli_query($con, $sql);
            $count = mysqli_num_rows($res);
            $statusData = mysqli_fetch_all($res, MYSQLI_ASSOC);
            if($count == 1) {
                return $statusData[0]["privilege"];
            } else {
                header("Location: login.php?error=privilegeError"); // 跳转到登录页面
            }
        } else {
            header("Location: login.php?error=privilegeError"); // 跳转到登录页面
        }
    }

    /**
     * 检查访问权限
     * @param array $need_privilege 该页面所需的权限
     */
    function privilege_check($need_privilege) {
        global $con;
        if(isset($_COOKIE["Carleton_Status"])) {
            $cookieStatus = json_decode(base64_decode(urldecode($_COOKIE["Carleton_Status"])), true);
            $user = $cookieStatus["user"];
            $sql = "SELECT * FROM `user` WHERE `user` = '{$user}'";
            $res = mysqli_query($con, $sql);
            $count = mysqli_num_rows($res);
            $statusData = mysqli_fetch_all($res, MYSQLI_ASSOC);
            if($count == 1) {
                if (in_array($statusData[0]["privilege"], $need_privilege)) {
                    ;
                } else {
                    header("Location: index.php"); // 跳转到首页
                }
            } else {
                header("Location: index.php"); // 跳转到首页
            }
        } else {
            header("Location: index.php"); // 跳转到首页
        }
    }

    /**
     * 获取用户名
     * 注意：该函数仅从Cookie中获取用户名
     * @return string 用户名，不存在返回空字符串
     */
    function getUserName() {
        if(isset($_COOKIE["Carleton_Status"])) {
            $cookieStatus = json_decode(base64_decode(urldecode($_COOKIE["Carleton_Status"])), true);
            $user = $cookieStatus["user"];
            return $user;
        }
        return "";
    }

    /**
     * 列出课程归属院系清单
     * @return array 课程库中归属院系的array
     */
    function listCourseAttribution() {
        global $con;
        $sql = "SELECT DISTINCT `attribution` FROM `course`";
        $res = mysqli_query($con, $sql);
        $data = mysqli_fetch_all($res, MYSQLI_NUM);
        $return = array();
        foreach($data as $item) {
            $return[] = $item[0];
        }
        return $return;
    }

    /**
     * 列出课程授课语言清单
     * @return array 课程库中授课语言的array
     */
    function listCourseLanguage() {
        global $con;
        $sql = "SELECT DISTINCT `language` FROM `course`";
        $res = mysqli_query($con, $sql);
        $data = mysqli_fetch_all($res, MYSQLI_NUM);
        $return = array();
        foreach($data as $item) {
            $return[] = $item[0];
        }
        return $return;
    }

    /**
     * 列出课程类型清单
     * @return array 课程库中课程类型的array
     */
    function listCourseType() {
        global $con;
        $sql = "SELECT DISTINCT `type` FROM `course`";
        $res = mysqli_query($con, $sql);
        $data = mysqli_fetch_all($res, MYSQLI_NUM);
        $return = array();
        foreach($data as $item) {
            $return[] = $item[0];
        }
        return $return;
    }

    /**
     * 列出选修课类型清单
     * @return array 课程库中课程类型的array
     */
    function listCourseCategory() {
        global $con;
        $sql = "SELECT DISTINCT `category` FROM `course`";
        $res = mysqli_query($con, $sql);
        $data = mysqli_fetch_all($res, MYSQLI_NUM);
        $return = array();
        foreach($data as $item) {
            $return[] = $item[0];
        }
        return $return;
    }

    /**
     * 获得课程详情
     * @param int $courseID 课程ID
     * @return array 课程库中课程类型的array
     */
    function getCourseDetail($courseID) {
        global $con;
        $sql = "SELECT * FROM `course` WHERE `courseID` = $courseID";
        $res = mysqli_query($con, $sql);
        $data = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $count = mysqli_num_rows($res);
        if ($count == 1) {
            return $data[0];
        } else {
            return $data;
        }
    }
?>