<?php
$ord=0;
$last_inst;
$arg_num=0;
$dom = new DOMDocument('1.0','UTF-8');
$dom->formatOutput = true;
$root = $dom->createElement('program');
$dom->appendChild($root);
function rt($str,$e){
  //echo("$str"." $e"."\n");
  if ($e==0) {
    exit(23);
  }else {return true;}
}
function gen(){
  global $Token;
  global $dom;
  global $root;
  global $ord;
  global $last_inst;
  global $arg;
  global $arg_num;

  if ($Token->type=="header") {
    $root->setAttribute('language',"IPPcode22");
    return;
  }
  if ($Token->type=="Instruction") {
    $ord++;
    $arg_num=0;
    $last_inst= $dom->createElement('instruction');
    $root->appendChild($last_inst);
    $last_inst->setAttribute('order', $ord);
    $last_inst->setAttribute('opcode',strtoupper( $Token->data));
    return;
  }
  else{
    $arg_num++;
    $arg= $dom->createElement(("arg".strval($arg_num)));
    $last_inst->appendChild($arg);
    $arg->setAttribute("type",$Token->type );
    $arg->textContent=$Token->data;
    if ($Token->type=="variable") {
      $arg->setAttribute("type","var");
    }
    elseif ($Token->type=="string") {
      $arg->textContent=$Token->data_val;
    }
    elseif ($Token->type=="int") {
      $arg->textContent=$Token->data_val;
    }
    elseif ($Token->type=="bool") {
      $arg->textContent=$Token->data_val;
    }
    return;
  }
}

function syntax_f(){
   return program();
}
function program(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="header") {
    gen();
    $Token=Get_token();
    if ($Token->type=="EOF") {return rt("program",1);}
    if ($Token->type=="EOL"){
      return body();
    }
  }
  if($Token->type=="EOL"){
    return program();
  }
  exit(21);
  return rt("program",0);
}
function body(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="EOF") {
    return rt("body",1);
  }
  if ($Token->type=="EOL") {
    return body();
  }
  if ($Token->type=="Instruction") {
    return(instruction() and body());
  }
  return rt("body",0);
}
function instruction(){
  global $Token;
  if (!(function_exists("$Token->data"."_f"))) {
    exit(22);
  }
  return (("$Token->data"."_f")());
}
function MOVE_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" SIBN")) {
      gen();
      return rt("Move",1);
    }
  }
  return rt("MOVE_f",0);
}
function CREATEFRAME_f(){
  gen();
  return rt("CREATEFRAME",1);
}
function PUSHFRAME_f(){
  gen();
  return rt("PUSHFRAME",1);
}
function POPFRAME_f(){
  gen();
  return rt("POPFRAME_f",1);
}
function DEFVAR_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    return rt("DEFVAR",1);
  }
  return rt("DEFVAR",0);
}
function CALL_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="label") {
    gen();
    return rt("CALL",1);
  }
  return rt("CALL",0);
}
function RETURN_f(){
  gen();
  return rt("RETURN_f",1);
}
function PUSHS_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if (symb(" SIBN")) {
    gen();
    return rt("PUSHS",1);
  }
  return rt("PUSHS",0);
}
function POPS_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    return rt("POPS_f",1);
  }
  return rt("POPS_f",0);
}
function ADD_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" I")) {
      gen();
      $Token=Get_token();
      if (symb(" I")) {
        gen();
        return rt("ADD_f",1);
      }
    }
  }
  return rt("ADD_f",0);
}
function SUB_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" I")) {
      gen();
      $Token=Get_token();
      if (symb(" I")) {
        gen();
        return rt("SUB_f",1);
      }
    }
  }
  return rt("SUB_f",0);
}
function MUL_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" I")) {
      gen();
      $Token=Get_token();
      if (symb(" I")) {
        gen();
        return rt("MUL_f",1);
      }
    }
  }
  return rt("MUL_f",0);
}
function IDIV_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" I")) {
      gen();
      $Token=Get_token();
      if (symb(" I")) {
        gen();
        return rt("IDIV_f",1);
      }
    }
  }
  return rt("IDIV_f",0);
}
function LT_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    $type=$Token->type;
    if (symb(" IBS")) {
      gen();
      $Token=Get_token();
      if (($Token->type==$type)or($type=="variable")or($Token->type=="variable")) {
        gen();
        return rt("LT_f",1);
      }
    }
  }
  return rt("LT_f",0);
}
function GT_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    $type=$Token->type;
    if (symb(" IBS")) {
      gen();
      $Token=Get_token();
      if (($Token->type==$type)or($type=="variable")or($Token->type=="variable")) {
        gen();
        return rt("GT_f",1);
      }
    }
  }
  return rt("GT_f",0);
}
function EQ_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    $type=$Token->type;
    if (symb(" IBS")) {
      gen();
      $Token=Get_token();
      if (($Token->type==$type)or($type=="variable")or($Token->type=="variable")) {
        gen();
        return rt("EQ_f",1);
      }
    }
  }
  return rt("EQ_f",0);
}
function AND_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" B")) {
      gen();
      $Token=Get_token();
      if (symb(" B")) {
        gen();
        return rt("AND_f",1);
      }
    }
  }
  return rt("AND_f",0);
}
function OR_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" B")) {
      gen();
      $Token=Get_token();
      if (symb(" B")) {
        gen();
        return rt("OR_f",1);
      }
    }
  }
  return rt("OR_f",0);
}
function NOT_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" B")) {
      gen();
        return rt("NOT_f",1);
    }
  }
  return rt("NOT_f",0);
}
function INT2CHAR_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
  $Token=Get_token();
    if (symb(" I")) {
      gen();
      return rt("INT2CHAR_f",1);
    }
  }
  return rt("INT2CHAR_f",0);
}
function STR2INT_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" S")) {
      gen();
      $Token=Get_token();
      if (symb(" I")) {
        gen();
        return rt("STR2INT_f",1);
      }
    }
  }
  return rt("STR2INT_f",0);
}
function READ_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
  $Token=Get_token();
    if (($Token->data=="int")or($Token->data=="bool")or($Token->data=="string")) {
      $Token->type="type";
      gen();
      return rt("READ_f",1);
    }
  }
  return rt("READ_f",0);
}
function WRITE_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if (symb(" SBIN")) {
    gen();
    return rt("WRITE_f",1);
  }
  return rt("WRITE_f",0);
}
function CONCAT_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" S")) {
      gen();
      $Token=Get_token();
      if (symb(" S")) {
        gen();
        return rt("CONCAT_f",1);
      }
    }
  }
  return rt("CONCAT_f",0);
}
function STRLEN_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" S")) {
      gen();
      return rt("STRLEN_f",1);
    }
  }
  return rt("STRLEN_f",0);
}
function GETCHAR_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" S")) {
      gen();
      global $Token;
      $Token=Get_token();
      if (symb(" I")) {
        gen();
        return rt("GETCHAR_f",1);
      }
    }
  }
  return rt("GETCHAR_f",0);
}
function SETCHAR_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" I")) {
      gen();
      global $Token;
      $Token=Get_token();
      if (symb(" S")) {
        gen();
        return rt("SETCHAR_f",1);
      }
    }
  }
  return rt("SETCHAR_f",0);
}
function TYPE_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    gen();
    $Token=Get_token();
    if (symb(" SIBN")) {
      gen();
      return rt("TYPE_f",1);
    }
  }
  return rt("TYPE_f",0);
}
function LABEL_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="label") {
    gen();
    return rt("LABEL_f",1);
  }
  return rt("LABEL_f",0);
}
function JUMP_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="label") {
    gen();
    return rt("JUMP_f",1);
  }
  return rt("JUMP_f",0);
}
function JUMPIFEQ_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="label") {
    gen();
    $Token=Get_token();
    if ($Token->type=="variable") {
      gen();
      $Token=Get_token();
      if (symb(" SBIN")) {
        gen();
        return rt("JUMPIFEQ_f",1);
      }
    }
    if (symb(" SBIN")) {
      gen();
      $type=$Token->type;
      $Token=Get_token();
      if (($Token->type==$Type)or($Token->type=="variable")) {
        gen();
        return rt("JUMPIFEQ_f",1);
      }
    }
  }
  return rt("JUMPIFEQ_f",0);
}
function JUMPIFNEQ_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if ($Token->type=="label") {
    gen();
    $Token=Get_token();
    if ($Token->type=="variable") {
      gen();
      $Token=Get_token();
      if (symb(" SBIN")) {
        gen();
        return rt("JUMPIFEQ_f",1);
      }
    }
    if (symb(" SBIN")) {
      gen();
      $type=$Token->type;
      $Token=Get_token();
      if (($Token->type==$Type)or($Token->type=="variable")) {
        gen();
        return rt("JUMPIFEQ_f",1);
      }
    }
  }
  return rt("JUMPIFEQ_f",0);
}
function EXIT_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if (symb(" I")) {
    gen();
    return rt("EXIT_f",1);
  }
  return rt("EXIT_f",0);
}
function DPRINT_f(){
  gen();
  global $Token;
  $Token=Get_token();
  if (sybn("SBIN")) {
    gen();
    return rt("DPRINT_f",1);
  }
  return rt("DPRINT_f",0);
}
function BREAK_f(){
  gen();
  return rt("BREAK_f",1);
}
function symb($str){
  global $Token;
  if ($Token->type=="variable") {
    return rt("symb",1);
  }
  if (strpos($str,"I")!=false) {
    if ($Token->type=="int") {
      return rt("symb",1);
    }
  }
  if (strpos($str,"S")!=false) {
    if ($Token->type=="string") {
      return rt("symb",1);
    }
  }
  if (strpos($str,"B")!=false) {
    if ($Token->type=="bool") {
      return rt("symb",1);
    }
  }
  if (strpos($str,"N")!=false) {
    if ($Token->type=="nil") {
      return rt("symb",1);
    }
  }
  return rt("symb",0);
}

?>
