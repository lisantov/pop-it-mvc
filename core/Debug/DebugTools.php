<?php

namespace Debug;

class DebugTools
{
    public static function log($message) {
        echo '<pre>';
        print_r($message);
        echo '</pre>';
        die();
    }
}