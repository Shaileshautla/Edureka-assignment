<?php
  header('Content-Type: text/html; charset=UTF-8');
  $commit = getenv('COMMIT_SHA') ?: 'unknown';
  $host = gethostname();
?>
<html>
<head><title>AppleBite – PHP App</title></head>
<body>
  <h1>AppleBite – It works! 🍏</h1>
  <p>Host: <b><?php echo $host; ?></b></p>
  <p>Commit: <b><?php echo $commit; ?></b></p>
  <p>Time: <b><?php echo date('c'); ?></b></p>
</body>
</html>
