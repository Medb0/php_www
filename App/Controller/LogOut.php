<?php
namespace App\Controller;
class LogOut extends Controller
{
  public function __construct()
  {

  }

  // 사용자가 임의로 지정한 동적방식 이 프로젝트는 main() 함수에서 실행이 된다.
  public function main()
  {
    echo "로그아웃 실행";
    $_SESSION['email'] = "";
    header("location:"."/");
  }
}
