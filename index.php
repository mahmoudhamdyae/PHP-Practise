<?php 
// This is comment
# Comment Line
/*
* Comment Block
*/

echo "Hello, World!", "Ali"; // Faster // Can print more scalar type content
print "Welcome to ITI";

echo true; // 1
echo false; // (empty)

/*
* Data Types
* 1. Scalar Data Type [String, Integer, Float, Boolean]
* 2. Compound Data Type [array, object]
* 3. Special Data Type [Resource, Null]
*
* PHP is loosely typed language 
*/

$x = "Ali"; // String
$y = 10; // Integer
$z = 10.5; // Float
$w = true; // Boolean

$arr1 = array(10, 20, 30, 1.2, true); // Array
$arr2 = [10, 20, 30]; // Array
print_r($arr1); // Can not use echo for compound data type

echo "<br>";
echo $x;

var_dump($y); // Show data type and value

if (is_string($x)) {
    echo "<br> $x is String";
}

echo gettype($z); // Show data type only
settype($z, "integer"); // Change data type
echo gettype($z);
echo (int) $z; // Type Casting
$student = 'Ahmed';
echo 'Welcome $student'; // Variable Parsing - Print studentSingle
echo "Welcome $student"; // No Variable Parsing - Print Ahmed

echo '<h1>Welcome to ITI</h1>'; // HTML Code

$relation = "father";
$father = "Ahmed";
echo $$relation; // Variable Variables - Print Ahmed
if (isset($student)) {
    echo "<br> Variable is defined";
}

if (empty($student)) {
    echo "<br> Variable is empty";
} else {
    echo "<br> Variable is not empty";
}

/// Functions
function sum($a, $b, $c = 0) {
    global $x; // Access Global Variable inside function
    static $age = 20; // Initialize only once
    return $a + $b;
}

const PI = 3.14; // Constant Variable
define("MAX_SIZE", 100); // Constant Variable

$a = "Hello";
$b = " World";
echo $a . $b; // Concatenation Operator

// echo &$a;
unset($a); // Delete Variable

echo "For Loop";
for ($i = 0; $i < 5; $i++) {
    echo $i;
}

echo count($arr1);
foreach($arr1 as $item) {
    echo $item;
}

// Associative Array
$names = [
    "father" => "Ahmed",
    "mother" => "Asmaa",
    "son" =>  "Ashraf"
];

foreach($names as $key=>$value) {
    echo $key.": ".$value."<br>"; 
}

/// Super  Global Array
var_dump($_SERVER);
var_dump(PHP_VERSION);