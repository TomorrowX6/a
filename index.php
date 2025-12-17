<?php
// 开启错误显示，方便调试（上线后可删除）
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
// 兼容两种方式：
// A. URL 参数方式: index.php?action=login
// B. 路径伪静态方式: /oauth2/login
if ($action === 'login' || strpos($request_uri, '/oauth2/login') !== false) {
    $sess = shengcpasswd(true);
    getoauth($sess); // 这里会执行 header跳转 并 exit
}

// ==========================================
// 2. 处理回调逻辑 (从 LinuxDo 回来)
// ==========================================
// 检查是否有 code 参数
if (!empty($code)) {
    // 获取用户名 (func.php 里会检查等级，不足则 exit)
    $username = ofmCallback($code);
    
    if ($username) {
        // 写入数据库
        $passwd = getpasswd($username);
        // 显示成功页面
        susscesPage($username, $passwd);
    } else {
        exit("Error: Failed to get username.");
    }
}

// ==========================================
// 3. 默认显示主页
// ==========================================
mainPage();
?>
