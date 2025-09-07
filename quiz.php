<?php
// quiz.php
session_start();
require_once 'config.php';
 
// fetch all questions
$stmt = $pdo->query("SELECT id, question, option_a, option_b, option_c, option_d FROM questions ORDER BY id ASC");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
// store question ids in session to preserve order / prevent tampering
$ids = array_column($questions, 'id');
$_SESSION['q_order'] = $ids;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>IQ Test — Quiz</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root{--bg:#071029;--card:#071b2f;--accent:#06b6d4;--glass:rgba(255,255,255,0.03);--muted:#9fb3c8}
    body{margin:0; background:linear-gradient(180deg,#021124,#07243a); color:#eef7fb; font-family:Inter,system-ui,Arial; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:30px}
    .wrap{width:980px; max-width:96%; background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border-radius:16px; padding:26px; box-shadow:0 12px 40px rgba(2,6,23,0.6); border:1px solid rgba(255,255,255,0.03)}
    .head{display:flex; justify-content:space-between; align-items:center; gap:12px}
    .progress{font-size:14px; color:var(--muted)}
    .question-box{margin-top:18px; background:var(--card); padding:20px; border-radius:12px; border:1px solid rgba(255,255,255,0.02)}
    .qtext{font-size:18px; margin-bottom:12px}
    .options{display:grid; gap:10px}
    .opt{padding:12px; border-radius:10px; cursor:pointer; background:var(--glass); border:1px solid rgba(255,255,255,0.02)}
    .opt input{margin-right:10px}
    .nav{display:flex; justify-content:space-between; margin-top:18px}
    .btn{padding:10px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:600}
    .btn-primary{background:linear-gradient(90deg,var(--accent), #3b82f6); color:#021022}
    .btn-muted{background:transparent; border:1px solid rgba(255,255,255,0.04); color:var(--muted)}
    .count{font-size:13px; color:var(--muted); margin-top:8px}
    @media(max-width:720px){.head{flex-direction:column; align-items:flex-start}}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="head">
      <div>
        <h2 style="margin:0">IQ Test</h2>
        <div class="progress">Practice Mode • <?= count($questions) ?> Questions</div>
      </div>
      <div class="count">Answer honestly — result is calculated after submission</div>
    </div>
 
    <form id="quizForm" action="submit.php" method="post">
      <?php foreach($questions as $i => $q): ?>
        <div class="question-box" style="margin-top:<?= $i===0 ? '18px' : '12px' ?>;">
          <div class="qtext"><strong>Q<?= $i+1 ?>.</strong> <?= htmlspecialchars($q['question']) ?></div>
          <div class="options">
            <label class="opt"><input type="radio" name="answer[<?= $q['id'] ?>]" value="A"> A) <?= htmlspecialchars($q['option_a']) ?></label>
            <label class="opt"><input type="radio" name="answer[<?= $q['id'] ?>]" value="B"> B) <?= htmlspecialchars($q['option_b']) ?></label>
            <label class="opt"><input type="radio" name="answer[<?= $q['id'] ?>]" value="C"> C) <?= htmlspecialchars($q['option_c']) ?></label>
            <label class="opt"><input type="radio" name="answer[<?= $q['id'] ?>]" value="D"> D) <?= htmlspecialchars($q['option_d']) ?></label>
          </div>
        </div>
      <?php endforeach; ?>
 
      <div class="nav">
        <button type="button" class="btn btn-muted" id="backBtn" onclick="location.href='index.php'">Back</button>
        <div>
          <button type="reset" class="btn btn-muted" style="margin-right:8px">Clear</button>
          <button type="submit" class="btn btn-primary">Submit Answers</button>
        </div>
      </div>
    </form>
  </div>
 
<script>
  // optional: warn if user tries to leave without submitting
  var form = document.getElementById('quizForm');
  var isSubmitted = false;
  form.addEventListener('submit', function(){ isSubmitted = true; });
  window.addEventListener('beforeunload', function(e){
    if(!isSubmitted){
      e.preventDefault();
      e.returnValue = '';
    }
  });
</script>
</body>
</html>
