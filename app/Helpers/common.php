<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

if (!function_exists('uploadImage')) {

    /**
     * Convert to array postgres
     *
     * @param $file
     * @param string $directory
     * @return bool
     */
    function uploadImage($file, $directory = 'files')
    {
        $storage = Storage::disk('local');
        $rdate = '/public/'. $directory . date("/Y/m/d", time());
        $nameDirectory = '/'. $directory . date("/Y/m/d", time());
        $directory =  $rdate;
        $name = md5(uniqid()) . '.' . $file->getClientOriginalExtension();
        if (config('app.env') != 'local') {
            uploadImageS3($nameDirectory . '/' . $name, $file);
        } else {
            $storage->putFileAs($directory, $file, $name);
            $storage->setVisibility($rdate . '/' . $name, true);
        }

        return $nameDirectory . '/' . $name;
    }
}

if (!function_exists('uploadImageS3')) {

    /**
     * Convert to array postgres
     *
     * @param $directory
     * @param $file
     * @param string $fileVisibility
     * @return bool
     */
    function uploadImageS3($directory, $file)
    {
        try {
            $s3Client = Storage::disk('s3');
            $s3Client->put($directory, file_get_contents($file));
        } catch (\Exception $exception) {
            Log::debug($exception);
        }
    }
}

if (!function_exists('random_str_az')) {
    function random_str_az($length) {
        $chars = 'abcdefghjkmnpqrstuvwxyz';
        $str = '';
        for($i = 0; $i < $length; $i++)
        {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }
}

if (!function_exists('random_str_number')) {
    function random_str_number($length) {
        $chars = '123456789';
        $str = '';
        for($i = 0; $i < $length; $i++)
        {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }
}

if (!function_exists('random_str')) {
    function random_str($length) {
        $chars = '123456789abcdefghjkmnpqrstuvwxyz';
        $str = '';
        for($i = 0; $i < $length; $i++)
        {
            $str .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $str;
    }
}

if (!function_exists('escape_like')) {
    function escape_like($value, $char = '\\')
    {
        return str_replace(
            [$char, '%', '_'],
            [$char.$char, $char.'%', $char.'_'],
            $value
        );
    }
}

if (!function_exists('getDateStringRandom')) {

    /**
     * Convert to array postgres
     *
     * @param $file
     * @param string $directory
     * @return bool
     */
    function getDateStringRandom()
    {
        return date("_Y_m_d_H_i_s", time());
    }
}

if (!function_exists('isBetween')) {

    /**
     * Compare a range time is between other range time in DB
     *
     * @param $dbFrom, $dbTo, $start, $end
     * @return bool
     */
    function isBetween($dbFrom, $dbTo, $start, $end) {
        $dbFrom = strtotime($dbFrom);
        $dbTo = strtotime($dbTo);
        $start = strtotime($start);
        $end = strtotime($end);
    
        if(
            (($start < $dbFrom) and ($end > $dbFrom) and ($end <= $dbTo)) OR
            (($start >= $dbFrom) and ($start < $dbTo) and ($end <= $dbTo)) OR
            (($start <= $dbFrom) and ($end >= $dbTo)) OR
            (($start >= $dbFrom) and ($start < $dbTo) and ($end >= $dbTo))
        ){
            return true;
        }else{
            return false;
        }
    }
}

if (!function_exists('getArrayIndex')) {

    /**
     * Get array index with param value search
     *
     * @param $search
     * @param $array want to search
     * @return indexs of array
     */
    function getArrayIndex($search, $array)
    {
        $results = array_filter($array, function($value) use($search){
            return strpos($value, $search) !== false;
        });

        return array_keys($results);
    }
}
