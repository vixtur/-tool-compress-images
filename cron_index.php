<?php

require 'vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$mysqli = new mysqli($_ENV["DB_HOST"], $_ENV["DB_USER"], $_ENV["DB_PASS"], $_ENV["DB_BASE"]);

define('ROOT', $_ENV["IMAGES_LOCATION"] ); // путь до хостов
$count = 0;


function info($count){
    if($count % 10000 == 0)
        return "\n";
    if($count % 100 == 0)
        return ".";
    return "";
}

function recursive($dir) {

    $odir = opendir($dir);

    global $count;
    global $mysqli;

    while (($file = readdir($odir)) !== FALSE) {

        if (
          $file == '.' ||
          $file == '..'
        ) {

            continue;

        } else {

            if (
              is_file($dir.DIRECTORY_SEPARATOR.$file) &&
              (
                stripos($file,'.jpg')  ||
                stripos($file,'.jpeg') ||
                stripos($file,'.png')  ||
                stripos($file,'.svg')  ||
                stripos($file,'.gif')
              )
            ) {

                $count++;
                echo info($count);

                $url  = str_replace(ROOT,'',$dir.DIRECTORY_SEPARATOR.$file);
                $size_temp = filesize ( ROOT.$url );
                $sql  = 'INSERT INTO ' .
                  '`compression` ' .
                  '(`url`, `size_temp`) ' .
                  'VALUES ' .
                  "('$url', '$size_temp') " .
                  'ON DUPLICATE KEY ' .
                  "UPDATE `size_temp`='$size_temp';";

                $mysqli->query($sql);

            }

        }

        if (is_dir($dir.DIRECTORY_SEPARATOR.$file)) {

            recursive($dir.DIRECTORY_SEPARATOR.$file);

        }

    }

    closedir($odir);

}

recursive(ROOT);

?>
