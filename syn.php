<?php
function rt($str,$e){
  echo("$str"."\n");
  if ($e==0) {
    exit("0");
  }else {return true;}
}

function syntax_f(){
   return program();
}
function program(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="header") {
    $Token=Get_token();
    if ($Token->type=="EOF") {return rt("program",1);}
    if ($Token->type=="EOL"){
      return body();
    }
  }
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
  return (("$Token->data"."_f")());
}
function MOVE_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    if (symb("SIBN")) {
      return rt("Move_pass",0);
    }
  }
  return rt("MOVE_f",0);
}
function CREATEFRAME_f(){
  return rt("CREATEFRAME",1);
}
function PUSHFRAME_f(){
  return rt("PUSHFRAME",1);
}
function POPFRAME_f(){
  return rt("POPFRAME_f",1);
}
function DEFVAR_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    return rt("DEFVAR",1);
  }
  return rt("DEFVAR",0);
}
function CALL_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="label") {
    return rt("CALL",1);
  }
  return rt("CALL",0);
}
function RETURN_f(){
  return rt("RETURN_f",1);
}
function PUSHS_f(){
  global $Token;
  $Token=Get_token();
  if (symb("SIBN")) {
    return rt("PUSHS",1);
  }
  return rt("PUSHS",0);
}
function POPS_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    return rt("POPS_f",1);
  }
  return rt("POPS_f",0);
}
function ADD_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    if (symb("I")) {
      $Token=Get_token();
      if (symb("I")) {
        return rt("ADD_f",1);
      }
    }
  }
  return rt("ADD_f",0);
}
function SUB_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    if (symb("I")) {
      $Token=Get_token();
      if (symb("I")) {
        return rt("SUB_f",1);
      }
    }
  }
  return rt("SUB_f",0);
}
function MUL_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    if (symb("I")) {
      $Token=Get_token();
      if (symb("I")) {
        return rt("MUL_f",1);
      }
    }
  }
  return rt("MUL_f",0);
}
function IDIV_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    if (symb("I")) {
      $Token=Get_token();
      if (symb("I")) {
        return rt("IDIV_f",1);
      }
    }
  }
  return rt("IDIV_f",0);
}
function LT_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    $type=$Token->type;
    if (symb("IBS")) {
      $Token=Get_token();
      if (($Token->type==$type)or($type=="variable")or($Token->type=="variable")) {
        return rt("LT_f",1);
      }
    }
  }
  return rt("LT_f",0);
}
function GT_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    $type=$Token->type;
    if (symb("IBS")) {
      $Token=Get_token();
      if (($Token->type==$type)or($type=="variable")or($Token->type=="variable")) {
        return rt("GT_f",1);
      }
    }
  }
  return rt("GT_f",0);
}
function EQ_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    $type=$Token->type;
    if (symb("IBS")) {
      $Token=Get_token();
      if (($Token->type==$type)or($type=="variable")or($Token->type=="variable")) {
        return rt("EQ_f",1);
      }
    }
  }
  return rt("EQ_f",0);
}
function AND_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    if (symb("B")) {
      $Token=Get_token();
      if (symb("B")) {
        return rt("AND_f",1);
      }
    }
  }
  return rt("AND_f",0);
}
function OR_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    if (symb("B")) {
      $Token=Get_token();
      if (symb("B")) {
        return rt("OR_f",1);
      }
    }
  }
  return rt("OR_f",0);
}
function NOT_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    if (symb("B")) {
        return rt("NOT_f",1);
    }
  }
  return rt("NOT_f",0);
}
function INT2CHAR_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token;
  $Token=Get_token();
    if (symb("I")) {
      return rt("INT2CHAR_f",1);
    }
  }
  return rt("INT2CHAR_f",0);
}
function STR2INT_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token;
  $Token=Get_token();
    if (symb("S")) {
      global $Token;
  $Token=Get_token();
      if (symb("I")) {
        return rt("STR2INT_f",1);
      }
    }
  }
  return rt("STR2INT_f",0);
}
function READ_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token;
  $Token=Get_token();
    if (symb("SIB")) {
      return rt("READ_f",1);
    }
  }
  return rt("READ_f",0);
}
function WRITE_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token;
  $Token=Get_token();
    if (symb("SIBN")) {
      return rt("WRITE_f",1);
    }
  }
  return rt("WRITE_f",0);
}
function CONCAT_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token;
  $Token=Get_token();
    if (symb("S")) {
      global $Token;
  $Token=Get_token();
      if (symb("S")) {
        return rt("CONCAT_f",1);
      }
    }
  }
  return rt("CONCAT_f",0);
}
function STRLEN_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token;
  $Token=Get_token();
    if (symb("S")) {
      return rt("STRLEN_f",1);
    }
  }
  return rt("STRLEN_f",0);
}
function GETCHAR_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token;
  $Token=Get_token();
    if (symb("S")) {
      global $Token;
  $Token=Get_token();
      if (symb("S")) {
        return rt("GETCHAR_f",1);
      }
    }
  }
  return rt("GETCHAR_f",0);
}
function SETCHAR_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token;
  $Token=Get_token();
    if (symb("I")) {
      global $Token;
  $Token=Get_token();
      if (symb("S")) {
        return rt("SETCHAR_f",1);
      }
    }
  }
  return rt("SETCHAR_f",0);
}
function TYPE_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token;
  $Token=Get_token();
    if (symb("SIBN")) {
      return rt("TYPE_f",1);
    }
  }
  return rt("TYPE_f",0);
}
function LABEL_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="label") {
    return rt("LABEL_f",1);
  }
  return rt("LABEL_f",0);
}
function JUMP_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="label") {
    return rt("JUMP_f",1);
  }
  return rt("JUMP_f",0);
}
function JUMPIFEQ_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="label") {
    $Token=Get_token();
    $type=$Token->type;
    if (symb("SIBN")) {
      $Token=Get_token();
      if (($Token->type==$type)or($Token->type=="variable")) {
        return rt("JUMPIFEQ_f",1);
      }
    }
    elseif (($Token->type=="variable")) {
      $Token=Get_token();
      if (($Token->type=="variable")or  ($Token->type=="int")or($Token->type=="string")or($Token->type=="bool")or($Token->type=="nil")) {
        return rt("JUMPIFEQ_f",1);
      }
    }
  }
  echo "false";
  return rt("JUMPIFEQ_f",0);
}
function JUMPIFNEQ_f(){
  global $Token;
  $Token=Get_token();
  if ($Token->type=="label") {
    $Token=Get_token();
    $type=$Token->type;
    if (symb("SIBN")) {
      $Token=Get_token();
      if (($Token->type==$type)or($Token->type=="variable")) {
        return rt("JUMPIFNEQ_f",1);
      }
    }
  }
  return rt("JUMPIFNEQ_f",0);
}
function EXIT_f(){
  global $Token;
  $Token=Get_token();
  if (symb("I")) {
    return rt("EXIT_f",1);
  }
  return rt("EXIT_f",0);
}
function DPRINT_f(){
  global $Token;
  $Token=Get_token();
  if (sybn("SBIN")) {
    return rt("DPRINT_f",1);
  }
  return rt("DPRINT_f",0);
}
function BREAK_f(){
  return rt("BREAK_f",1);
}
function symb($str){
  global $Token;
  exit(strval(strpos($str,"S")!=false));
  if ($Token->type=="variable") {
    return rt("symb",1);
  }
  elseif (strpos($str,"I")!=false) {
    if ($Token->type=="int") {
      return rt("symb",1);
    }
  }
  elseif (strpos($str,"S")!=false) {
    if ($Token->type=="string") {
      return rt("symb",1);
    }
  }
  elseif (strpos($str,"B")!=false) {
    if ($Token->type=="bool") {
      return rt("symb",1);
    }
  }
  elseif (strpos($str,"N")!=false) {
    if ($Token->type=="nil") {
      return rt("symb",1);
    }
  }
  return rt("symb",0);
}

?>
