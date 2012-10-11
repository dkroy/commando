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

	//Get execution history record
	$execution_history = null;
	MongoConnection::connect();
	MongoConnection::select_collection("executions");
	$results = MongoConnection::find(array("_id" => new MongoId($_GET['param1'])));
	MongoConnection::close();
		
	foreach($results as $result) {
		$result['executed'] = date(DATE_FORMAT, ($result['executed']->sec + Functions::timezone_offset_in_seconds()));
		$execution_history = $result;
	}
	
	if(empty($execution_history)) {
		Error::halt(404, 'not found', 'Execution history ID \'' . $_GET['param1'] . '\' does not exist.');
	}
	
	$execution_history_json = @json_encode($execution_history, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		
    header("Content-Type: application/json");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=\"" . $execution_history['_id'] . ".json\"");
    header("Content-Transfer-Encoding: quoted-printable");
    header("Content-Length: " . strlen($execution_history_json));
	
	echo $execution_history_json;
?> 