<?php

/**
 * @author Danijel
 * @copyright 2013
 */

// Google Analytics autoload function

spl_autoload_register(function($className) {
	if($className[0] == '\\') {
		$className = substr($className, 1);
	}
	
	// Leave if class should not be handled by this autoloader
	if(strpos($className, 'UnitedPrototype\\GoogleAnalytics') !== 0) return;
	
	$classPath = strtr(substr($className, strlen('UnitedPrototype')), '\\', '/') . '.php';
	
	require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.third.party/' . $classPath);
});

?>