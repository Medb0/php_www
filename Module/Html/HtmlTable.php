<?php
namespace Module\Html;

class HtmlTable{
  function table($rows)
  {
    $body = "<table class=\"table table-dark\">";


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
}
