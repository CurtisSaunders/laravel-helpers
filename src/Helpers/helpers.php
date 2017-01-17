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