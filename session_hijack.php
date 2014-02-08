<html>
<body>
<h1>Session Hijack(<a href="index.php">back</a>)</h1>
<hr />
<h2></h2>
<form method="post" action="session_hijack.php">
<p>name : <input type="text" name="name"></p>
<p>password : <input type="password" name="password"></p>
<input type="submit"><br />
</form>
</body>
</html>
<?php
  if (isset($_GET["PHPSESSID"])) {
    session_id($_GET["PHPSESSID"]);
  }
  session_start();
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["name"] = $_REQUEST["name"];
    $_SESSION["password"] = $_REQUEST["password"];
  }

  echo "<p>name : ".$_SESSION["name"]."</p>";
  echo "<p>password : ",$_SESSION["password"],"</p>";
  echo "<hr />";
  echo "<h2>Hijack URL</h2>";
  $path = explode("?", $_SERVER["REQUEST_URI"]);
  echo "http://".$_SERVER["HTTP_HOST"].$path[0]."?PHPSESSID=".session_id()."</p>";
