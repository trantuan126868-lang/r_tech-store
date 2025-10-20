<?php
require 'config/config.php';
include 'includes/header.php';
$res = $conn->query('SELECT * FROM posts ORDER BY created_at DESC');
?>
<!doctype html><html><head><meta charset='utf-8'><title>Blog</title><link rel="stylesheet" href="assets/css/style.css"></head><body>
<main style="padding:20px">
<h1>Blog</h1>
<?php while($r=$res->fetch_assoc()): ?>
<article style="margin-bottom:20px">
  <h2><a href="blog.php?id=<?= $r['id'] ?>"><?= htmlspecialchars($r['title']) ?></a></h2>
  <p><em>Đăng bởi <?= htmlspecialchars($r['author']) ?> - <?= $r['created_at'] ?></em></p>
  <p><?= nl2br(substr(htmlspecialchars($r['content']),0,300)) ?>...</p>
</article>
<?php endwhile; ?>
</main>
<?php include 'includes/footer.php'; ?>
</body></html>
