<?php
// 상수지정
define("START", microtime(true));

$config = include '../dbconf.php';
require '../Loading.php';

// 세션 활성화
session_start();

// 부트 스트래핑
$uri = $_SERVER['REQUEST_URI'];
$uris = explode("/", $uri);

//db 연결 초기화
$db = new \Module\Database\Database($config);

/**
* 컨트롤러
*/
// 도메인/= 시작
// 도메인/database <= 클래스 호출

if(isset($uris[1]) && $uris[1]){  // isset은 배열공간이 있는지 체크 && 공간안에 값이 있는지 체크
  // 컨트롤러 실행
  $controllerName = "App\Controller\\".ucfirst($uris[1]);
  $tables = new $controllerName($db);

  // 클래스의 메인이 처음으로 동작하도록 지정
  $tables->main();
}else {
  // M (Model : databse) + V (View : 파일 분리 처리) + C (Contorller : 객체) = MVC 패턴
  // 처음 페이지
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

function shutdown()
{
  echo "시작시간=".START."<br>";

  $endtime = microtime(true);
  echo "종료시간=".$endtime."<br>";

  $running = $endtime - START;
  echo "실행시간=".$running;
}

// shutdown();
// 프로그램이 종료되면, 자동으로 shutdown 함수를 호출
register_shutdown_function(shutdown);
