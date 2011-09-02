<?php
$client = new SoapClient("hello_world.wsdl", array('location'=>'http://localhost/~alex/soap-test/server.php'));
echo $client->sayHello("barf");
?>