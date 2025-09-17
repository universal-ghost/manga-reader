<?php
$path = __DIR__ . "/manga";
$mangas = array_filter(glob($path . '/*'), 'is_dir');
?>
<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8">
<title>کتابخانه مانگا</title>
<!-- Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<style>
body {
  font-family: "Vazirmatn", sans-serif;
  margin: 0;
  background: #f5f6fa;
  color: #2c3e50;
  direction: rtl;
}
header {
  background: #6c5ce7;
  color: white;
  padding: 12px 20px;
  font-size: 20px;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  position: sticky;
  top: 0;
  z-index: 100;
}
.site-name {
  position: absolute;
  right: 20px;
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 18px;
}
.search-box {
  display: flex;
  align-items: center;
  justify-content: center;
  background: white;
  border-radius: 8px;
  padding: 4px 8px;
  width: 60%;
}
.search-box input {
  border: none;
  outline: none;
  padding: 6px 10px;
  font-size: 14px;
  border-radius: 6px;
  width: 100%;
}

/* لیست مانگا */
.manga-list {
  max-width: 800px;
  margin: 20px auto;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.manga-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 16px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.08);
  transition: transform 0.2s, box-shadow 0.2s;
}
.manga-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 18px rgba(0,0,0,0.12);
}
.manga-info {
  display: flex;
  align-items: center;
  gap: 14px;
  flex-grow: 1;
}
.manga-info img, .cover-placeholder {
  width: 80px;
  height: 100px;
  object-fit: cover;
  border-radius: 8px;
  border: 1px solid #ddd;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  color: #fff;
  background: linear-gradient(135deg, #a29bfe, #6c5ce7);
  text-align: center;
}
.manga-item:hover .manga-info img {
  transform: scale(1.05);
}
.manga-text h3 {
  margin: 0;
  font-size: 18px;
  color: #2d3436;
}
.manga-text p {
  margin: 4px 0 0;
  font-size: 13px;
  color: #636e72;
  display: flex;
  align-items: center;
  gap: 4px;
}
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 8px 14px;
  border-radius: 8px;
  background: #6c5ce7;
  color: white;
  text-decoration: none;
  font-size: 14px;
  font-weight: bold;
  transition: background 0.3s, transform 0.2s, box-shadow 0.2s;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.btn:hover {
  background: #4834d4;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
.btn ion-icon {
  margin-left: 6px;
}
@media(max-width: 480px) {
  .manga-info img, .cover-placeholder { width: 60px; height: 80px; font-size: 12px; }
  .manga-text h3 { font-size: 16px; }
  .manga-text p { font-size: 12px; }
  .btn { padding: 6px 12px; font-size: 13px; }
  .search-box { width: 70%; }
}
</style>
</head>
<body>
<header>
  <div class="site-name">
    <ion-icon name="library-outline"></ion-icon> کتابخانه مانگا
  </div>
  <div class="search-box">
    <input type="text" id="searchInput" placeholder="جستجو مانگا...">
  </div>
</header>

<div class="manga-list" id="mangaList">
<?php 
foreach ($mangas as $manga):
    $name = basename($manga);
    $chapters = array_filter(glob($manga.'/*'), 'is_dir');
    $coverJpg = $manga . "/cover.jpg";
    $coverPng = $manga . "/cover.png";
    $hasCover = file_exists($coverJpg) ? $coverJpg : (file_exists($coverPng) ? $coverPng : false);
?>
  <div class="manga-item" data-name="<?= htmlspecialchars($name) ?>">
    <div class="manga-info">
      <?php if($hasCover): ?>
        <img src="<?= htmlspecialchars($hasCover) ?>" alt="کاور <?= htmlspecialchars($name) ?>">
      <?php else: ?>
        <div class="cover-placeholder">بدون کاور</div>
      <?php endif; ?>
      <div class="manga-text">
        <h3><?= htmlspecialchars($name) ?></h3>
        <p><ion-icon name="document-text-outline"></ion-icon> <?= count($chapters) ?> چپتر</p>
      </div>
    </div>
    <a href="manga.php?name=<?= urlencode($name) ?>" class="btn">
      شروع مطالعه <ion-icon name="eye-outline"></ion-icon>
    </a>
  </div>
<?php endforeach; ?>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const mangaList = document.getElementById('mangaList');
const items = mangaList.querySelectorAll('.manga-item');

searchInput.addEventListener('input', () => {
  const query = searchInput.value.toLowerCase();
  items.forEach(item => {
    const name = item.getAttribute('data-name').toLowerCase();
    item.style.display = name.includes(query) ? 'flex' : 'none';
  });
});
</script>
</body>
</html>
