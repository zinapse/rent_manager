<?php

use Illuminate\Support\Facades\Log;

class MissingRequiredException extends Exception {

    public function __construct(string $variableName) {
        $location = __DIR__ . PATH_SEPARATOR . __FILE__ . __LINE__;
        $error = "'$location' - $variableName not found.";
        Log::error($error);
    }

}