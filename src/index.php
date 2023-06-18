<?php

declare(strict_types=1);
echo '<pre>';
$path_to_file = __DIR__ . '/downloads/';
$files = [
    // 'ABC-3xx.csv',
    // 'ABC-4xx.csv',
    'ABC-8xx.csv',
    'DEF-9xx.csv',
];

foreach ($files as $file) {
    if (!is_file($path_to_file . $file)) {
        $ch = curl_init('https://opendata.digital.gov.ru/downloads/' . $file . '?1662393366550');
        $fp = fopen($path_to_file . $file, 'wb');

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $res = curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        if ($res) {
            echo 'Файл успешно скачан и сохранен.';
            echo PHP_EOL . PHP_EOL;
        } else {
            die('Произошла ошибка при скачивании файла.');
        }
    }
}

$start = microtime(true);

$result_array = [];
$r = 0;

foreach ($files as $file) {
    $handle = fopen($path_to_file . $file, "r");
    if ($handle !== false) {
        $first = true;
        while (($data = fgetcsv($handle, null, ";")) !== false) {
            if ($first) {
                $first = false;
                continue;
            }
            $operator_name = $data[4];
            $code = $data[0];
            $code_len = strlen($data[0]);
            $diapason_start = $data[1];
            $capacity = $data[3];
            $capacity_len = strlen($capacity);
            $diapason_start = (string) $diapason_start;
            $common_digits = $code . substr($diapason_start, 0, -$capacity_len);

            $digit = 0;
            $digits_array = [];
            for ($i = (int) $capacity_len; $i > 0; ) {
                $i--;
                $divider = pow(10, $i);
                $multiplicity = $capacity % $divider;
                $iter_digit = floor($capacity / $divider) % 10;

                $digit = $digit === 0 ? $common_digits : end($digits_array) + 1;
                $start_digit = ($diapason_start[strlen((string) $digit) - $code_len]) ?? false;
                for ($k = 0; $k < $iter_digit; $k++) {
                    $digits_array[] = $digit . ($k + (int) $start_digit);
                }

                if ($multiplicity === 0) {
                    break;
                }
            }

            if (!($result_array[$operator_name] ?? false)) {
                $result_array[$operator_name] = [];
            }

            $result_array[$operator_name] = array_merge($result_array[$operator_name], $digits_array);
            $r++;
        }
        fclose($handle);
    } else {
        die('Не удалось прочитать файл!');
    }
}

$diff = sprintf('%.6f sec.', microtime(true) - $start);
echo "Общее количество масок: $r";
echo PHP_EOL . PHP_EOL;
echo "Время выполнения: $diff";
echo PHP_EOL . PHP_EOL;
echo 'Затраченая память: ' . round(memory_get_usage(true) / 1024, 2) . " kilobytes";
echo PHP_EOL . PHP_EOL;

var_dump($result_array);