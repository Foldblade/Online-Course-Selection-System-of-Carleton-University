<?php

    // 读取配置文件
    $config_json = file_get_contents(".sql_config.json"); // 记得在此配置文件中修改你的数据库连接信息
    $sqlconfig = json_decode($config_json, true);

    $config_json = file_get_contents(".config.json"); // 配置文件，含有Salt
    $config = json_decode($config_json, true);

    // 建立数据库连接
    $con = mysqli_connect($sqlconfig["host"], $sqlconfig["user"], $sqlconfig["password"], $sqlconfig["database"]);
    mysqli_query($con, "SET NAMES UTF8"); 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // POST请求
        if(isset($_POST["login"])) { // 登陆表单
            setcookie("Carleton", "123123123");
            $response = array("status" => "success");
        }
    } else if ($_SERVER['REQUEST_METHOD'] == 'GET') { // POST请求
        
    }
    $responseJSON = json_encode($response, JSON_UNESCAPED_UNICODE);
    exit($responseJSON);
?>