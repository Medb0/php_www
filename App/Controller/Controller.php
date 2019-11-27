<?php

namespace App\Controller;

/**
 *
 */
abstract class Controller
{
  // 만들어야하는 메소드
  abstract public function main();

  // 상속으로 사용가능한 메소드
  public function hello()
  {
    echo "Hi , PHP";
  }
}
