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
  $rows [] = $row;
}

$content = table($rows);
$body = file_get_contents("../Resource/table.html");
$body = str_replace("{{content}}", $content, $body);
echo $body;

function table($rows)
{
  $body = "<table class=\"table\">";


  $body .="<thead>";
  $body .="<tr>
  <th>No.</th>
  <th>Name</th>
  </tr>";
  $body .= "</thead>";
  $body .= "<tbody>";

  for($i=0;$i<count($rows);$i++){
    $body .= "<tr>";
    $body .= "<td>$i</td>";
    $body .= "<td>".$rows[$i]->Tables_in_php."</td>";
    $body .= "</tr>";
  }

  $body .= "</tbody>";
  $body .=  "</table>";

  return $body;
}
