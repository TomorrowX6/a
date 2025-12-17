<?php
// 开启错误显示（调试完毕后可注释掉）
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'func.php';
require_once 'reg.php';

// 获取当前 URL 路径和参数
$request_uri = $_SERVER['REQUEST_URI'];
$action = isset($_GET['action']) ? $_GET['action'] : '';
$code = isset($_GET['code']) ? $_GET['code'] : '';

// ==========================================
// 1. 触发登录逻辑
// ==========================================
// 兼容参数 ?action=login 或 伪静态 /oauth2/login
if ($action === 'login' || strpos($request_uri, '/oauth2/login') !== false) {
    $sess = shengcpasswd(true);
    getoauth($sess); // 跳转到 LinuxDo
    exit;
}

// ==========================================
// 2. 处理回调逻辑 (从 LinuxDo 回来)
// ==========================================
if (!empty($code)) {
    // 获取用户名 (func.php 里会检查等级 < 2 则 exit)
    $username = ofmCallback($code);
    
    if ($username) {
        // 尝试写入数据库
        // 返回值可能是 'exist' 或者 '新生成的密码'
        $result = getpasswd($username);
        
        if ($result === 'exist') {
            // 用户已存在，显示提示页
            existPage($username);
        } else {
            // 注册成功，显示密码页
            susscesPage($username, $result);
        }
    } else {
        exit("Error: Failed to get username.");
    }
    exit;
}

// ==========================================
// 3. 默认显示主页
// ==========================================
mainPage();

?>
