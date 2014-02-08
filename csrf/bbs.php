<html>
<body>
<h1>BBS</h1>
<hr />
<form method="post" action="bbs.php">
<h2>form</h2>
<p>name</p>
<input type="text" name="name">
<p>comment</p>
<input type="text" name="comment">
<input type="submit">
</form>
<hr />
</body>
</html>
<?php
  if (isset($_REQUEST["name"])) {
    $fp = fopen("bbs.csv", "a");
    fwrite($fp, "\"".$_REQUEST["name"]."\", \"".$_REQUEST["comment"]."\", \"".date("Y/m/d H:i:s")."\"\n");
    fclose($fp);
  }

  $row = 1;
  if (($handle = fopen("bbs.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      echo "<p>User : $data[0] ($data[2])</p>";
      echo "<p>Comment : $data[1]</p>";
      echo "<hr />";
    }
    fclose($handle);
  }
