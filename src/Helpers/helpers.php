<?php 

if (! function_exists('versioned_asset')) {
	/**
	 * Returns file with version querystring on the end that updates every modification
	 * @param  [type] $file [description]
	 * @return [type]       [description]
	 */
	function versioned_asset($file)
	{
		return asset($file) . '?v=' . filemtime(public_path($file));
	}
}