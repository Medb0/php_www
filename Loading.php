<?php

// 자동으로 require 해주는 기능
// 이 기능을 등록해 준다.
// 익명함수로 지정
spl_autoload_register(function($classname){
  // echo $classname;
  require "../".$classname.".php";
  //exit;
});
