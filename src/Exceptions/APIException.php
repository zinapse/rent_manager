<?php

use Illuminate\Support\Facades\Log;

class APIException extends Exception {

    public function __construct(string $message, bool $debug = false) {
        $location = __DIR__ . PATH_SEPARATOR . __FILE__ . __LINE__;
        $error = "'$location' - $message";

        // Log the error
        ($debug ? Log::debug($error) : Log::error($error));
    }

}