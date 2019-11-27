<?php
namespace App\Controller;
class Goods extends Controller
{
  private $db;
  private $HttpUri;

  public function __construct($db)
  {
    $this->db = $db;
    $this->HttpUri = new \Module\Http\Uri();
  }
  public function main()
  {
    $second = $this->HttpUri->second();
    if($second == "new"){
      $this->newInsert();
    }else if(is_numeric($second)){
      $this->detailView($second);
    }
    else {
      $this->goods();
    }
  }

  Private function detailView($uid)
  {
    if($_POST && $_POST['mode']=="addcart"){
      echo "장바구니";
      $query="INSERT INTO cart (good, email) VALUES ('".$_POST['uid']."','".$_SESSION["email"]."')";
      $result = $this->db->queryExecute($query);
    }
    $query = "SELECT * from goods WHERE id=".$uid;
    echo $query;
    $result = $this->db->queryExecute($query);
    $data = mysqli_fetch_object($result);
    // print_r($data);

    $body = file_get_contents("../Resource/goods_view.html");
    $body = str_replace("{{goodname}}", $data->goodname, $body);
    $body = str_replace("{{images}}", "<img src='/images/".$data->images."' width='100%'/>", $body);
    $body = str_replace("{{price}}", $data->price, $body);
    $body = str_replace("{{id}}", $data->id, $body);
    $body = str_replace("{{categori}}",$this->categori(), $body);
    echo $body;

    $query = "UPDATE goods SET `click`=`click`+1 where id='$uid'";
    $result = $this->db->queryExecute($query);
  }

  private function goods()
  {
    $query = "SELECT * from Goods order by click desc";
    $result = $this->db->queryExecute($query);
    $count = mysqli_num_rows($result);
    $content = "<div class=\"container\">
                <div class=\"row\">";
    for ($i=0;$i<$count;$i++) {
        $row = mysqli_fetch_object($result);
        // print_r($row);

        if($i != 0 && $i%3 == 0){
          $content .= "</div>
          <div class=\"row\">";
        }

        $link = $_SERVER['REQUEST_URI']."/".$row->id;

        $content .= "<div class=\"col-sm\">";
        $content .= "<div>상품명 : <a href='$link'>".$row->goodname."</a>(".$row->click.")</div>";
        $content .= "<div><a href='$link'><img src='/images/".$row->images."' width='100%'/></a></div>";
        $content .= "<div>가격 : ".$row->price."</div>";
        $content .= "</div>";
    }
      $content .= "</div></div>";

    $body = file_get_contents("../Resource/goods.html");
    $body = str_replace("{{content}}", $content, $body);
    $body = str_replace("{{categori}}",$this->categori(), $body);
    $body = str_replace("{{new}}","/goods/new", $body);
    echo $body;
  }

  private function categori()
  {
    $query = "SELECT * from categori";
    $result = $this->db->queryExecute($query);
    $count = mysqli_num_rows($result);

    $cate = "";
    for($i=0;$i<$count;$i++) {
        $row = mysqli_fetch_object($result);
        $cate .= "<a href=\"#\" class=\"list-group-item\">".$row->cate."</a>";
    }
    return $cate;
  }

  private function newInsert()
  {
    if ($_POST) {
      \move_uploaded_file($_FILES['images']['tmp_name'],"images/".$_FILES['images']['name']);
      $query = "INSERT INTO goods (goodname , images, price )
                VALUE ('".$_POST['goodname']."','".$_FILES['images']['name']."','".$_POST['price']."')";
      $result = $this->db->queryExecute($query);

      header("location:"."/goods");
    }else{
      $body = file_get_contents("../Resource/goods_new.html");
      $body = str_replace("{{content}}", $content, $body);
      $body = str_replace("{{new}}","/goods/new", $body);
      echo $body;
    }
  }
}
