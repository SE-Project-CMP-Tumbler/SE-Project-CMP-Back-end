<?php

/**
 *  external resource from https://github.com/SiliconArena/alphamart-backend
*/

namespace App\Http\Misc\Helpers;

use Illuminate\Support\Str;

class FileHandler
{
    public static function storeImg($image, $driver)
    {
        $image_name = Str::random(40) . '.' . $image->extension();
        $image->storeAs('/', $image_name, $driver);
        return $image_name;
    }

    public static function readCSV($filename = '', $delimiter = '|')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }
}
