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