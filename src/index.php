<?php

/* 
################ ТЗ ################
https://opendata.digital.gov.ru/registry/numeric/downloads/
базу с номерами и написать функционал 
который из данных диапазонов сделает список префиксов (уникальных масок) 
только по операторам (по регионам не надо).
Префикс это уникальная маска номера, к примеру 7911ХХХХХХХ, где Х - любое число.
Постараться свести кол-во префиксов к минимуму.

Как ожидаемый итог - скрипт который может обработать файл и предоставить результат в виде массива 
(пример ['оператор 1'=>[7911,791222,791223],'оператор 2'=>[7921,792222,792223]])
*/

// var_dump('test');

// $file = fopen(__DIR__. '/ABC-3xx.csv', 'r');
// var_dump($file);

// while ($a <= 10) {
//     # code...
// }
// $rs = fgetcsv($file, null, ';');
// var_dump($rs);

phpinfo();

$path_to_file = __DIR__ . '/downloads/';
$fp = fopen($path_to_file . '3.csv', 'w');
$ch = curl_init('https://opendata.digital.gov.ru/downloads/ABC-3xx.csv?1662393366550');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_exec($ch);
var_dump(curl_error($ch));

$row = 1;
if (($handle = fopen($path_to_file . '3.csv', "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ";")) !== false) {
        $num = count($data);
        echo "<p> $num полей в строке $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            echo $data[$c] . "<br />\n";
        }
    }
    fclose($handle);
}
