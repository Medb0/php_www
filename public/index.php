<?php
$config = include '../dbconf.php';
print_r($config);

require '../Module/Database/Database.php';
require '../Module/Database/Table.php';

$db = new Database($config);
echo "<br>";
$query = "SHOW TABLES";
$result = $db->queryExecute($query);

$count = mysqli_num_rows($result);
for ($i=0; $i < $count ; $i++) {
  $row = mysqli_fetch_object($result);
  echo $row->Tables_in_php."<br>";
}
