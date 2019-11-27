<?php
$config = include '../dbconf.php';
require '../Loading.php';

session_start();  // 세션 활성화

$uri = $_SERVER['REQUEST_URI'];
$uris = explode("/", $uri);

$db = new \Module\Database\Database($config);

if(isset($uris[1]) && $uris[1]){  // isset은 배열공간이 있는지 체크 && 공간안에 값이 있는지 체크
  // 컨트롤러 실행
  $controllerName = "App\Controller\\".ucfirst($uris[1]);
  $tables = new $controllerName($db);
  $tables->main();
}else {
  // 처음 페이지임.
  $body = file_get_contents("../Resource/index.html");

  if($_SESSION['email']){
    // 로그인된 상태
    $body = str_replace("{{login}}", "로그인 상태입니다. <a href='logout'>로그아웃</a>", $body);
  }else {
    $loginForm = file_get_contents("../Resource/login.html");
    $body = str_replace("{{login}}", $loginForm , $body);
  }
  echo $body;
}
