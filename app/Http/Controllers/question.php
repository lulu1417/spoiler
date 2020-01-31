<?php

//row
$star = 9;
$row = 0;
$length = 9;
while($row < 5) {

    //middle
    $arr[$row][$length/2] = '*';

    //col
    for ($add = 1; $add < $star/2; $add++) {
        $arr[$row][4 + $add] = '*';
        $arr[$row][4 - $add] = '*';
    }

    for($add = $star/2; $add < $length/2; $add++){
        $arr[$row][$length+$add] = ' ';
    }

    $row ++;
    $star -= 2;

}

for($i=0; $i < $length ; $i ++){
    for($j=0; $j < $length ; $j ++){
        echo $arr[$i][$j];
    }
    echo "\n";
}






/*
   ** **
 *******
*********
*********
 *******
  *****
   ***
    *

 */
