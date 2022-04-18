import xml.etree.ElementTree as ET
##############
# values
##############
class VarAccesNode:
    def __init__(self,var_name_tok):
        self.var_name_tok = var_name_tok
        self.pos_start = self.var_name_tok.pos_start
        self.pos_end = self.var_name_tok.pos_end
class Number:
    def __init__(self, value):
        self.value = value
        self.set_pos()
    def set_pos(self, pos_start=None, pos_end=None):
        self.pos_start = pos_start
        self.pos_end = pos_end
        return self
    def added_to(self, other):
        if isinstance(other,number):
            return Number(self.value + other.value)
    def subbed_by(self, other):
        if isinstance(other,number):
            return Number(self.value - other.value)
    def multed_by(self, other):
        if isinstance(other,number):
            return Number(self.value * other.value)
    def dived_by(self, other):
        if isinstance(other,number):
            return Number(self.value / other.value)
    def __repr__(self):
        return str(self.value)
######################
# Symbol Table
######################
class SymbolTable:
    def __init__(self):
        self.symbols = {}
        self.types = {}
        self.parent = None

    def get_val(self, name):
        value = self.symbols.get(name, None)
        if value == None and self.parent:
            return self.parent.get(name)
        return value
    def get_type(self, name):
        value = self.types.get(name, None)
        if value == None and self.types:
            return self.parent.get_type(name)
        return value

    def set(self, name, value, type):
        self.symbols[name] = value
        self.types[name] = type
    def set_val(self, name, value):
        self.symbols[name] = value
    def set_type(self, name, type):
        self.types[name] = type

    def remove(self, name):
        del self.symbols[name]

######################
# Error handling
######################
class Error:
    def __init__(self, pos_start, pos_end, error_name, details):
        self.pos_start = pos_start
        self.pos_end = pos_end
        self.error_name = error_name
        self.details = details

    def as_string(self):
        result  = f'{self.error_name}: {self.details}\n'
        result += f'File {self.pos_start.fn}, line {self.pos_start.ln + 1}'
        result += '\n\n' + string_with_arrows(self.pos_start.ftxt, self.pos_start, self.pos_end)
        return result
class RTError(Error):
    def __init__(self, pos_start, pos_end, details, context):
        super().__init__(pos_start, pos_end, 'Runtime Error', details)
        self.context = context
######################
# Context
######################
class Context:
    def __init__(self,display_name,parent=None,parent_entry_pos=None):
        self.display_name = display_name
        self.parent = parent
        self.parent_entry_pos = parent_entry_pos
        self.symbol_table = None
######################
# Intereter
######################
class Interpreter:
    def visit(self,node,context):
        method_name=f'visit_{node.tag}Node'
        method = getattr(self,method_name,self.no_visit_method)
        return method(node,context)
    def no_visit_method(self, node,context):
        if node.get("language")=="IPPcode22":
            raise Exception(f'No visit_{node.tag} method defined')
        else:
            raise Exception(f'No visit_{node.get("opcode")}NODE method defined')
    ################################
    # Interpet nodes general
    ################################
    def visit_programNode(self, node,context):
        print("found ProgramNode")
        a=len(list(node))
        for i in range(a):
            self.visit(node[i],context)
    def visit_instructionNode(self, node,context):
        print("found Instruction node")
        inst = f'visit_{node.get("opcode")}Node'
        inst = getattr(self,inst,self.no_visit_method)
        inst(node,context)
        #inst(node,context)
    ################################
    # Interpet nodes arith
    ################################
    def visit_NumberNode(self, node, context):
        return RTResult().success(Number(node.tok.value).set_context(context).set_pos(node.pos_start, node.pos_end))

    def visit_BinOpNode(self, node, context):
        res = RTResult()
        left = res.register(self.visit(node.left_node, context))
        if res.error: return res
        right = res.register(self.visit(node.right_node, context))
        if res.error: return res
        if node.op_tok.type == TT_PLUS:
            result, error = left.added_to(right)
        elif node.op_tok.type == TT_MINUS:
            result, error = left.subbed_by(right)
        elif node.op_tok.type == TT_MUL:
            result, error = left.multed_by(right)
        elif node.op_tok.type == TT_DIV:
            result, error = left.dived_by(right)
        if error:
            return res.failure(error)
        else:
            return res.success(result.set_pos(node.pos_start, node.pos_end))
    def visit_UnaryOpNode(self, node, context):
        res = RTResult()
        number = res.register(self.visit(node.node, context))
        if res.error: return res
        error = None
        if node.op_tok.type == TT_MINUS:
            number, error = number.multed_by(Number(-1))
        if error:
            return res.failure(error)
        else:
            return res.success(number.set_pos(node.pos_start, node.pos_end))
    ################################
    # Interpet nodes IO
    ################################
    def visit_WRITENode(self, node,context):
        print("of type WRITE ")
        f = open("./out.txt", "a")
        f.write(node[0].text)
        f.close()
    ################################
    # Variavle Nodes
    ################################
    def visit_VarAccessNode(self, node, context):
        #res = RTResult()
        var_name = node.var_name_tok.value
        value = context.symbol_table.get(var_name)

        if not value:
            return res.failure(RTError(
            node.pos_start, node.pos_end,
            f"'{var_name}' is not defined",
            context
            ))

        value = value.copy().set_pos(node.pos_start, node.pos_end)
        return res.success(value)
    def visit_MOVENode(self, node, context):

        print("it is MOVE node")
        #res = RTResult()
        target_var_name = node[0].text[3:]
        target_var_frame=node[0].text[:2]
        assign_type= node[1].get("type")
        if assign_type == "var":
            value=context.symbol_table.get(node[1].text[3:]) #TODO frames, so far only curF
            type=value=context.symbol_table.get(node[1].text[3:])
            context.symbol_table.set(target_var_name,value,type)
        else:
            value=node[1].text
            type=node[1].get("type")
            context.symbol_table.set(target_var_name,value,type)
    def visit_DEFVARNode(self, node, context):
        print("it is DEFVAR node")
        #res = RTResult()
        var_name = node[0].text[3:]
        frame=node[0].text[:2]
        #value = res.register(self.visit(node.value_node, context))
        #if res.error: return res

        context.symbol_table.set(var_name,"","")
    ################################
    # Symb methods
    ################################
    def get_symb_value(self, node, type, context):
        result=0
        node_type=node.get("type")
        if node_type=="int":
            return int(node.text)
        elif node_type=="var":
            name=node.text[3:]
            if context.symbol_table.get_type(name)=="int":
                return int(context.symbol_table.get_val(name))
        elif type=="string" or type=="bool":
            return node.text;
        else:
            raise Exception("bad type")
    def get_symb_type(self, node, context):
        result=0
        node_type=node.get("type")
        if node_type=="var":
            name=node.text[3:]
            node_type= context.symbol_table.get_type(name)
            return node_type
        else:
            return node_type;
    ################################
    # Arithmetic nodes
    def visit_ADDNode(self,node,context):
        print("it is ADD node, result: ",end="")
        result=self.get_symb_value(node[1],"int",context)
        result+=self.get_symb_value(node[2],"int",context)
        context.symbol_table.set_val(node[0].text[3:],result)
        context.symbol_table.set_type(node[0].text[3:],"int")
        print(context.symbol_table.get_val(node[0].text[3:]))
    def visit_MULNode(self,node,context):
        print("it is MUL node, result: ",end="")
        result = self.get_symb_value(node[1],"int",context)
        result*= self.get_symb_value(node[2],"int",context)
        context.symbol_table.set_val(node[0].text[3:],result)
        context.symbol_table.set_type(node[0].text[3:],"int")
        print(context.symbol_table.get_val(node[0].text[3:]))
    def visit_SUBNode(self,node,context):
        print("it is SUB node, result: ",end="")
        result = self.get_symb_value(node[1],"int",context)
        result-= self.get_symb_value(node[2],"int",context)
        context.symbol_table.set_val(node[0].text[3:],result)
        context.symbol_table.set_type(node[0].text[3:],"int")
        print(context.symbol_table.get_val(node[0].text[3:]))
    def visit_IDIVNode(self,node,context):
        print("it is IDIV node, result: ",end="")
        result = self.get_symb_value(node[1],"int",context)
        result/= self.get_symb_value(node[2],"int",context)
        context.symbol_table.set_val(node[0].text[3:],result)
        context.symbol_table.set_type(node[0].text[3:],"int")
        print(context.symbol_table.get_val(node[0].text[3:]))
    ################################
    # Logic nodes
    ################################
    def visit_LTNode(self,node,context):
        print("its LT node")
        type=self.get_symb_type(node[1],context)
        if type==self.get_symb_type(node[2],context):
            if type=="int":
                result= self.get_symb_value(node[1],"int",context) < self.get_symb_value(node[2],"int",context)
            if type=="bool":
                bool1=0
                bool2=0
                if self.get_symb_value(node[1],"bool",context):
                    bool1=1
                if self.get_symb_value(node[2],"bool",context):
                    bool2=1
                result=bool1<bool2
            if type == "string":
                result= self.get_symb_value(node[1],"string",context) < self.get_symb_value(node[2],"string",context)
            if result==True:
                result="true"
            elif result==False:
                result="false"
        context.symbol_table.set_val(node[0].text[3:],result)
        context.symbol_table.set_type(node[0].text[3:],"bool")
        print(" " + str(self.get_symb_value(node[1],"string",context)) + " < " + str(self.get_symb_value(node[2],"string",context)))
        print (" " + result)
    def visit_GTNode(self,node,context):
        print("its GT node")
        print("its LT node")
        type=self.get_symb_type(node[1],context)
        if type==self.get_symb_type(node[2],context):
            if type=="int":
                result= self.get_symb_value(node[1],"int",context) > self.get_symb_value(node[2],"int",context)
            if type=="bool":
                bool1=0
                bool2=0
                if self.get_symb_value(node[1],"bool",context):
                    bool1=1
                if self.get_symb_value(node[2],"bool",context):
                    bool2=1
                result=bool1 > bool2
            if type == "string":
                result= self.get_symb_value(node[1],"string",context) > self.get_symb_value(node[2],"string",context)
            if result==True:
                result="true"
            elif result==False:
                result="false"
        context.symbol_table.set_val(node[0].text[3:],result)
        context.symbol_table.set_type(node[0].text[3:],"bool")
        print(" " + str(self.get_symb_value(node[1],"string",context)) + " > " + str(self.get_symb_value(node[2],"string",context)))
        print (" " + result)
    def visit_EQNode(self,node,context):
        print("its EQ node")
        print("its LT node")
        type=self.get_symb_type(node[1],context)
        if type==self.get_symb_type(node[2],context):
            if type=="int":
                result= self.get_symb_value(node[1],"int",context) == self.get_symb_value(node[2],"int",context)
            if type=="bool":
                bool1=0
                bool2=0
                if self.get_symb_value(node[1],"bool",context):
                    bool1=1
                if self.get_symb_value(node[2],"bool",context):
                    bool2=1
                result=bool1 == bool2
            if type == "string":
                result= self.get_symb_value(node[1],"string",context) == self.get_symb_value(node[2],"string",context)
            if result==True:
                result="true"
            elif result==False:
                result="false"
        context.symbol_table.set_val(node[0].text[3:],result)
        context.symbol_table.set_type(node[0].text[3:],"bool")
        print(" " + str(self.get_symb_value(node[1],"string",context)) + " == " + str(self.get_symb_value(node[2],"string",context)))
        print (" " + result)
    def visit_ANDNode(self,node,context):
        print("its AND node")
        print(True>True)
        exit()


def run():
    #Parse xml
    tree=ET.parse('./ipp-2022-tests/interpret-only/arithmetic/correct2.src')
    #tree=ET.parse('./Testy/supplementary-tests/int-only/write_test.src')
    node=tree.getroot()
    # Run Program
    interpreter= Interpreter()
    # init context
    context = Context('<program>')
    #init symTable
    global global_symbol_table
    global_symbol_table = SymbolTable()
    context.symbol_table=global_symbol_table
    result=interpreter.visit(node,context)
run()
