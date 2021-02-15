<?php

$input_path = $argv[2];
$output_path = $argv[3];

if (strcmp("encode", $argv[1]) == 0) {
    encode_advanced_rle($input_path, $output_path);
} elseif (strcmp("decode", $argv[1]) == 0) {
    decode_advanced_rle($input_path, $output_path);
}

function encode_advanced_rle(string $input_path, string $output_path) {
    $input_path = file_get_contents($input_path);
    $output = fopen($output_path, 'w');

    $write = '';
    $zero = '0';

    $i = 0;
    $j = 1;
    $k = 1;
    $m = 0;

    if (strlen($input_path) == 1) {
        $read = dechex(0);
        if (strlen($read) == 1) {
            $read = $zero.$read;
        }
        $write = hex2bin($read);
        fwrite($output, $write);
        $read = dechex(1);
        if (strlen($read) == 1) {
            $read = $zero.$read;
        }
        $write = hex2bin($read);
        fwrite($output, $write);
        $read = bin2hex($input_path[$i]);
        if (strlen($read) == 1) {
            $read = $zero.$read;
        }
        $write = hex2bin($read);
        fwrite($output, $write);
    } else {

    $var = decbin(0);
    $input_path[strlen($input_path)] = $var;
    $input_path[strlen($input_path)] = $var;

        while ($i != strlen($input_path) -2) {
            if (strcmp($input_path[$i],$input_path[$j]) == 0) {
                $k = 1;
                while (strcmp($input_path[$i],$input_path[$j]) == 0 && $k < 255) {
                    $i++;
                    $j++;
                    $k++;
                }
                $read = dechex($k);
                if (strlen($read) == 1) {
                    $read = $zero.$read;
                }
                $write = hex2bin($read);
                fwrite($output, $write);
                $read = bin2hex($input_path[$i]);
                if (strlen($read) == 1) {
                    $read = $zero.$read;
                }
                $write = hex2bin($read);
                fwrite($output, $write);
                $k = 1;
                $i++;

            } else {
                $k = 0;
                $l = $i;
                while (strcmp($input_path[$i], $input_path[$j]) != 0 && $i != strlen($input_path) - 2  && $k < 255) {
                    $i++;
                    $j++;
                    $k++;
                }
                $read = sprintf("%02d" ,dechex(0));
                if (strlen($read) == 1) {
                    $read = $zero.$read;
                }
                $write = hex2bin($read);
                fwrite($output, $write);
                $read = dechex($k);
                if (strlen($read) == 1) {
                    $read = $zero.$read;
                }
                $write = hex2bin($read);
                fwrite($output, $write);
                //echo $inputpath[0];
                while ($l <= $i - 1) {
                    $read = bin2hex($input_path[$l]);
                    if (strlen($read) == 1) {
                        $read = $zero.$read;
                    }
                    $write = hex2bin($read);
                    fwrite($output, $write);
                    $l++;
                }
                $k = 1;
                $i--;
                $j--;
                $i++;
            }
            $j++;
        }
    }
    fclose($output);
    
}

function decode_advanced_rle(string $input_path, string $output_path) {

    $input_path = file_get_contents($input_path);

    if (strlen($input_path) == 1 || strlen($input_path) == 3 && bin2hex($input_path[1]) != "01") {
        echo "KO";
        echo 1;
    } else {
    
    $output = fopen($output_path, 'w');

    $write = '';
    $zero = '0';

    $i = 0;
    $j = 1;
    $k = 1;
    $m = 0;

    $var = decbin(0);
    $input_path[strlen($input_path)] = $var;
    $input_path[strlen($input_path)] = $var;

    while ($i != strlen($input_path) -2) {
        $m = 0;

        if (bin2hex($input_path[$i]) == "00") {
            $i++;
  
            $var =  bin2hex($input_path[$i]);   
            $len = hexdec($var);
            $i++;
            while ( $m < $len && $i != strlen($input_path) - 2) {
                $read = bin2hex($input_path[$i]); 
                $write = hex2bin($read);
                fwrite($output, $write);
                $i++;
                $m++;
            }
        } elseif (bin2hex($input_path[$i]) != "00" ) {
            $m = 0;
            $var =  bin2hex($input_path[$i]);   
            $len = hexdec($var);
            $i++;
            $torepeat = bin2hex($input_path[$i]);
            $m++;
            while ( $m <= $len && $i != strlen($input_path) - 2) {
                $read = bin2hex($torepeat); 
                $write = hex2bin($read);
                if (strlen($write) == 1) {
                    $write = $zero.$write;
                }
                $result =  hex2bin($write);
                fwrite($output, $result);
                $m++;
            }
            $i++;
        }

    }
}   
    echo "OK";
    echo "\n";
    fclose($output);
}
?>

