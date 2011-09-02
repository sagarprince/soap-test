<?php
/*
$ php cvent_client.php account user pass
*/

if(count($argv) != 4) {
  echo "Usage: php cvent_client.php <account> <username> <password>\n";
  exit;
}

function displayXmlConversation($client) {
  static $count = 1;
  
  echo `echo "\$(tput setaf 2)Request $count: \$(tput sgr0)"`;
  echo $client->__getLastRequestHeaders();
  system('echo \''.$client->__getLastRequest().'\' | xmllint --format -' );
  echo "\n";
  echo `echo "\$(tput setaf 2)Response $count: \$(tput sgr0)"`;
  echo $client->__getLastResponseHeaders();
  system( 'echo \''.$client->__getLastResponse().'\' | xmllint --format -');
  echo "\n\n\n";
  
  $count += 1;
}



$client = new SoapClient( "V200611.ASMX.xml", array( 
  'cache_wsdl' => WSDL_CACHE_NONE,
  'location' => "https://api.cvent.com/soap/V200611.ASMX",
  'trace'=>true
));


// LOGIN
$login = $client->Login(array(
  'AccountNumber'=>$argv[1],
  'UserName'=>$argv[2],
  'Password'=>$argv[3]
));
displayXmlConversation($client);

class CventAuthHeader {
  public $CventSessionValue;
  public function __construct($token) {
    $this->CventSessionValue = $token;
  }
}
$auth_header = new SoapHeader( 
  "http://schemas.cvent.com/api/2006-11", 
  "CventSessionHeader",
  new CventAuthHeader($login->LoginResult->CventSessionHeader)
);

// SEARCH FOR CONTACTS
try {
  $client->__setSoapHeaders($auth_header);
  $search = $client->Search(array(
    'ObjectType'=>'Contact',
    'CvSearchObject'=>array(
      'Filter'=>array(
        'Field'=>'Email',
        'Operator'=>'Equals',
        'Value'=>'someone@example.com'
      )
    )
  ));
  var_dump($search);
} catch(SoapFault $e) {
  displayXmlConversation($client);
}


?>