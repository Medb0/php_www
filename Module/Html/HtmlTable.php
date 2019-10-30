<?php
namespace Module\Html;

class HtmlTable{
  function table($rows)
  {
    $body = "<table class=\"table table-dark\">";
    $body .="<thead>";
    /*
    $body .="<tr>
    <th>No.</th>
    <th>Name</th>
    </tr>";
    */

    $body .= "<tr>";
    foreach ($rows[0] as $key => $value) {
      $body .= "<th>".$key."</th>";
    }
    $body .= "</tr>";
    $body .= "</thead>";
    $body .= "<tbody>";

    //2차원 배열
    //상위 배열 처리 for
    for($i=0;$i<count($rows);$i++){
      $body .= "<tr>";
      // $body .= "<td>$i</td>";
      // $body .= "<td><a href='/TableInfo/".$rows[$i]->Tables_in_php."'>".$rows[$i]->Tables_in_php."</a></td>";

      //하위 배열 처리 foreach
      foreach ($rows[$i] as $key => $value) {
        $body .= "<td>".$value."</td>";
      }
      $body .= "</tr>";
    }

    $body .= "</tbody>";
    $body .=  "</table>";

    return $body;
  }
}
