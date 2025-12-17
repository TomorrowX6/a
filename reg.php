<?php
// ==========================================
// 公共特效代码块
// 使用 Nowdoc (<<<'EOT')，不需要转义任何字符，最安全
// ==========================================
$commonEffects = <<<'EOT'
<canvas id="snowCanvas"></canvas>
<div id="fpsCounter">FPS: --</div>

<style>
    #snowCanvas { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; pointer-events: none; }
    #fpsCounter { position: fixed; top: 10px; left: 10px; color: rgba(255,255,255,0.8); font-family: "JetBrains Mono", Consolas, monospace; font-size: 12px; z-index: 9999; background: rgba(0,0,0,0.4); padding: 4px 8px; border-radius: 4px; backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.1); }
</style>

<script>
(function(){
    const fpsDisplay = document.getElementById('fpsCounter');
    let lastTime = performance.now();
    let frameCount = 0;
    const canvas = document.getElementById('snowCanvas');
    const ctx = canvas.getContext('2d');
    let width, height;
    const particles = [];
    const particleCount = 100;

    function resize() { width = canvas.width = window.innerWidth; height = canvas.height = window.innerHeight; }
    window.addEventListener('resize', resize);
    resize();

    class Snowflake {
        constructor() { this.reset(); this.y = Math.random() * height; }
        reset() {
            this.x = Math.random() * width;
            this.y = -10;
            this.speedY = Math.random() * 1.5 + 0.5;
            this.speedX = Math.random() * 1 - 0.5;
            this.radius = Math.random() * 2.5 + 0.5;
            this.opacity = Math.random() * 0.5 + 0.3;
            // Nowdoc模式下不需要转义$
            this.color = `rgba(255, ${230 + Math.random()*25}, ${240 + Math.random()*15}, ${this.opacity})`;
        }
        update() {
            this.y += this.speedY; this.x += this.speedX;
            if (this.y > height + 10 || this.x > width + 10 || this.x < -10) this.reset();
        }
        draw() { ctx.beginPath(); ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2); ctx.fillStyle = this.color; ctx.fill(); }
    }

    for (let i = 0; i < particleCount; i++) particles.push(new Snowflake());

    function animate() {
        ctx.clearRect(0, 0, width, height);
        particles.forEach(p => { p.update(); p.draw(); });
        const now = performance.now(); frameCount++;
        if (now - lastTime >= 1000) { fpsDisplay.innerText = `FPS: ${frameCount}`; frameCount = 0; lastTime = now; }
        requestAnimationFrame(animate);
    }
    animate();
})();
</script>
EOT;

// ==========================================
// 页面函数 (使用 PHP/HTML 混排模式)
// ==========================================

function mainPage(){
    global $commonEffects;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册服务 | 立即上车</title>
    <style>
        :root { --primary: #ff7eb3; --secondary: #ff4f8b; --bg-grad: linear-gradient(135deg, #1a0a13, #2e1621); }
        body { margin: 0; font-family: "PingFang SC", "Microsoft YaHei", "Segoe UI", sans-serif; background: var(--bg-grad); min-height: 100vh; display: flex; align-items: center; justify-content: center; color: #fff; overflow: hidden; }
        .container { background: rgba(255,255,255,0.03); padding: 40px; border-radius: 20px; max-width: 480px; width: 90%; text-align: center; backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 20px 50px rgba(0,0,0,0.5); position: relative; overflow: hidden; }
        .container::before { content: ""; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,126,179,0.15) 0%, transparent 60%); z-index: -1; animation: rotate 15s linear infinite; }
        h1 { font-size: 2.2em; background: linear-gradient(to right, #fff, #ffd1e3); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 10px; font-weight: 700; letter-spacing: 1px; }
        p { font-size: 1em; color: #e6aecb; line-height: 1.6; margin-bottom: 30px; font-weight: 300; }
        .tag { display: inline-block; padding: 4px 12px; border-radius: 50px; background: rgba(255,126,179,0.2); color: #ff7eb3; font-size: 0.8em; margin-bottom: 20px; border: 1px solid rgba(255,126,179,0.3); }
        .plan { text-align: left; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 12px; margin: 20px 0; border: 1px solid rgba(255,255,255,0.05); }
        .plan ul { list-style: none; padding: 0; margin: 0; }
        .plan li { padding: 10px 0; display: flex; align-items: center; color: #e6aecb; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .plan li:last-child { border-bottom: none; }
        .plan li::before { content: "✓"; display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; background: var(--primary); color: #fff; border-radius: 50%; margin-right: 12px; font-size: 12px; font-weight: bold; box-shadow: 0 2px 5px rgba(255,126,179,0.4); }
        .register-btn { display: block; width: 100%; padding: 16px; margin-top: 25px; font-size: 1.1em; font-weight: 600; color: #fff; background: linear-gradient(90deg, var(--primary), var(--secondary)); border: none; border-radius: 12px; cursor: pointer; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(255,79,139,0.4); }
        .register-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255,79,139,0.6); }
        @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="container">
        <div class="tag">Limited Access</div>
        <h1>一键注册服务</h1>
        <p>很高兴宣布，新一批内测名额已限时开放。<br/>本次升级带来更加流畅、高效、稳定的体验。</p>
        <div class="plan">
            <ul>
                <li>LinuxDo 一键接入登录</li>
                <li>一人一号，独立隔离，安全可控</li>
                <li>全新智能化管理面板</li>
                <li>隐私保护：账号密码阅后即焚</li>
                <li>热佬社区免费技术支持</li>
            </ul>
        </div>
        <a href="?action=login" class="register-btn">立即上车 🚀</a>
    </div>
    <?php echo $commonEffects; ?>
</body>
</html>
<?php
    exit;
}

function susscesPage($user, $passwd){
    global $commonEffects;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>注册成功</title>
    <style>
        body { margin: 0; font-family: "PingFang SC", "Segoe UI", sans-serif; background: linear-gradient(135deg, #1a0a13, #2e1621, #3d1e2c); min-height: 100vh; display: flex; align-items: center; justify-content: center; color: #fff; overflow: hidden; }
        .container { background: rgba(255,255,255,0.05); padding: 40px; border-radius: 16px; width: 90%; max-width: 500px; text-align: center; backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); }
        .icon-box { width: 80px; height: 80px; background: linear-gradient(135deg, #ff7eb3, #ff4f8b); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 10px 20px rgba(255, 126, 179, 0.3); }
        .icon-box svg { width: 40px; height: 40px; fill: #fff; }
        h2 { font-size: 1.8em; margin: 0 0 10px; color: #fff; }
        .subtitle { color: #e6aecb; margin-bottom: 30px; font-size: 0.95em; }
        .credential-box { background: rgba(0,0,0,0.3); border-radius: 10px; padding: 20px; text-align: left; margin-bottom: 25px; border: 1px solid rgba(255,255,255,0.05); position: relative; }
        .c-row { margin-bottom: 15px; }
        .c-row:last-child { margin-bottom: 0; }
        .c-label { display: block; font-size: 0.8em; color: #ffabc8; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 1px; }
        .c-value { display: block; font-size: 1.1em; color: #ff7eb3; font-family: "JetBrains Mono", Consolas, monospace; word-break: break-all; user-select: text; text-shadow: 0 0 10px rgba(255,126,179,0.3); }
        .btn { display: block; width: 100%; padding: 14px; border: none; border-radius: 8px; font-size: 1em; cursor: pointer; transition: 0.2s; font-weight: 600; }
        .btn-primary { background: linear-gradient(90deg, #ff7eb3, #ff4f8b); color: #fff; margin-bottom: 10px; box-shadow: 0 4px 15px rgba(255,79,139,0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,79,139,0.5); }
        .btn-outline { background: transparent; color: #ffabc8; border: 1px solid rgba(255,255,255,0.1); }
        .btn-outline:hover { border-color: #ff7eb3; color: #fff; background: rgba(255,126,179,0.1); }
        footer { margin-top: 30px; font-size: 0.8em; color: #885f73; }
        .toast { position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background: rgba(255,79,139,0.9); color: white; padding: 10px 20px; border-radius: 30px; font-size: 0.9em; opacity: 0; transition: opacity 0.3s; pointer-events: none; z-index: 100; }
        .toast.show { opacity: 1; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-box">
            <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
        </div>
        <h2>注册成功!</h2>
        <p class="subtitle">您的账户已就绪。请务必保存下方信息，<br>出于安全考虑，密码仅显示一次。</p>
        
        <div class="credential-box" id="credBox">
            <div class="c-row">
                <span class="c-label">登录用户名</span>
                <span class="c-value" id="uField"><?php echo $user; ?>@linux.do</span>
            </div>
            <div class="c-row">
                <span class="c-label">初始密码</span>
                <span class="c-value" id="pField"><?php echo $passwd; ?></span>
            </div>
        </div>

        <button class="btn btn-primary" onclick="copyInfo()">复制账号信息</button>
        <button class="btn btn-outline" onclick="window.location.href='/'">跳转到登录页</button>
        
        <footer>© 2077 loli.000.moe</footer>
    </div>
    <div class="toast" id="toast">已复制到剪贴板</div>

    <script>
        function copyInfo() {
            const u = document.getElementById("uField").innerText;
            const p = document.getElementById("pField").innerText;
            const text = "用户名: " + u + "\n密码: " + p;
            navigator.clipboard.writeText(text).then(() => {
                const t = document.getElementById("toast");
                t.classList.add("show");
                setTimeout(() => t.classList.remove("show"), 2000);
            });
        }
    </script>
    <?php echo $commonEffects; ?>
</body>
</html>
<?php
    exit;
}

function existPage($user){
    global $commonEffects;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户已存在</title>
    <style>
        body { margin: 0; font-family: "PingFang SC", "Segoe UI", sans-serif; background: #1a0a13; min-height: 100vh; display: flex; align-items: center; justify-content: center; color: #fff; overflow: hidden; }
        .container { background: #24101a; padding: 50px 30px; border-radius: 16px; max-width: 400px; width: 90%; text-align: center; border: 1px solid #3d1e2c; box-shadow: 0 0 40px rgba(0,0,0,0.5); position: relative; z-index: 1; }
        .icon { font-size: 60px; margin-bottom: 20px; animation: bounce 2s infinite; }
        h1 { font-size: 1.5em; margin: 0 0 15px; color: #ffeb3b; }
        p { color: #e6aecb; line-height: 1.6; font-size: 0.95em; margin-bottom: 0; }
        .info-box { background: rgba(255,235,59,0.1); border: 1px solid rgba(255,235,59,0.3); border-radius: 8px; padding: 15px; margin: 20px 0; color: #fff9c4; font-size: 0.9em; word-break: break-all; }
        .btn { display: block; width: 100%; padding: 14px; border: none; border-radius: 8px; font-size: 1em; cursor: pointer; transition: 0.2s; font-weight: 600; margin-top: 20px; text-decoration: none; box-sizing: border-box; }
        .btn-primary { background: linear-gradient(90deg, #ff7eb3, #ff4f8b); color: #fff; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,79,139,0.5); }
        .footer { font-size: 0.8em; color: #885f73; margin-top: 20px; }
        @keyframes bounce { 0%, 20%, 50%, 80%, 100% {transform: translateY(0);} 40% {transform: translateY(-10px);} 60% {transform: translateY(-5px);} }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">⚠️</div>
        <h1>无需重复注册</h1>
        <p>系统检测到您的 LinuxDo 账号已关联过服务。</p>
        
        <div class="info-box">
            当前账号: <?php echo $user; ?>
        </div>

        <p style="font-size: 0.85em;">如果您忘记了密码，请联系管理员重置。<br>或直接使用现有账号登录。</p>

        <a href="/" class="btn btn-primary">返回首页</a>
        <div class="footer">SYSTEM NOTICE</div>
    </div>
    <?php echo $commonEffects; ?>
</body>
</html>
<?php
    exit;
}

function stopPage(){
    global $commonEffects;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>暂时封车</title>
    <style>
        body { margin: 0; font-family: "PingFang SC", "Segoe UI", sans-serif; background: #1a0a13; min-height: 100vh; display: flex; align-items: center; justify-content: center; color: #fff; overflow: hidden; }
        .container { background: #24101a; padding: 50px 30px; border-radius: 16px; max-width: 400px; width: 90%; text-align: center; border: 1px solid #3d1e2c; box-shadow: 0 0 40px rgba(0,0,0,0.5); position: relative; z-index: 1; }
        .icon { font-size: 60px; margin-bottom: 20px; animation: pulse 2s infinite; }
        h1 { font-size: 1.5em; margin: 0 0 15px; color: #ff4f8b; }
        p { color: #e6aecb; line-height: 1.6; font-size: 0.95em; margin-bottom: 0; }
        .divider { height: 1px; background: #3d1e2c; margin: 25px 0; }
        .footer { font-size: 0.8em; color: #885f73; }
        @keyframes pulse { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.6; transform: scale(0.95); } 100% { opacity: 1; transform: scale(1); } }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">⛔</div>
        <h1>此车已封</h1>
        <p>当前批次注册名额已满。<br>为保证服务质量，我们暂时关闭了注册入口。</p>
        <div class="divider"></div>
        <p style="font-size: 0.85em; color: #ffabc8;">已上车用户不受影响，请直接登录使用。<br>下一批次开放时间待定。</p>
        <div class="footer" style="margin-top: 20px;">SYSTEM STATUS: LOCKED</div>
    </div>
    <?php echo $commonEffects; ?>
</body>
</html>
<?php
    exit;
}
?>
