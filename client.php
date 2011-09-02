<?php
/*
Usage: php client.php <url of server> <a name>

$ php client.php http://localhost/~alex/soap-test/server.php Kelsey
=> Hello Kelsey!
*/

if(count($argv) != 3) {
  echo "Usage: php client.php <url of server> <a name>\n";
  exit;
}

$client = new SoapClient( "hello_world.wsdl", array( 'location' => $argv[1] ) );
echo $client->sayHello( $argv[2] );
?>