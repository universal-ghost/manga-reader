<?php
$name = $_GET['name'] ?? null;
$path = __DIR__ . "/manga/" . $name;

if (!$name || !is_dir($path)) {
    die("❌ مانگا یافت نشد.");
}

$chapters = array_filter(glob($path . '/*'), 'is_dir');
natsort($chapters);

$cover = $path . "/cover.jpg";
if (!file_exists($cover)) $cover = $path . "/cover.png";
if (!file_exists($cover)) $cover = "https://via.placeholder.com/100x130?text=No+Cover";
?>
<!DOCTYPE html>
<html lang="fa">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($name) ?></title>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <style>
    body {
      font-family: "Vazirmatn", sans-serif;
      margin: 0;
      background: #f0f2f5;
      color: #2c3e50;
      direction: rtl;
    }
    header {
      background: #6c5ce7;
      color: white;
      padding: 12px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 6px;
      font-size: 18px;
      font-weight: bold;
    }
    header .home-btn {
      display: flex;
      align-items: center;
      gap: 5px;
      padding: 6px 10px;
      background: rgba(255,255,255,0.2);
      border-radius: 6px;
      color: white;
      text-decoration: none;
      transition: background 0.3s;
      font-size: 14px;
    }
    header .home-btn:hover {
      background: rgba(255,255,255,0.4);
    }

    .cover-info {
      display: flex;
      align-items: center;
      max-width: 700px;
      margin: 30px auto;
      background: linear-gradient(135deg, #ffffff, #f8f9fb);
      padding: 15px 20px;
      border-radius: 15px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.12);
      gap: 15px;
    }
    .cover-info img {
      width: 100px;
      height: 130px;
      object-fit: cover;
      border-radius: 12px;
      border: 1px solid #ddd;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .cover-details {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .cover-details h1 {
      margin: 0 0 8px 0;
      font-size: 22px;
      color: #2d3436;
    }
    .cover-details p {
      margin: 0;
      font-size: 14px;
      color: #636e72;
    }
    .cover-details .btn-view {
      margin-top: 12px;
      padding: 8px 14px;
      background: #6c5ce7;
      color: white;
      text-decoration: none;
      font-weight: bold;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background 0.3s;
      width: fit-content;
    }
    .cover-details .btn-view:hover {
      background: #4834d4;
    }

    /* لیست چپترها */
    .chapter-list {
      max-width: 700px;
      margin: 20px auto;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .chapter-list li {
      list-style: none;
    }
    .chapter-list a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 16px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      text-decoration: none;
      color: #2d3436;
      font-size: 14px;
      transition: all 0.3s ease;
    }
    .chapter-list a:hover {
      background: #6c5ce7;
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    .chapter-icon {
      font-size: 18px;
    }
    .chapter-title {
      flex-grow: 1;
    }

    @media(max-width: 480px) {
      .cover-info { flex-direction: column; }
      .cover-info img { width: 120px; height: 150px; }
      .chapter-list a { padding: 10px 12px; font-size: 13px; }
    }
  </style>
</head>
<body>
  <header>
    <div>
      <ion-icon name="book-outline"></ion-icon> <?= htmlspecialchars($name) ?>
    </div>
    <a href="index.php" class="home-btn">
      <ion-icon name="home-outline"></ion-icon> خانه
    </a>
  </header>

  <div class="cover-info">
    <img src="<?= htmlspecialchars($cover) ?>" alt="کاور <?= htmlspecialchars($name) ?>">
    <div class="cover-details">
      <h1><?= htmlspecialchars($name) ?></h1>
      <p><ion-icon name="document-text-outline"></ion-icon> <?= count($chapters) ?> چپتر</p>
      <a href="read.php?name=<?= urlencode($name) ?>&chapter=<?= urlencode(basename(reset($chapters))) ?>" class="btn-view">
        <ion-icon name="eye-outline"></ion-icon> شروع مطالعه
      </a>
    </div>
  </div>

  <ul class="chapter-list">
    <?php foreach ($chapters as $chapter): 
        $chName = basename($chapter);
    ?>
      <li>
        <a href="read.php?name=<?= urlencode($name) ?>&chapter=<?= urlencode($chName) ?>">
          <span class="chapter-icon"><ion-icon name="document-outline"></ion-icon></span>
          <span class="chapter-title">چپتر <?= htmlspecialchars($chName) ?></span>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
