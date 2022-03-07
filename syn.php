<?php
function syntax_f(){
  program();
}
function program(){
  global $Token=Get_token();
  if ($Token->type=="header") {
    $Token=Get_token();
    if ($Token->type=="EOF") {return true;}
    if ($Token->type=="EOL"){
      return body();
    }
  }
  return false;
}

function body(){
  global $Token=Get_token();
  if ($Token->type=="EOF") {
    return true;
  }
  if ($Token->type=="EOL") {
    return body();
  }
  if ($Token->type=="Instruction") {
    return(instruction() and body())
  }
}
function instruction(){
  global $Token=Get_token();
  return ($Token->type());
}
function CREATEFRAME(){
  return true;
}
function PUSHFRAME(){
  return true;
}
function POPFRAME(){
  return true;
}
function DEFVAR(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    return true;
  }
  return false;
}
function CALL(){
  global $Token=Get_token();
  if ($Token->type=="label") {
    return true;
  }
  return false;
}
function RETURN(){
  return true;
}
function PUSHS(){
  global $Token=Get_token();
  if (($Token->type=="bool")or($Token->type=="string")or($Token->type=="int")or($Token->type=="nil")) {
    return true;
  }
  return false;
}
function POPS(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    return true;
  }
  return false;
}
function ADD(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token=Get_token();
    if ($Token->type=="int") {
      global $Token=Get_token();
      if ($Token->type=="int") {
        return true
      }
    }
  }
  return false;
}
function SUB(){

}
?>
