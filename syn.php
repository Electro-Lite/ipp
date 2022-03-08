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
    $Token=Get_token();
    if ($Token->type=="int") {
      $Token=Get_token();
      if ($Token->type=="int") {
        return true
      }
    }
  }
  return false;
}
function SUB(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    if ($Token->type=="int") {
      $Token=Get_token();
      if ($Token->type=="int") {
        return true
      }
    }
  }
  return false;
}
function MUL(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    if ($Token->type=="int") {
      $Token=Get_token();
      if ($Token->type=="int") {
        return true
      }
    }
  }
  return false;
}
function IDIV(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    if ($Token->type=="int") {
      $Token=Get_token();
      if ($Token->type=="int") {
        return true
      }
    }
  }
  return false;
}
function LT(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    $type=$Token->type;
    if (($type=="int")or($type=="string")or($type=="bool")) {
      $Token=Get_token();
      if ($Token->type==$type) {
        return true;
      }
    }
  }
  return false;
}
function GT(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    $type=$Token->type;
    if (($type=="int")or($type=="string")or($type=="bool")) {
      $Token=Get_token();
      if ($Token->type==$type) {
        return true;
      }
    }
  }
  return false;
}
function EQ(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    $type=$Token->type;
    if (($type=="int")or($type=="string")or($type=="bool")or($type=="nil")) {
      $Token=Get_token();
      if ($Token->type==$type) {
        return true;
      }
    }
  }
  return false;
}
function AND(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    $type=$Token->type;
    if ($type=="bool") {
      $Token=Get_token();
      if ($Token->type==$type) {
        return true;
      }
    }
  }
  return false;
}
function OR(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    $type=$Token->type;
    if ($type=="bool") {
      $Token=Get_token();
      if ($Token->type==$type) {
        return true;
      }
    }
  }
  return false;
}
function NOT(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    $Token=Get_token();
    $type=$Token->type;
    if ($type=="bool") {
        return true;
    }
  }
  return false;
}
function INT2CHAR(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token=Get_token();
    if ($Token->type=="int") {
      return true;
    }
  }
return false;
}
function STR2INT(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token=Get_token();
    if ($Token->type=="string") {
      global $Token=Get_token();
      if ($Token->type=="int") {
        return true;
      }
    }
  }
  return false;
}
function READ(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token=Get_token();
    if (($Token->type=="string")or($Token->type=="int")or($Token->type=="bool")) {
      return true;
    }
  }
  return false;
}
function WRITE(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token=Get_token();
    if (($Token->type=="string")or($Token->type=="int")or($Token->type=="bool")or($Token->type=="nil")) {
      return true;
    }
  }
  return false;
}
function CONCAT(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token=Get_token();
    if ($Token->type=="string") {
      global $Token=Get_token();
      if ($Token->type=="string") {
        return true;
      }
    }
  }
  return false;
}
function STRLEN(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token=Get_token();
    if ($Token->type=="string") {
      return true;
    }
  }
  return false;
}
function GETCHAR(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token=Get_token();
    if ($Token->type=="string") {
      global $Token=Get_token();
      if ($Token->type=="int") {
        return true;
      }
    }
  }
  return false;
}
function SETCHAR(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token=Get_token();
    if ($Token->type=="int") {
      global $Token=Get_token();
      if ($Token->type=="string") {
        return true;
      }
    }
  }
  return false;
}
function TYPE(){
  global $Token=Get_token();
  if ($Token->type=="variable") {
    global $Token=Get_token();
    if (($Token->type=="string")or($Token->type=="int")or($Token->type=="bool")or($Token->type=="nil")) {
      return true;
    }
  }
  return false;
}
function LABEL(){
  global $Token=Get_token();
  if ($Token->type=="label") {
    return true;
  }
  return false;
}
function JUMP(){
  global $Token=Get_token();
  if ($Token->type=="label") {
    return true;
  }
  return false;
}
function JUMPIFEQ(){
  global $Token=Get_token();
  if ($Token->type=="label") {
    $Token=Get_token();
    $type=$Token->type;
    if (($type=="int")or($type=="string")or($type=="bool")or($type=="nil")) {
      $Token=Get_token();
      if ($Token->type==$type) {
        return true;
      }
    }
  }
  return false;
}
function JUMPIFNEQ(){
  global $Token=Get_token();
  if ($Token->type=="label") {
    $Token=Get_token();
    $type=$Token->type;
    if (($type=="int")or($type=="string")or($type=="bool")or($type=="nil")) {
      $Token=Get_token();
      if ($Token->type==$type) {
        return true;
      }
    }
  }
  return false;
}
function EXIT(){
  global $Token=Get_token();
  if ($Token->type=="int") {
    return true;
  }
  return false;
}
function DPRINT(){
  global $Token=Get_token();
  if (($Token->type=="int")or($Token->type=="string")or($Token->type=="bool")or($Token->type=="nil")) {
    return true;
  }
  return false;
}
function BREAK(){
  return true;
}
?>
