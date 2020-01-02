#!/usr/bin/php
<?php
function printLine($line){
  foreach ($line as $value) {
    if ($value == 0) {
      echo " ";
    } elseif($value == 1) {
      echo "#";
    }
  }
  echo "\n";
}

if (sizeof($argv) < 2) {
  echo("No input supplied, please enter a rule: ");
  $rule = substr(fgets(fopen('php://stdin', 'r'), 1024),0,-1);
  // exit;
} else {
  $rule = $argv[1]; 
}

//Convert to binary, zero pad, then split $rule
$newState = str_split(str_pad(decbin($rule), 8,"0", STR_PAD_LEFT), 1);

exec('tput cols', $out);
$width = $out[0];

$lastLine = array();
for ($x=0; $x < $width; $x++) { 
  $lastLine[$x] = rand(0,1);
}
printLine($lastLine);

while (true) {
  $nextLine = array(0);
  for ($x=0; $x < $width; $x++) {
    $nextLine[$x] = 0;
  }


  for ($x=0; $x < $width; $x++) { 
    $pattern = bindec($lastLine[0].$lastLine[1].$lastLine[2]);
    $nextLine[1] = $newState[7-$pattern];

    //TODO: use %width for index, rather than rotating the array
    array_push($lastLine, array_shift($lastLine));
    array_push($nextLine, array_shift($nextLine));
  }

  printLine($nextLine);
  $lastLine = $nextLine;
  usleep(100000);
}

?>