<?php
class HelloWorld {
  public static function sayHello($name) {
    return "Hello $name!\n";
  }
}

$server = new SoapServer("hello_world.wsdl");
$server->setClass('HelloWorld');
$server->handle();
?>