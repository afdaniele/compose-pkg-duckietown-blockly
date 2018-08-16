<?php
# @Author: Andrea F. Daniele <afdaniele>
# @Date:   Sunday, December 31st 2017
# @Email:  afdaniele@ttic.edu
# @Last modified by:   afdaniele
# @Last modified time: Monday, January 15th 2018



namespace system\packages\duckietown_blockly;

require_once $GLOBALS['__SYSTEM__DIR__'].'classes/Core.php';
use \system\classes\Core as Core;

require_once $GLOBALS['__SYSTEM__DIR__'].'classes/Configuration.php';
use \system\classes\Configuration as Configuration;

require_once $GLOBALS['__SYSTEM__DIR__'].'classes/Utils.php';
use \system\classes\Utils as Utils;


/**
*   Module for managing the Blockly plugin for \compose\-Duckietown
*/
class DuckietownBlockly{

	private static $initialized = false;


	// disable the constructor
	private function __construct() {}

    /** Initializes the module.
     *
     *	@retval array
	 *		a status array of the form
	 *	<pre><code class="php">[
	 *		"success" => boolean, 	// whether the function succeded
	 *		"data" => mixed 		// error message or NULL
	 *	]</code></pre>
	 *		where, the `success` field indicates whether the function succeded.
	 *		The `data` field contains errors when `success` is `FALSE`.
     */
	public static function init(){
		if( !self::$initialized ){
			// link blocks_compressed.js from dist to data/public/blockly/
			$link_blocks_compressed = __DIR__.'/data/public/blockly/blocks_compressed.js';
			$dist_blocks_compressed = __DIR__.'/blockly_data/dist/blocks_compressed.js';
			if( !file_exists($link_blocks_compressed) || !is_link($link_blocks_compressed) ){
				unlink($link_blocks_compressed);
				link($dist_blocks_compressed, $link_blocks_compressed);
			}
			// link python_compressed.js from dist to data/public/blockly/
			$link_python_compressed = __DIR__.'/data/public/blockly/python_compressed.js';
			$dist_python_compressed = __DIR__.'/blockly_data/dist/python_compressed.js';
			if( !file_exists($link_python_compressed) || !is_link($link_python_compressed) ){
				unlink($link_python_compressed);
				link($dist_python_compressed, $link_python_compressed);
			}
			//
			return array( 'success' => true, 'data' => null );
		}else{
			return array( 'success' => true, 'data' => "Module already initialized!" );
		}
	}//init


    /** Safely terminates the module.
     *
     *	@retval array
	 *		a status array of the form
	 *	<pre><code class="php">[
	 *		"success" => boolean, 	// whether the function succeded
	 *		"data" => mixed 		// error message or NULL
	 *	]</code></pre>
	 *		where, the `success` field indicates whether the function succeded.
	 *		The `data` field contains errors when `success` is `FALSE`.
     */
	public static function close(){
        // do stuff
		return array( 'success' => true, 'data' => null );
	}//close

}//DuckietownBlockly

?>
