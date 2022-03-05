
<?php

// Object-styled definition of an employee
$employee_object = new stdClass;
$employee_object->name = "John Doe";
$employee_object->position = "Software Engineer";
$employee_object->address = "53, nth street, city";
$employee_object->status = "Best";
$employee_object->status .= "Bad";

// Display the employee contents
print_r($employee_object);
?>
