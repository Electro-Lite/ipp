import xml.etree.ElementTree as ET
###### Values
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
# Intereter
######################
class Interpreter:
    def visit(self,node):
        method_name=f'visit_{node.tag}Node'
        method = getattr(self,method_name,self.no_visit_method)
        return method(node)
    def no_visit_method(self, node):
        raise Exception(f'No visit_{node.tag} method defined')
    ################################
    # Interpet nodes general
    ################################
    def visit_programNode(self, node):
        print("found ProgramNode")
        a=len(list(node))
        for i in range(a):
            self.visit(node[i])
    def visit_instructionNode(self, node):
        print("found Instruction node")
        #self.visit(node.get("opcode"))
        inst = f'visit_{node.get("opcode")}Node'
        inst = getattr(self,inst,self.no_visit_method)
        inst(node)
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
    def visit_WRITENode(self, node):
        print("of type WRITE ")
        f = open("./out.txt", "a")
        f.write(node[0].text)
        f.close()

def run():
    #Parse xml
    tree=ET.parse('./ipp-2022-tests/interpret-only/arithmetic/correct.src')
    node=tree.getroot()
    # Run Program
    interpreter= Interpreter()
    interpreter.visit(node)
run()
