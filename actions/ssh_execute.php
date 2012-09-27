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
	
	require_once(dirname(__DIR__) . "/classes/Requires.php");
	
	Functions::check_required_parameters(array($_POST['groups'], $_POST['recipe']));
	
	if(!CSRF::is_valid()) {
		Error::halt(400, 'bad request', 'Missing required security token.');
	}
	
	$result = MySQLQueries::get_recipe($_POST['recipe']);
	$recipe = MySQLConnection::fetch_object($result);
	
	if(empty($recipe)) {
		//Output error details
		Error::halt(400, 'bad request', 'The recipe \'' . $_POST['recipe'] . '\' does not exist.');
	}
	
	////
	// Check for includes
	////
	preg_match_all('/\{\{include:([a-zA-Z0-9_]{25})\}\}/i', $recipe->content, $include_matches, PREG_PATTERN_ORDER);
	
	if(isset($include_matches[1])) {
		$include_matches = array_unique($include_matches[1]);
	}

	////
	// There are includes
	////
	if(isset($include_matches) && count($include_matches) > 0) {
		$result = MySQLQueries::get_recipes($include_matches);
		
		$recipes = array();
		while($row = MySQLConnection::fetch_object($result)) {
			$recipes[$row->id] = $row;	
		}
		
		////
		// Confirm that all includes exist
		////
		foreach($include_matches as $include) {
			if(!isset($recipes[$include])) {
				//Output error details
				Error::halt(400, 'bad request', 'The included recipe \'' . $include . '\' does not exist.');
			}
		}
		
		foreach($recipes as $row) {
			////
			// Make sure the included recipe uses the same interpreter
			////
			if($row->interpreter === $recipe->interpreter) {
				$recipe->content = str_replace('{{include:' . $row->id . '}}', $row->content, $recipe->content);
				
				////
				// Check for includes in the include (not currently supported)
				////
				preg_match_all('/\{\{include:([a-zA-Z0-9_]{25})\}\}/i', $recipe->content, $include_matches, PREG_PATTERN_ORDER);
				
				if(isset($include_matches[1])) {
					$include_matches = array_unique($include_matches[1]);
				}
			
				if(isset($include_matches) && count($include_matches) > 0) {
					//Output error details
					Error::halt(409, 'conflict', 'Multi-level includes are not currently supported, i.e. an included recipe cannot include other recipes itself. Want this feature? <a href="mailto:commando@nodesocket.com">Send us</a> an e-mail.');	
				}
			} else {
				//Output error details
				Error::halt(409, 'conflict', 'Included recipe \'' . $row->id . '\' uses a different interpreter than this recipe.');	
			}
		}
	}
	
	MongoConnection::connect();
	MongoConnection::select_collection("executions");
	
	//Default group handling
	if(count($_POST['groups']) === 1 && empty($_POST['groups'][0])) {
		$_POST['groups'] = array();
	}
	
	$servers = array();
	$results = MySQLQueries::get_servers_by_groups($_POST['groups']);
	while($row = MySQLConnection::fetch_object($results)) {
		$servers[] = $row;
	}
	
	switch($recipe->interpreter) {
		case "shell":
			$command = $recipe->content;
			break;
		case "bash":
			$recipe->content = str_replace("\r\n", "\n", str_replace("'", "\'", $recipe->content));
			$command = 'echo $\'' . $recipe->content . '\' | bash';
			break;
		case "perl":
			$recipe->content = str_replace("\r\n", "\n", str_replace("'", "\'", $recipe->content));
			$command = 'echo $\'' . $recipe->content . '\' | perl';
			break;
		case "python":
			$recipe->content = str_replace("\r\n", "\n", str_replace("'", "\'", $recipe->content));
			$command = 'echo $\'' . $recipe->content . '\' | python';
			break;
		case "node.js":
			$recipe->content = str_replace("\r\n", "\n", str_replace("'", "\'", $recipe->content));
			$command = 'echo $\'' . $recipe->content . '\' | node';
			break;
	}

	$returned_results = array();
	foreach($servers as $server) {
		try {
			$ssh = new SSH($server->address, $server->ssh_port, THROW_ERROR);
		} catch(Exception $ex) {
			$ex = json_decode($ex->getMessage());
			$returned_results[] = array("server" => $server->id, "server_label" => $server->label, "stream" => "error", "result" => $ex->error->message);
			continue;
		}
		
		try {
			$ssh->auth($server->ssh_username, SSH_PUBLIC_KEY_PATH, SSH_PRIVATE_KEY_PATH, THROW_ERROR);
		} catch(Exception $ex) {
			$ex = json_decode($ex->getMessage());
			$returned_results[] = array("server" => $server->id, "server_label" => $server->label, "stream" => "error", "result" => $ex->error->message);
			continue;
		}
		
		$result = $ssh->execute($command);
		$returned_results[] = array("server" => $server->id, "server_label" => $server->label, "stream" => $result->stream, "result" => $result->result);
	}

	MongoConnection::insert(Functions::build_execution_history_object($_POST['notes'], $_POST['groups'], $recipe, $servers, $returned_results));
	MongoConnection::close();
	
	echo json_encode($returned_results);
?>