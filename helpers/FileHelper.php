<?php


namespace greenweb\addon\helpers;


class FileHelper
{
    public static function findFileInPaths($filename, array $paths)
    {
        foreach ($paths as $path) {
            $path = self::standard($path);
            if (is_file($path . DIRECTORY_SEPARATOR . $filename)) {
                return $path;
            }
        }
        return false;
    }

    private static function standard($path, $preSlash = false)
    {
        $path = rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
        return $preSlash ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : $path;
    }
}