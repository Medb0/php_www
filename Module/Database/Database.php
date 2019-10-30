<?php
// 선언 -> 생성 -> 호출

// Database 클래스 선언
class Database
{
  public $connect;

  // 복합 객체 - 객체지향의 은닉화
  // public , private , protected
  // private은 자기 클래스에서만 접근 가능하기때문에
  // 접근시에는 get , set 함수를 만들어서 접근한다.
  private $Table;
  public function setTable($name)
  {
    $this->Table = $name;
    return $this;
  }
  public function getTable()
  {
    return $this->Table;
  }

  // 생성자 메소드
  public function __construct($config)
  {
    echo "클래스 생성";

    //테이블 객체 연결
    $this->Table = new Table($this);

    // mysqli 객체 생성
    $this->connect = new mysqli($config['host'], $config['user'], $config['password'], $config['database']);

    //connect_errno = 0 or 1 == NoErr or Err
    if (!$this->connect->connect_errno) {
      echo "DB 접속 성공<br>";
    }else {
      echo "DB 접속 실패<br>";
    }

  }

  public function queryExecute($query)
  {
    // 전역변수는 $this->를 붙여주고
    // 지역변수는 $만 붙여준다.
    if ($result = mysqli_query($this->connect, $query)) {
      echo "쿼리 실행 완료<br>";
    }else {
      print "쿼리 실행 실패";
    }

    return $result;
  }

  /*
  // 테이블 확인
  */
  public function isTable($tablename)
  {
    $query = "SHOW TABLES";
    $result = $this->queryExecute($query);

    $count = mysqli_num_rows($result);

    for ($i=0; $i < $count ; $i++) {
      $row = mysqli_fetch_object($result);
      if($row->Tables_in_php == $tablename){
        return true;
      }
    }
    return false;
  }
}
