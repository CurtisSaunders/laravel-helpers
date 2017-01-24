<?php 

if (! function_exists('versioned_asset')) {
    /**
     * Returns file with version query string on the end that updates every modification
     *
     * @param $file
     * @return string
     */
	function versioned_asset($file)
	{
		if ( file_exists(public_path($file)) ) {
			return asset($file) . '?v=' . filemtime(public_path($file));
		}
		
		return asset($file);
	}
}



if (! function_exists('concat')) {
    /**
     * Concatenate strings together
     *
     * @return string
     */
    function concat()
    {
        $args = func_get_args();

        if (! empty($args)) {
            foreach($args as $key=>$arg) {
                if (is_object($args) || is_array($arg)) {
                    unset($args[$key]);
                }
            }
        }

        return implode(' ', $args);
    }
}

if (! function_exists('concat_ws')) {
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

        if (! empty($args)) {
            foreach($args as $key=>$arg) {
                if (is_object($args) || is_array($arg)) {
                    unset($args[$key]);
                }
            }
        }

        return implode($separator, $args);
    }
}

if (! function_exists('generate_uuid')) {
    /**
     * Generate a valid RFC 4122 universally unique identifier. Supporting
     * version 1, 3, 4 and 5,
     *
     * @param int $version The version of Uuid to generate. Accepts 1-5.
     * @param string $name
     * @return string
     */
    function generate_uuid($version = 1, $name = 'laravel')
    {
        switch($version) {
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
if ( ! function_exists('var_d'))
{
    /**
     * var_dump(), but pretty printed like dd().
     *
     * @param  mixed
     * @return void
     */
    function var_d()
    {
        array_map(function($x) { (new Illuminate\Support\Debug\Dumper)->dump($x); }, func_get_args());
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
