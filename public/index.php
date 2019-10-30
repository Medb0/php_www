<?php
$config = include '../dbconf.php';
// print_r($config);

require '../Loading.php';
// require '../Module/Database/Database.php';
// require '../Module/Database/Table.php';

$db = new \Module\Database\Database($config);
// echo "<br>";
$query = "SHOW TABLES";
$result = $db->queryExecute($query);
$count = mysqli_num_rows($result);
$content = "";
for ($i=0; $i < $count ; $i++) {
  $row = mysqli_fetch_object($result);
  $content .= "<tr>";
  $content .= "<td>$i</td>";
  $content .= "<td>".$row->Tables_in_php."</td>";
  $content .= "</tr>";
}

$body = file_get_contents("../Resource/table.html");
$body = str_replace("{{content}}", $content, $body);
echo $body;
