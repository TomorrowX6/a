<?php
// ==========================================
// func.php - 核心逻辑配置
// ==========================================

$CLIENT_ID = 'AMfmRA61Issgg0hONFPUf6NXDJPV5x9I';
$CLIENT_SECRET = 'R0zcaAU72LazaqoGv4gTrlMiFHsQNLgs';
$REDIRECT_URI = 'https://loli.000.moe/oauth_auto_login'; // 你的回调地址，请确保在 LinuxDo 后台填的一模一样
$AUTHORIZATION_ENDPOINT = 'https://connect.linux.do/oauth2/authorize';
$TOKEN_ENDPOINT = 'https://connect.linux.do/oauth2/token';
$USER_ENDPOINT = 'https://connect.linux.do/api/user';
$DOMIAN = 'linux.do'; 

// 核心 CURL 函数 (保持原样，只加了基础错误检查)
function callbackFunc($code, $clientId, $clientSecret, $redirectUri) {
    global $TOKEN_ENDPOINT, $USER_ENDPOINT;
    $ch = curl_init($TOKEN_ENDPOINT);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'code' => $code,
        'redirect_uri' => $redirectUri,
        'grant_type' => 'authorization_code'
    ]));
  
    $tokenResponse = curl_exec($ch);
    curl_close($ch);
  
    $tokenData = json_decode($tokenResponse, true);
    if (!isset($tokenData['access_token'])) {
        return ['error' => 'access err', 'details' => $tokenData];
    }
  
    $ch = curl_init($USER_ENDPOINT);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $tokenData['access_token']
    ]);
  
    $userResponse = curl_exec($ch);
    curl_close($ch);
  
    return json_decode($userResponse, true);
}

// 跳转授权函数
function getoauth($sess){
    global $AUTHORIZATION_ENDPOINT, $CLIENT_ID, $REDIRECT_URI;
    $jump = $AUTHORIZATION_ENDPOINT . '?' . http_build_query([
        'client_id' => $CLIENT_ID,
        'redirect_uri' => $REDIRECT_URI,
        'response_type' => 'code',
        'state' => $sess
    ]);
    header('Location: ' . $jump, true, 302);
    exit();
}
    
// 回调处理函数
function ofmCallback($getCode){
    global $CLIENT_ID, $CLIENT_SECRET, $REDIRECT_URI;
    $userInfo = callbackFunc($getCode, $CLIENT_ID, $CLIENT_SECRET, $REDIRECT_URI);
  
    if (isset($userInfo['error'])) {
        exit('OAuth Error: ' . $userInfo['error']);
    } else {
        $username = $userInfo['username'];
        $trust_level = $userInfo['trust_level'];
        
        // 【修改点】只允许 3 级及以上
        if ($trust_level < 3) {
            header('Content-Type: text/html; charset=utf-8');
            exit("<center><h1>等级不足</h1><p>需要 LinuxDo 3级及以上。<br>你当前等级: {$trust_level}</p></center>");
        }
        return $username;
    }
}

function shengcpasswd($is_sess) {
    $bytes = random_bytes(16);
    if ($is_sess == true) {
        return substr(bin2hex($bytes), 0, 16);
    } else {
        return rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
    }
}

function getpasswd($user){
    global $DOMIAN;
    $passwd = shengcpasswd(false);
    $password = password_hash($passwd, PASSWORD_BCRYPT);
    
    // 生成 UUID 逻辑
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    
    $token = md5(uniqid(random_bytes(16), true));
    $now = time();
    
    try {
        // 【修改点】修复了这里的中文引号，现在是正确的英文引号
        $pdo = new PDO("sqlite:/www/.docker/.data/database.sqlite");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO v2_user (
            invite_user_id, telegram_id, email, password, password_algo, password_salt,
            balance, discount, commission_type, commission_rate, commission_balance,
            t, u, d, transfer_enable, banned, is_admin, last_login_at, is_staff,
            last_login_ip, uuid, group_id, plan_id, speed_limit, remind_expire,
            remind_traffic, token, expired_at, remarks, created_at, updated_at,
            device_limit, online_count, last_online_at, next_reset_at, last_reset_at,
            reset_count
        ) VALUES (
            NULL, NULL, :email, :password, NULL, NULL,
            0, NULL, 0, NULL, 0,
            0, 0, 0, 0, 0, 0, NULL, 0,
            NULL, :uuid, 1, 1, NULL, 1,
            1, :token, 0, NULL, :created_at, :updated_at,
            NULL, NULL, NULL, NULL, NULL,
            0
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email'      => $user.'@'. $DOMIAN,
            ':password'   => $password,
            ':uuid'       => $uuid,
            ':token'      => $token,
            ':created_at' => $now,
            ':updated_at' => $now
        ]);

        return $passwd;

    } catch (PDOException $e) {
        exit("Database Error: " . $e->getMessage());
    }
}
?>
