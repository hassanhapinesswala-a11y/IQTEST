<?php
// index.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>IQ Test — شروع کریں</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    /* Internal CSS — visually impressive */
    :root{--bg:#0f1724;--card:#0b1220;--accent:#7c3aed;--muted:#94a3b8;--glass:rgba(255,255,255,0.04)}
    *{box-sizing:border-box;font-family:Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial}
    body{margin:0;background:
      radial-gradient(1200px 600px at 10% 10%, rgba(124,58,237,0.12), transparent 8%),
      radial-gradient(900px 400px at 90% 90%, rgba(14,165,233,0.06), transparent 6%),
      var(--bg); color:#e6eef8; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:40px;}
    .card{width:900px; max-width:95%; background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border-radius:18px; padding:36px; box-shadow: 0 8px 30px rgba(2,6,23,0.7); border:1px solid rgba(255,255,255,0.03); display:grid; grid-template-columns: 1fr 360px; gap:24px;}
    .left h1{margin:0 0 8px 0; font-size:34px; letter-spacing:-0.6px}
    .muted{color:var(--muted); margin-bottom:18px}
    .features{display:grid; gap:10px; margin-top:6px}
    .feature{background:var(--glass); padding:12px; border-radius:12px; font-size:14px; color:#dbeafe}
    .cta{margin-top:18px}
    .btn{display:inline-block; padding:12px 18px; border-radius:12px; cursor:pointer; border:none; font-weight:600; background:linear-gradient(90deg,var(--accent), #4f46e5); color:white; box-shadow: 0 6px 20px rgba(124,58,237,0.25);}
    .right{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border-radius:12px; padding:18px; border:1px solid rgba(255,255,255,0.03)}
    .meta{font-size:13px; color:var(--muted)}
    footer{margin-top:18px; text-align:center; color:var(--muted); font-size:13px}
    @media(max-width:800px){.card{grid-template-columns:1fr; padding:20px}.right{order:-1}}
  </style>
</head>
<body>
  <div class="card">
    <div class="left">
      <h1>آن لائن IQ ٹیسٹ</h1>
      <div class="muted">تیار ہو؟ یہ مختصر ٹیسٹ آپ کی منطقی، پیٹرن ریكگنیشن اور مسئلہ حل کرنے کی صلاحیت جانچتا ہے۔</div>
 
      <div class="features">
        <div class="feature">🔹 10 تیز سوالات — تقریباً 5-10 منٹ</div>
        <div class="feature">🔹 پوائنٹس کی بنیاد پر IQ اندازہ</div>
        <div class="feature">🔹 فوری نتیجہ اور فیڈبیک</div>
      </div>
 
      <div class="cta">
        <button class="btn" id="startBtn">Start Test</button>
      </div>
      <div style="margin-top:12px" class="meta">نوٹ: ہر سوال کا ایک درست جواب ہے — ایمانداری سے حل کریں تاکہ نتیجہ صحیح آئے۔</div>
    </div>
 
    <div class="right">
      <h3 style="margin:0 0 8px 0">Test Details</h3>
      <p class="meta">Questions: <strong>10</strong></p>
      <p class="meta">Estimated time: <strong>5-10 minutes</strong></p>
      <p class="meta">Designed for practice and fun — not an official clinical IQ assessment.</p>
 
      <div style="margin-top:14px">
        <button class="btn" style="background:transparent;border:1px solid rgba(255,255,255,0.06); color:#c7d2fe" onclick="location.href='quiz.php'">Start without JS redirect</button>
      </div>
    </div>
  </div>
 
  <footer>Made with ❤️ — IQ Test</footer>
 
<script>
  // JS redirect (required per your instruction)
  document.getElementById('startBtn').addEventListener('click', function(){
    // go to quiz page
    window.location.href = 'quiz.php';
  });
</script>
</body>
</html>
