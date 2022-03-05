<?php
$fp = fopen('php://stdin', 'r');
if (!$fp) {
  echo 'Could not open file somefile.txt';
  exit();
}
include 'lex.php';
include 'syn.php';
// end headers
syntax_f();
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
