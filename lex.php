<?php
function deb_lex($str){
  //echo("$str"."\n");
  return;
}

function Get_Token(){
  $Token=fce();
  //echo "Lex_";print_r($Token);
  return $Token;
}

function fce() {
  static $EOL_bool=false;
  $char="";
  $Token = new stdClass; $Token->data="";$Token->type="";
  $letters=" qwertzuioplkjhgfdsayxcvbnmQWERTZUIOPLKJHGFDSAYXCVBNM";
  $numbers=" 0123456789";
  $special=" %*!?_$&";
  global $fp;
  $state="s1";
  while(true){
    if(($char=="\n")or($char=="\r")){
      if (($EOL_bool==false) and ($state!="d1")) {
        $Token->type="EOL";
        $EOL_bool=true;
        return $Token;
      }else {$EOL_bool=false;}

    }
    if ($state=="s1") {
      deb_lex("s1");//print_r($Token);
      if (false === ($char = fgetc($fp))) {if (feof($fp)) {$state="f3";$Token->data.=$char;continue;}exit(23);} //TODo unsure here
      if ($char==" ") {continue;}
      if ($char=="\n") {continue;}
      if ($char=="\r") {continue;}
      if ($char==".") {$state="s2";$Token->data.=$char;continue;}
      if ($char=="#") {$state="s3";continue;}
      if ((strpos($letters,$char)!=false)or(strpos($numbers,$char)!=false)) {$state="s4";$Token->data.=$char;continue;}
      //if ($char=="@") {$state="s5";continue;}
    }
    if ($state=="s2") {
      deb_lex("s2");
      for ($i=0; $i < 9; $i++) {
        if (false === ($char = fgetc($fp))) {exit(23);}
        $Token->data.=$char;
      }
      if ($Token->data==".IPPcode22") {
        $Token->type="header";
        return $Token;
      }else{exit(21);}
    }
    if ($state=="s3") {
      deb_lex("s3");
      while (($char!="\n")and($char!="\r")) {
        if (false === ($char = fgetc($fp))) {if (feof($fp)) {$state="f3";$Token->data.=$char;continue;}exit(23);}
      }
      $state="s1";
      continue;
    }
    if ($state=="s4") {
      deb_lex("s4");
      while (true) { //TODo white space # a tak
        if (false === ($char = fgetc($fp))) {if (feof($fp)) {$state="d1";break;}exit(23);}
        if ( ($char=="\n") or ($char==" ") or ($char=="\r") ) {
          $state="d1";
          break;
        }
        $Token->data.=$char;
        if ($char=="@") {
          if ($Token->data=="string@") {
            $state="f6";
            break;
          }
          if ($Token->data=="int@") {
            $state="f8";
            break;
          }
          if ($Token->data=="bool@") {
            $state="s8";
            break;
          }
          if ($Token->data=="nil@") {
            $state="s9";
            break;
          }
          if (($Token->data=="GF@")or($Token->data=="LF@")or($Token->data=="TF@")) {
            $state="f9";
            break;
          }
        }
      }
      continue;
    }
    if ($state=="s8") {
      deb_lex("s8");
      $Token->data_val="";
      if (false === ($char = fgetc($fp))) {exit(23);}
        $Token->data.=$char;
        $Token->data_val.=$char;
      if ($char=="t") {
        for ($i=0; $i < 3; $i++) {
          if (false === ($char = fgetc($fp))) {exit(23);}
          $Token->data.=$char;
          $Token->data_val.=$char;
        }
        if ($Token->data_val=="true") {
          $Token->type="bool";
          return $Token;
        }else{exit(23);}
      }elseif($char=="f"){
        for ($i=0; $i < 4; $i++) {
          if (false === ($char = fgetc($fp))) {exit(23);}
          $Token->data.=$char;
          $Token->data_val.=$char;
        }
        if ($Token->data_val=="false") {
          return $Token;
        }else{exit(23);}
      }
    }
    if ($state=="s9") {
      deb_lex("s9");
      for ($i=0; $i < 3; $i++) {
        if (false === ($char = fgetc($fp))) {exit(23);}
        $Token->data.=$char;
      }
    }
    if ($state=="f3") {
      $Token->type="EOF";
      return $Token;
    }
    if ($state=="f6") {
      deb_lex("f6");
      $Token->data_val="";
      while (true) { //TODo # ? BTW nevyřešil jsem zde \ASCII
        if (false === ($char = fgetc($fp))) {exit(23);}
        if (($char=="\n")or($char==" ") or ($char=="\r")) {break;}
        $Token->data.=$char;
        $Token->data_val.=$char;
      }
      $Token->type="string";
      return $Token;
    }
    if ($state=="f8") {
      deb_lex("f8");
      $Token->data_val="";
      while (true) { //TODo # ? BTW nevyřešil jsem zde \ASCII
        if (false === ($char = fgetc($fp))) {exit(23);}
        if (($char=="\n")or($char==" ")or($char=="\r")) {break;}
        if ((strpos($numbers,$char)==false)) {
          exit("expected num, got $char");
        }
        $Token->data_val.=$char;
        $Token->data.=$char;
      }
      $Token->type="int";
      return $Token;
    }
    if ($state=="f9") {
      deb_lex("f9");
      $Token->type="variable";
      while (true) {
        if (false === ($char = fgetc($fp))) {exit(23);}
        if (($char=="\n")or($char==" ")or($char=="\r")) {return $Token;}
        if ((strpos($letters,$char)==false)and(strpos($special,$char)==false)) {
          exit("f9 unexpected char, got:$char");
        }
        $Token->data.=$char;
      }
    }
    if ($state=="d1"){
      deb_lex("d1");
      if ($Token->data=="MOVE") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="CREATEFRAME") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="PUSHFRAME") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="POPFRAME") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="DEFVAR") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="CALL") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="RETURN") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="PUSH") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="POPS") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="ADD") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="SUB") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="MUL") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="IDIV") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="LT") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="GT") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="EQ") {$Token->type="Instruction";return $Token;}
      if ($Token->data=='AND') {$Token->type="Instruction";return $Token;}
      if ($Token->data=='OR') {$Token->type="Instruction";return $Token;}
      if ($Token->data=='NOT') {$Token->type="Instruction";return $Token;}
      if ($Token->data=="INT2CHAR") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="READ") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="WRITE") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="CONCAT") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="STRLEN") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="GETCHAR") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="SETCHAR") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="TYPE") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="LABEL") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="JUMP") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="JUMPIFEQ") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="JUMPIFNEQ") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="EXIT") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="DPRINT") {$Token->type="Instruction";return $Token;}
      if ($Token->data=="BREAK") {$Token->type="Instruction";return $Token;}
      $Token->type="label";
      return $Token;
    }


  }
}
?>
