<?php
namespace App\Controller;
/**
 *
 */
class TableInfo
{
  private $db;
  public function __construct($db)
  {
    $this->db = $db;
  }

  public function main()
  {
    $html = new \Module\Html\HtmlTable;

    $uri = $_SERVER['REQUEST_URI'];
    $uris = explode("/", $uri);

    $query = "DESC ".$uris[2];
    $result = $this->db->queryExecute($query);

    $count = mysqli_num_rows($result);
    $content = "";
    $rows = [];
    for ($i=0; $i<$count ; $i++) {
      $row = mysqli_fetch_object($result);
      $rows [] = $row;
    }
    $content = $html->table($rows);

    $body = file_get_contents("../Resource/desc.html");
    $body = str_replace("{{content}}", $content, $body);
    echo $body;
  }
}
