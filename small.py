import xml.etree.ElementTree as ET
tree=ET.parse('.\Testy\supplementary-tests\int-only\write_test.src')
root=tree.getroot()
def func():
    global x
    x=10
func()
print(x)
