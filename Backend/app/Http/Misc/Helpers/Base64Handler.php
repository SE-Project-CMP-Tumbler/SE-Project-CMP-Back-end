<?php

/**
 *  external resource from https://github.com/SiliconArena/alphamart-backend
*/

namespace App\Http\Misc\Helpers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Base64Handler
{

    public static function base64Validation($base64data, $allowedMime, $maxFileSize)
    {
        // check if the data send has base64 in it
        if (strpos($base64data, ';base64') !== false) {
            list(, $base64data) = explode(';', $base64data);
            list(, $base64data) = explode(',', $base64data);
        }

        // strict mode filters for non-base64 alphabet characters
        if (base64_decode($base64data, true) === false) {
            return false;
        }

        $binaryData = base64_decode($base64data);

        // decoding and then reeconding should not change the data
        if (base64_encode($binaryData) !== $base64data) {
            return false;
        }

        // temporarily store the decoded data on the filesystem to be able to pass it to the fileAdder
        $tmpFileName = tempnam(sys_get_temp_dir(), 'medialibrary');
        file_put_contents($tmpFileName, $binaryData);
        $tmpFile = new File($tmpFileName);

        // Check the MimeTypes and size
        $validation = Validator::make(
            ['file' => $tmpFile],
            [
                'file' => 'file',
                'file' => 'mimes:' . implode(',', $allowedMime),
                'file' => 'max:' . $maxFileSize,
            ],
            [
                'file.mimes' => 'unsupported type',
                'file.max' => 'bad file size'
            ]
        );

        if (!$validation->fails()) {
            return $tmpFile;
        } else {
            return false;
        }
    }

    public static function getMimeType($encoded_str)
    {
        return explode(';', explode(':', $encoded_str)[1])[0];
    }

    public static function getExtension($encoded_str)
    {
        return explode(';', explode('/', $encoded_str)[1])[0];
    }

    public static function storeFile($encoded_file_str, $driver)
    {
        $extension = self::getExtension($encoded_file_str);
        $file_name = Str::random(40) . '.' . $extension;
        $file = base64_decode(substr($encoded_file_str, strpos($encoded_file_str, ",") + 1));
        Storage::disk($driver)->put($file_name, $file);
        return $file_name;
    }

    public static function storeFileAs($encoded_file_str, $chosen_file_name, $driver)
    {
        $extension = self::getExtension($encoded_file_str);
        $file_name = $chosen_file_name . '.' . $extension;
        $file = base64_decode(substr($encoded_file_str, strpos($encoded_file_str, ",") + 1));
        Storage::disk($driver)->put($file_name, $file);
        return $file_name;
    }
}
