<?php
	/*
	# Copyright 2012 NodeSocket, LLC
	#
	# Licensed under the Apache License, Version 2.0 (the "License");
	# you may not use this file except in compliance with the License.
	# You may obtain a copy of the License at
	#
	# http://www.apache.org/licenses/LICENSE-2.0
	#
	# Unless required by applicable law or agreed to in writing, software
	# distributed under the License is distributed on an "AS IS" BASIS,
	# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	# See the License for the specific language governing permissions and
	# limitations under the License.
	*/
	
 	class Requires {
 		static function autoload() {
 			require_once(dirname(__DIR__) . "/classes/Prerequisites.php");
 			require_once(dirname(__DIR__) . "/timezone.php");
 			require_once(dirname(__DIR__) . "/defines.php");
 			require_once(dirname(__DIR__) . "/classes/Error.php");
 			require_once(dirname(__DIR__) . "/classes/Sessions.php");
 			
 			if(!file_exists(dirname(__DIR__) . "/app.config.php")) {
 				Error::halt(404, 'not found', 'File \'app.config.php\' does not exist. Did you run \'install.php\'?');
 			}
 			
 			require_once(dirname(__DIR__) . "/app.config.php");
 			
 			if(!file_exists(dirname(__DIR__) . "/classes/MySQLConfiguration.php")) {
				Error::halt(404, 'not found', 'File \'/classes/MySQLConfiguration.php\' does not exist.');	
			}
 			
 			if(!file_exists(dirname(__DIR__) . "/classes/MongoConfiguration.php")) {
				Error::halt(404, 'not found', 'File \'/classes/MongoConfiguration.php\' does not exist.');	
			}
			
 			spl_autoload_register(function($class_name) {
 				require_once(dirname(__DIR__) . "/classes/" . $class_name . ".php");
			});
 		}
 	}
	
	Requires::autoload();
?>