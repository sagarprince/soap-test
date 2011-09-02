<?php
class HelloWorld {
  public static function sayHello($name) {
    return "Hello $name!\n";
  }
  public static function sayGoodbye($name) {
    return "Goodbye $name!\n";
  }
  public static function getAddress($name) {
    return array('name'=>$name, 'address'=>'1060 W Addison');
  }
}

$server = new SoapServer("hello_world.wsdl", array(
  'cache_wsdl' => WSDL_CACHE_NONE
));
$server->setClass('HelloWorld');
$server->handle();
?>