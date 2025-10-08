<?php
$role = "admin_sales";
$is_logged_in = true;
// if($role == "admin"){
//     echo "Welcome, Admin!";
// }elseif($role == "editor"){
//     echo "Welcome, Editor!";
// }else{
//     echo "Welcome, Guest!";
// }

// switch($role){
//     case "admin":
//     case "admin_sales":
//         echo "Welcome, Admin!";
//         break;
//     case "editor":
//         echo "Welcome, Editor!";
//         break;
//     default:
//         echo "Welcome, Guest!";
//         break;
// }

$greeting = match($role){
    "admin" =>"Welcome, Admin!",
    "admin_sales"=> "Welcome, Admin Sales!",
    "editor" => "Welcome, Editor!",
    default => "Welcome, Guest!",
};
echo $greeting."\n";
$i = 0;
for($i; $i < 10; $i++) { 
    echo "Odd number: $i<br>";
}

$i = 0;
while($i < 10){
    echo "Even number: $i<br>";
    $i++;
}

$i = 0;
do{
    echo "Do While number: $i<br>";
    $i++;
}while($i < 10);


$items = ["Apple", "Banana", "Cherry"];
echo $items[1]."<br>";
$items[1] = "Orange";
foreach($items as $key => $value){
    if($key >=1) {
        echo "Item $key: $value<br>";
    }
}

function sum($a, $b){
    $a += 2;
    $b += 3;
    $value = $a + $b;
    
    return $value;
}
echo sum(5, 10)."<br>";
echo sum(50, 1)."<br>";
echo sum(5, 10)."<br>";
echo sum(5, 10)."<br>";
echo sum(5, 10)."<br>";

$associative = [
    "name" => "John",
    "age" => 30,
    "city" => "New York"
];

$associative["phone"] = "123-456-7890";

echo "Name: ".$associative["name"]."<br>";
echo "Age: ".$associative["age"]."<br>";
echo "City: ".$associative["city"]."<br>";

foreach($associative as $key => $value){
    echo "$key: $value<br>";
}

$multidimensional = [
    "data"=>"user",
    "users" => [
            [
                "name" => "Alice",
                "age" => 25
            ],
            [
                "name" => "Bob",
                "age" => 28
            ],
            [
                "name" => "Charlie",
                "age" => 22
            ]
    ]
];
?>