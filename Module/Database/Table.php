<?php

class Table
{
  public $table_name;
  // 객체를 저장할 변수
  public $Database;

  public function __construct($database)
  {
    echo "테이블 클래스 생성<br>";
    // 객체를 받아와서 $Database에 저장한다.
    $this->Database = $database;
  }

  public function createTable($name, array $fields)
  {
    echo "테이블 생성을 시작하겠습니다.<br>";
    // 테이블을 생성할때 사용할 변수
    $query =
    "create table `".$name."` (`id` int(11) NOT NULL auto_increment,";

    // 중간부분을 $fields 배열로 받아와서 foreach를 이용해서 채워넣는다.
    // 이 기법을 쿼리 빌더라고 한다.
    foreach ($fields as $key => $value) {
      $query .= "`$key` $value,";
    }
    $query .= "PRIMARY KEY(`id`)) ENGINE=innodb default charset=utf8;
      ";

      // 저장한 객체의 값을 이용하여 메소드 실행
      mysqli_query($this->Database->connect, $query);
  }
}
