<?php
$name = $_GET['name'] ?? null;
$chapter = $_GET['chapter'] ?? null;

if (!$name || !$chapter) die("❌ داده ناقص است.");

$chPath = "manga/{$name}/{$chapter}";
if (!is_dir($chPath)) die("❌ فصل پیدا نشد.");

// جمع‌آوری صفحات فقط JPEG و PNG
$images = array_filter(scandir($chPath), fn($file) => preg_match('/\.(jpe?g|png)$/i', $file));
natsort($images);
$images = array_values($images);

// بررسی فصل قبلی/بعدی
$chapterDirs = array_filter(scandir("manga/{$name}/"), fn($dir) => is_dir("manga/{$name}/$dir") && !in_array($dir, ['.', '..']));
natsort($chapterDirs);
$chapterDirs = array_values($chapterDirs);
$currentIndex = array_search($chapter, $chapterDirs);
$prevCh = $chapterDirs[$currentIndex - 1] ?? null;
$nextCh = $chapterDirs[$currentIndex + 1] ?? null;

// کاور مانگا
$cover = "manga/{$name}/cover.jpg";
if (!file_exists($cover)) $cover = "manga/{$name}/cover.png";
if (!file_exists($cover)) $cover = "https://via.placeholder.com/100x130?text=No+Cover";
?>
<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars("$name - $chapter") ?></title>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<style>
  body { font-family:"Vazirmatn",sans-serif; background:#000; color:#fff; margin:0; text-align:center; }
  header { background:#6c5ce7; color:#fff; padding:12px 20px; display:flex; justify-content:space-between; align-items:center; }
  header a { font-size:14px; color:#fff; text-decoration:none; display:flex; align-items:center; gap:5px; }
  .cover-info { display:flex; align-items:center; max-width:700px; margin:20px auto; background:#111; padding:15px 20px; border-radius:15px; gap:15px; }
  .cover-info img { width:100px; height:130px; object-fit:cover; border-radius:12px; border:1px solid #333; }
  .cover-details { flex-grow:1; display:flex; flex-direction:column; justify-content:center; align-items:flex-start; gap:6px; }
  .cover-details h1 { margin:0; font-size:22px; }
  .cover-details p { margin:0; font-size:14px; color:#ccc; }
  .btn-view { margin-top:10px; padding:8px 14px; background:#6c5ce7; color:#fff; text-decoration:none; font-weight:bold; border-radius:8px; display:inline-flex; align-items:center; gap:6px; transition:0.3s; }
  .btn-view:hover { background:#4834d4; }

  /* نوار پایین */
  #bottom-controls {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(20,20,20,0.9);
    padding: 10px 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 1000;
    box-shadow: 0 0 15px #000;
    opacity: 1;
    pointer-events: auto;
    transition: all 0.4s ease;
  }
  #bottom-controls.hidden { opacity: 0; pointer-events: none; transform: translate(-50%, 100%); }
  #bottom-controls button, #bottom-controls a {
    background:#6c5ce7;
    color:#fff;
    border:none;
    border-radius:8px;
    width:40px;
    height:40px;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    transition:0.3s;
    text-decoration:none;
  }
  #bottom-controls button:hover, #bottom-controls a:hover { background:#4834d4; }
  #bottom-controls button:disabled { background:#555; cursor:not-allowed; }
  #zoomValue { color:#fff; font-size:0.9rem; user-select:none; min-width:40px; text-align:center; }

  .chapter-image { width:100%; height:auto; margin-bottom:20px; box-shadow:0 0 10px #888; transition:width 0.3s ease; cursor:pointer; }
</style>
</head>
<body>

<header>
  <span><?= htmlspecialchars($name) ?> - <?= htmlspecialchars($chapter) ?></span>
  <a href="manga.php?name=<?= urlencode($name) ?>">
    <ion-icon name="menu-outline"></ion-icon> فهرست
  </a>
</header>

<div class="cover-info">
  <img src="<?= htmlspecialchars($cover) ?>" alt="کاور <?= htmlspecialchars($name) ?>">
  <div class="cover-details">
    <h1><?= htmlspecialchars($name) ?></h1>
    <p><ion-icon name="document-text-outline"></ion-icon> <?= count($images) ?> صفحه</p>
    <a href="manga.php?name=<?= urlencode($name) ?>" class="btn-view"><ion-icon name="menu-outline"></ion-icon> فهرست</a>
  </div>
</div>

<?php if(empty($images)): ?>
  <p class="text-danger">❌ تصویری برای این فصل پیدا نشد.</p>
<?php else: ?>
  <?php foreach($images as $img): ?>
    <img class="chapter-image" src="<?= $chPath . '/' . $img ?>" alt="صفحه <?= htmlspecialchars($img) ?>">
  <?php endforeach; ?>
<?php endif; ?>

<!-- نوار پایین -->
<div id="bottom-controls">
  <button id="zoomOut" title="زوم -"><ion-icon name="remove-outline"></ion-icon></button>
  <div id="zoomValue">100%</div>
  <button id="zoomIn" title="زوم +"><ion-icon name="add-outline"></ion-icon></button>

  <?php if($prevCh): ?>
    <a href="read.php?name=<?= urlencode($name) ?>&chapter=<?= urlencode($prevCh) ?>" title="فصل قبل"><ion-icon name="arrow-back-outline"></ion-icon></a>
  <?php else: ?>
    <button disabled title="فصل قبل غیر فعال"><ion-icon name="arrow-back-outline"></ion-icon></button>
  <?php endif; ?>

  <a href="manga.php?name=<?= urlencode($name) ?>" title="لیست فصل‌ها"><ion-icon name="list-outline"></ion-icon></a>

  <?php if($nextCh): ?>
    <a href="read.php?name=<?= urlencode($name) ?>&chapter=<?= urlencode($nextCh) ?>" title="فصل بعد"><ion-icon name="arrow-forward-outline"></ion-icon></a>
  <?php else: ?>
    <button disabled title="فصل بعد غیر فعال"><ion-icon name="arrow-forward-outline"></ion-icon></button>
  <?php endif; ?>
</div>

<script>
  const zoomInBtn = document.getElementById('zoomIn');
  const zoomOutBtn = document.getElementById('zoomOut');
  const zoomValueSpan = document.getElementById('zoomValue');
  const images = document.querySelectorAll('.chapter-image');
  const bottomControls = document.getElementById('bottom-controls');

  let zoomPercent = 100;

  function updateZoom() {
    zoomValueSpan.textContent = zoomPercent + '%';
    images.forEach(img => img.style.width = zoomPercent + '%');
  }

  zoomInBtn.addEventListener('click', () => { if(zoomPercent < 150){ zoomPercent+=10; updateZoom(); } });
  zoomOutBtn.addEventListener('click', () => { if(zoomPercent > 10){ zoomPercent-=10; updateZoom(); } });

  updateZoom();

  // مخفی/نمایش نوار با یک کلیک روی صفحه
  document.body.addEventListener('click', (e) => {
    // جلوگیری از تداخل کلیک روی خود نوار
    if(e.target.closest('#bottom-controls')) return;
    bottomControls.classList.toggle('hidden');
  });
</script>

</body>
</html>
