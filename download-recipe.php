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
	
	Functions::check_required_parameters(array($_GET['param1']));

	if(isset($_GET['param2']) && !empty($_GET['param2'])) {
		$recipe_version = $_GET['param2'];
	} else {
		$result = MySQLQueries::get_recipe_head_version($_GET['param1']);
		$head = MySQLConnection::fetch_object($result);
		
		////
		// Invalid recipe id
		////
		if(empty($head)) {
			Error::halt(404, 'not found', 'Recipe \'' . $_GET['param1'] . '\' does not exist.');
		} else {
			$recipe_version = $head->recipe_version;
		}
	}
	
	$result = MySQLQueries::get_recipe_by_version($_GET['param1'], $recipe_version);
	$recipe = MySQLConnection::fetch_object($result);
	
	////
	// Invalid recipe version
	////
	if(empty($recipe)) {
		Error::halt(404, 'not found', 'Recipe version \'' . $_GET['param1'] . '\' does not exist.');
	}
	
	$file_extension = null;
	switch($recipe->interpreter) {
		case 'shell':
			$file_extension = ".sh";
			break;
		case 'bash';
			$file_extension = ".sh";
			break;
		case 'perl';
			$file_extension = ".pl";
			break;
		case 'python';
			$file_extension = ".py";
			break;
		case 'node.js';
			$file_extension = ".js";
			break;
	}
		
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=\"" . $recipe->name . $file_extension . "\"");
    header("Content-Transfer-Encoding: quoted-printable");
    header("Content-Length: " . strlen($recipe->content));
	
	echo $recipe->content;
?> 