<?php

if ( ! function_exists('versioned_asset')) {
    /**
     * Returns file with version query string on the end that updates every modification
     *
     * @param $file
     * @return string
     */
    function versioned_asset($file)
    {
        if (file_exists(public_path($file))) {
            return asset($file) . '?v=' . filemtime(public_path($file));
        }

        return asset($file);
    }
}


if ( ! function_exists('concat')) {
    /**
     * Concatenate strings together
     *
     * @return string
     */
    function concat()
    {
        $args = func_get_args();

        if ( ! empty($args)) {
            foreach ($args as $key => $arg) {
                if (is_object($args) || is_array($arg)) {
                    unset($args[$key]);
                }
            }
        }

        return implode(' ', $args);
    }
}

if ( ! function_exists('concat_ws')) {
    /**
     * Concatenate strings with specified separator as first argument
     *
     * @return string
     */
    function concat_ws()
    {
        $args = func_get_args();

        $separator = (isset($args[0])) ? $args[0] : ' ';
        unset($args[0]);

        if ( ! empty($args)) {
            foreach ($args as $key => $arg) {
                if (is_object($args) || is_array($arg)) {
                    unset($args[$key]);
                }
            }
        }

        return implode($separator, $args);
    }
}

if ( ! function_exists('generate_uuid')) {
    /**
     * Generate a valid RFC 4122 universally unique identifier. Supporting
     * version 1, 3, 4 and 5,
     *
     * @param int    $version The version of Uuid to generate. Accepts 1-5.
     * @param string $name
     * @return string
     */
    function generate_uuid($version = 1, $name = 'laravel')
    {
        switch ($version) {
            case 1:
                return \Ramsey\Uuid\Uuid::uuid1()->toString();
                break;
            case 3:
                return \Ramsey\Uuid\Uuid::uuid3(\Ramsey\Uuid\Uuid::NAMESPACE_DNS, $name)->toString();
                break;
            case 4:
                return \Ramsey\Uuid\Uuid::uuid4()->toString();
                break;
            case 5:
                return \Ramsey\Uuid\Uuid::uuid5(\Ramsey\Uuid\Uuid::NAMESPACE_DNS, $name)->toString();
                break;
            default:
                return \Ramsey\Uuid\Uuid::uuid1()->toString();
                break;
        }
    }
}
if ( ! function_exists('var_d')) {
    /**
     * var_dump(), but pretty printed like dd().
     *
     * @param  mixed
     * @return void
     */
    function var_d()
    {
        array_map(function ($x) {
            (new Illuminate\Support\Debug\Dumper)->dump($x);
        }, func_get_args());
    }
}


if ( ! function_exists('database_dump')) {
    /**
     * Dump a backup of the specified db/tables
     *
     * @param string $tables      The tables to include array - leave blank for all
     * @param array  $fileOptions The backup file options array (path, name, ext)
     * @param array  $dbOptions   The database options array (db, user, pass, port)
     * @param null   $limit       The number of files to trim the directory to after backup (oldest first)
     * @return mixed|string Returns the path to the backup
     * @throws Exception
     */
    function database_dump(
        $tables = '',
        $fileOptions = [],
        $dbOptions = [],
        $limit = null
    ) {
        $db = ! empty($dbOptions['db']) ? $dbOptions['db'] : env('DB_DATABASE');
        $user = ! empty($dbOptions['user']) ? $dbOptions['user'] : env('DB_USERNAME');
        $pass = ! empty($dbOptions['pass']) ? $dbOptions['pass'] : env('DB_PASSWORD');
        $port = ! empty($dbOptions['port']) ? $dbOptions['port'] : env('DB_PORT');

        $path = ! empty($fileOptions['path']) ? preg_replace('/\/$/', '',
            $fileOptions['path']) : storage_path("backups");
        $ext = ! empty($fileOptions['ext']) ? $fileOptions['ext'] : 'sql';
        $name = ! empty($fileOptions['name']) ? $fileOptions['name'] : ($db . "_" . date('Ymd_H-i-s') . "." . $ext);

        if (is_array($tables)) {
            $tables = implode(" ", $tables);
        }

        if (empty($user) || empty($pass) || empty($db) || empty($path) || empty($port)) {
            throw new \Exception('DB credentials missing. Ensure you either pass correct strings or check .env has correct details');
        }

        $output = null;
        $return = null;
        exec("mysqldump --user=$user --password=$pass --port=$port $db $tables > $path/$name.$ext",
            $output, $return);

        if ($return) {
            throw new \Exception('There was an error creating the backup - check the mysql logs');
        }

        if (is_integer($limit)) {
            $files = glob("$path/*." . $ext);

            if (count($files) > $limit) {
                array_multisort(
                    array_map('filemtime', $files),
                    SORT_NUMERIC,
                    SORT_DESC,
                    $files
                );

                foreach ($files as $key => $file) {
                    if (($key + 1) > $limit) {
                        unlink($file);
                    }
                }
            }
        }
        return $path . '/' . $name . '.' . $ext;
    }
}

if ( ! function_exists('route_is')) {
    /**
     * Check the current route equals what is passed, including specific parameters
     *
     * @param      $string
     * @param null $parameters
     * @return bool
     */
    function route_is($string, $parameters = null)
    {
        $request = app('Illuminate\Http\Request');

        if ($request->route()) {
            // If the route is not the same as the one we want, return false
            if ($request->route()->getName() != $string) {
                return false;
            }

            // If the route is the same (cause we got past the first check) and we are not checking for parameters
            if ($parameters == null) {
                return true;
            }

            // If we got here then we are checking for parameters then do that...
            $routeParameters = $request->route()->parameters();
            foreach ($parameters as $key => $value) {
                // If the key is not in the route parameters then return false as it has not matched what we want
                if ( ! array_key_exists($key, $routeParameters)) {
                    return false;
                }

                // If the route parameter value is not the same as the one we want to match return false
                if ($routeParameters[$key] != $value) {
                    return false;
                }
            }

            // If all has passed our checks then return true
            return true;
        }

        return false;
    }
}

if ( ! function_exists('routeIs')) {
    /**
     * Alternative to route_is()
     *
     * @param  $string
     * @return bool
     */
    function routeIs($string, $parameters = null)
    {
        return route_is($string, $parameters);
    }
}

if ( ! function_exists('query_log_to_sql')) {
    /**
     * Transforms a database query log into raw sql for easy sql logging
     *
     * @param $queryLog
     * @return array
     */
    function query_log_to_sql($queryLog)
    {
        if (empty($queryLog)) {
            return [];
        }

        $queries = [];
        foreach ($queryLog as $queryData) {
            $queries[] = combine_query($queryData['query'], $queryData['bindings']);
        }

        return $queries;
    }
}
if ( ! function_exists('combine_query')) {
    /**
     * Merge database query with its bindings
     *
     * @param $query
     * @param $bindings
     * @return array
     */
    function combine_query($query, $bindings)
    {
        $sql = preg_replace_callback('/(:([0-9a-z_]+)|(\?))/', function($value) use (&$bindings) {
            $data = array_shift($bindings);

            if ( ! is_int($data)) {
                return "'$data'";
            }

            return $data;
        }, $query);

        return $sql;
    }
}