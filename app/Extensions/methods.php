<?php

use Illuminate\Support\Str;

if (!function_exists('in_environment')) {
    function in_environment () {
        $envs = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();
        $runningIn = config('app.env');

        // check if exactly as it is
        if (in_array($runningIn, $envs)) {
            return true;
        }

        // check if starts with i.e; prod as production
        foreach ( $envs as $env ) {
            if (Str::startsWith(strtolower($runningIn), strtolower($env))) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('is_production')) {
    function is_production () {
        return in_environment('prod');
    }
}

if (!function_exists('is_local')) {
    function is_local () {
        return in_environment('local');
    }
}

if (!function_exists('log_db_queries')) {
    function log_db_queries () {
        return config('app.enable_query_log');
    }
}

if (!function_exists('custom_logger')) {
    function custom_logger (array $data, $level = 'debug', $channels = '') {
        $defaultChannel = env('LOG_CHANNEL', 'single');
        if (empty($channels)) {
            $channels = $defaultChannel;
        }

        // if channels passed as array, we'll also push to the default channel
        if (!is_array($channels)) {
            $channels = [ $channels ];
        } else {
            // if default doesn't exist, push default.
            if (!in_array($defaultChannel, $channels)) {
                $channels[] = $defaultChannel;
            }
        }

        app('log')->stack($channels)->$level(json_encode($data));
    }
}
