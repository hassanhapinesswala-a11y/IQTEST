<?php
// submit.php
session_start();
require_once 'config.php';
 
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php'); exit;
}
 
$answers = $_POST['answer'] ?? [];
// fetch correct answers for the question ids in session or from DB
$q_order = $_SESSION['q_order'] ?? [];
if (empty($q_order)) {
    // fallback: fetch all ids
    $stmt = $pdo->query("SELECT id FROM questions");
    $q_order = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
 
// Prepare statement to get correct answers for relevant ids
$placeholders = implode(',', array_fill(0, count($q_order), '?'));
$stmt = $pdo->prepare("SELECT id, correct FROM questions WHERE id IN ($placeholders)");
$stmt->execute($q_order);
$corrects_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$corrects = [];
foreach($corrects_raw as $r) $corrects[$r['id']] = $r['correct'];
 
$total = count($corrects);
$score = 0;
$details = [];
 
foreach($corrects as $id => $correct){
    $given = $answers[$id] ?? null;
    $is_correct = ($given !== null && strtoupper($given) === strtoupper($correct));
    if ($is_correct) $score++;
    $details[] = [
        'id' => $id,
        'given' => $given,
        'correct' => $correct,
        'is_correct' => $is_correct
    ];
}
 
// Convert raw score to an approximate IQ-like number for fun:
// basic mapping: percent -> IQ between 80 and 140 linear
$percent = $total ? ($score / $total) : 0;
$IQ = round(80 + ($percent * 60)); // 80..140
 
// Feedback
if ($IQ >= 130) {
    $feedback = "Excellent! آپ کی تجزیاتی صلاحیت بہت اچھی ہے۔ مسائل کو منطقی انداز میں حل کرنے کی زبردست صلاحیت۔";
} elseif ($IQ >= 115) {
    $feedback = "بہتر! آپ کی منطقی اور مسئلہ حل کرنے کی صلاحیت اوپر درمیانی سطح سے بہتر ہے۔";
} elseif ($IQ >= 95) {
    $feedback = "اچھی کارکردگی — آپ کی صلاحیت اوسط کے قریب ہے، تھوڑی مشق سے بہتر ہو سکتی ہے۔";
} else {
    $feedback = "مزید مشق کریں — ریاضیاتی سوچ اور پیٹرن پہچان پر قابو پانے سے بہتری آئے گی۔";
}
 
// Optionally: clear session order to avoid accidental resubmission
unset($_SESSION['q_order']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>IQ Test — Results</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root{--bg:#071026;--card:#071b2f;--accent:#f97316;--muted:#9fb3c8}
    body{margin:0; background:linear-gradient(180deg,#001220,#071026); color:#f0f9ff; font-family:Inter,system-ui,Arial; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:30px}
    .wrap{width:900px; max-width:96%; background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border-radius:14px; padding:26px; box-shadow:0 12px 40px rgba(2,6,23,0.6); border:1px solid rgba(255,255,255,0.03)}
    .score{display:flex; gap:20px; align-items:center}
    .badge{width:120px;height:120px;border-radius:999px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:22px;background:linear-gradient(135deg, rgba(249,115,22,0.16), rgba(124,58,237,0.06)); border:1px solid rgba(255,255,255,0.03)}
    .meta{color:var(--muted)}
    .actions{margin-top:18px}
    .btn{padding:10px 14px;border-radius:10px;border:none;cursor:pointer;font-weight:700}
    .btn-primary{background:linear-gradient(90deg,#f97316,#fb923c); color:#021022}
    .btn-secondary{background:transparent;border:1px solid rgba(255,255,255,0.04); color:var(--muted)}
    .details{margin-top:18px;background:rgba(255,255,255,0.02); padding:12px; border-radius:10px}
    .correct{color:#86efac}
    .wrong{color:#fda4af}
    .share{font-size:13px;color:var(--muted); margin-top:8px}
    @media(max-width:700px){.score{flex-direction:column; align-items:flex-start}}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="score">
      <div class="badge"><?= htmlspecialchars($IQ) ?></div>
      <div>
        <h2 style="margin:0">Your estimated IQ: <strong><?= htmlspecialchars($IQ) ?></strong></h2>
        <div class="meta">Score: <?= $score ?> / <?= $total ?> (<?= round($percent*100,1) ?>%)</div>
        <div style="margin-top:8px; max-width:680px; color:#dff3f8"><?= htmlspecialchars($feedback) ?></div>
      </div>
    </div>
 
    <div class="actions">
      <button class="btn btn-primary" onclick="window.location.href='quiz.php'">Retake Test</button>
      <button class="btn btn-secondary" onclick="window.location.href='index.php'" style="margin-left:10px">Back to Home</button>
    </div>
 
    <div class="share">Share your result: <em>Copy & paste</em> — IQ: <?= $IQ ?> (<?= $score ?>/<?= $total ?>)</div>
 
    <div class="details">
      <h4 style="margin:0 0 8px 0">Question details</h4>
      <ol>
      <?php
      // Show basic per-question correctness using question text
      // fetch text for each question id in $details
      $ids = array_column($details, 'id');
      if (!empty($ids)) {
          $ph = implode(',', array_fill(0, count($ids), '?'));
          $stm = $pdo->prepare("SELECT id, question, option_a, option_b, option_c, option_d FROM questions WHERE id IN ($ph)");
          $stm->execute($ids);
          $qmap_raw = $stm->fetchAll(PDO::FETCH_ASSOC);
          $qmap = [];
          foreach($qmap_raw as $r) $qmap[$r['id']] = $r;
      } else $qmap = [];
 
      foreach($details as $d):
        $q = $qmap[$d['id']] ?? null;
        if (!$q) continue;
        $given = $d['given'] ?? '—';
        $correct = $d['correct'];
        $is_correct = $d['is_correct'];
        ?>
        <li style="margin-bottom:8px">
          <div style="font-weight:600"><?= htmlspecialchars($q['question']) ?></div>
          <div class="<?= $is_correct ? 'correct' : 'wrong' ?>" style="font-size:14px">
            Your answer: <?= $given ?> — Correct: <?= $correct ?> <?= $is_correct ? ' (Correct)' : ' (Wrong)' ?>
          </div>
        </li>
      <?php endforeach; ?>
      </ol>
    </div>
  </div>
</body>
</html>
 
