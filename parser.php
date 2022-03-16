<?php
$fp = fopen('php://stdin', 'r');
if (!$fp) {
  exit(11);
}
include 'lex.php';
include 'syn.php';
// end headers
$Token;
if (syntax_f()){
  echo $dom->saveXML();
  //$dom->save('result.xml') or die('XML Create Error');
}

/*
for ($i=0; $i < 50; $i++) {
  echo "Token $i \n";
  $Token=Get_Token();
  print_r($Token);
  echo "\n";
  if ($Token->type=="EOF") {break;}
}
*/

//
//finishup
fclose ($fp);
?>
