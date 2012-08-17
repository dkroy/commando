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
	
	($_SERVER['SCRIPT_NAME'] !== "/controller.php") ? require_once(__DIR__ . "/classes/Requires.php") : Links::$pretty = true;
	
	$diffs_to_execute = array();
	
	if($handle = opendir(__DIR__ . "/schema/diffs")) {
		while(false !== ($diff = readdir($handle))) {
			if($diff != "." && $diff != "..") {
				$diff = basename($diff, ".sql");
				
				if(version_compare($diff, Version::db, ">=")) {
					$diffs_to_execute[] = $diff;
				}
        	}
    	}
    	closedir($handle);
    }
    
	foreach($diffs_to_execute as $diff) {
		$SQL = str_replace("\n", "", file_get_contents(__DIR__ . "/schema/diffs/" . $diff . ".sql"));
		MySQLConnection::multi_query($SQL) or Error::db_halt(500, 'internal server error', 'Unable to execute request, SQL query error.', __FUNCTION__, MySQLConnection::error(), $SQL);
	}
	
	Functions::redirect("/");
?>