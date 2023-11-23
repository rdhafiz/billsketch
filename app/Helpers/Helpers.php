<?php

namespace App\Helpers;

class Helpers
{
    /**
     * @param $file
     * @return string
     */
    public static function fileUpload($file): string
    {
        $name = time() . str_replace(' ', '-', $file->getClientOriginalName());
        $file->move(storage_path('/app/public/uploads'), $name);
        return $name;
    }
}
