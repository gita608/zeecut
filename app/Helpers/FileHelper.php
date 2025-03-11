<?php

use Illuminate\Http\UploadedFile;

if (!function_exists('uploadFile')) {
    function uploadFile(UploadedFile $file, $folder = 'uploads', $disk = 'public')
    {
        if ($file->isValid()) {
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            return $file->storeAs($folder, $filename, $disk);
        }
        return null;
    }
}
