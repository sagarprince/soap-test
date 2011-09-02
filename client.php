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

$client = new SoapClient( "hello_world.wsdl", array( 
  'cache_wsdl' => WSDL_CACHE_NONE,
  'location' => $argv[1]
));

echo "WSDL exposes the following API:\n";
foreach($client->__getFunctions() as $value) {
  echo " - $value\n";
}
echo "\n";

echo '$client->sayHello( "'.$argv[2].'" ) => ' . $client->sayHello( $argv[2] );
echo '$client->sayGoodbye( "'.$argv[2].'" ) => ' .$client->sayGoodbye( $argv[2] );
echo '$client->getAddress( "'.$argv[2].'" ) => ' .var_export($client->getAddress( $argv[2] ), true) . "\n";
?>