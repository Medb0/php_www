<?php
namespace Module\Http;

class Uri
{
  public $uri;  // 외부접근 허용
  private $uris;  // 내부접근만 허용

  public function __construct()
  {
    $uri = explode("?", $_SERVER['REQUEST_URI']);
    $this->uri = $uri[0];
    $this->uris = explode("/", $this->uri);
    unset($this->uris[0]);  // 0번 배열 제거
  }

  public function first()
  {
    if(isset($this->uris[1]) && $this->uris[1]){
      return $this->uris[1];
    }
  }

  public function second()
  {
    if(isset($this->uris[2]) && $this->uris[2]){
      return $this->uris[2];
    }
  }

  public function third()
  {
    if(isset($this->uris[3]) && $this->uris[3]){
      return $this->uris[3];
    }
  }

  public function fourth()
  {
    if(isset($this->uris[4]) && $this->uris[4]){
      return $this->uris[4];
    }
  }
}
