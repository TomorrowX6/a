<?php
// ==========================================
// 公共特效代码块
// JS中的 $ 已转义为 \$ 防止 PHP 报错
// ==========================================
$commonEffects = <<<EOT
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
            // 修复：使用 \$ 转义
            this.color = `rgba(255, \${230 + Math.random()*25}, \${240 + Math.random()*15}, \${this.opacity})`;
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
        if (now - lastTime >= 1000) { fpsDisplay.innerText = `FPS: \${frameCount}`; frameCount = 0; lastTime = now; }
        requestAnimationFrame(animate);
    }
    animate();
})();
</script>
EOT;

// ==========================================
// 页面函数
// ==========================================

function mainPage(){
global $commonEffects;
exit('<!DOCTYPE html>
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
    ' . $commonEffects . '
</body>
</html>');
}

function susscesPage($user,$passwd){
global $commonEffects;
exit ('<!DOCTYPE html>
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
        .toast { position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background: rgba(255,79,13
