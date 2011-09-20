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

function slightlyBetterIdea($int) {
  return $int/1;
}


$client = new SoapClient( "V200611.ASMX.xml", array( 
  'cache_wsdl' => WSDL_CACHE_NONE,
  'location' => "https://api.cvent.com/soap/V200611.ASMX",
  'trace'=>true
));

// LOGIN
try {
  $login = $client->Login(array(
    'AccountNumber'=>$argv[1],
    'UserName'=>$argv[2],
    'Password'=>$argv[3]
  ));
  displayXmlConversation($client);
  
  $auth_header = new SoapHeader(
    "http://api.cvent.com/2006-11", 
    "CventSessionHeader",
    (object) array('CventSessionValue'=>
      $login->LoginResult->CventSessionHeader
    )
  );
  
  // SEARCH FOR CONTACTS
  $client->__setSoapHeaders($auth_header);
  $search = $client->Search(array(
    'ObjectType'=>'Contact',
    'CvSearchObject'=>array(
      'Filter'=>array(
        'Field'=>'LastName',
        'Operator'=>'Equals',
        'Value'=>'McTester'
      )
    )
  ));
  displayXmlConversation($client);
  
  echo "Search returned:\n";
  var_dump($search);
  
  // // CHECK FOR NEW/UPDATED REGISTRATIONS
  // $client->__setSoapHeaders($auth_header);
  // $ids = $client->GetUpdated(array(
  //   'ObjectType'=>'Registration',
  //   'StartDate'=> new DateTime('-5 day'),
  //   'EndDate'=> new DateTime()
  // ));
  // var_dump($ids);
  // displayXmlConversation($client);
  
} catch(SoapFault $e) {
  displayXmlConversation($client);
}

?>
