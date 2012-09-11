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
	
	$file = null;
	MongoConnection::connect();
	MongoConnection::grid_fs();
	$results = MongoConnection::grid_fs_find(array("_id" => new MongoId($_GET['param1'])));
	
	foreach($results as $result) {
		$file = $result->file;
		$file['data'] = $result->getResource();
	}
	
	$content = null;
	if(strpos($file['type'], 'text') !== false) {
		while (!feof($file['data'])) {
			$content .= fread($file['data'], 8192);
		}
	}
	
	header("Content-Type: text/plain; charset=utf-8");
	echo $content;
?> 