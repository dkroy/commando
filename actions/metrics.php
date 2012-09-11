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
	
	//Get settings
	$result = MySQLQueries::get_settings();
	$row = MySQLConnection::fetch_object($result);
	$instance_key = null;
	
	if(isset($row->data)) {
		$row->data = json_decode($row->data);
		
		if(isset($row->data->instance_key) && !empty($row->data->instance_key)) {
			$instance_key = $row->data->instance_key;
		} else {
			$instance_key = Functions::generate_random(9) . uniqid() . Functions::generate_random(8);
			
			$data = array("instance_key" => $instance_key,
				  		  "default_ssh_username" => $row->data->default_ssh_username,
				  		  "default_ssh_port" => $row->data->default_ssh_port,
				  		  "default_interpreter" => $row->data->default_interpreter,
				  		  "timezone_offset" => $row->data->timezone_offset,
				  		  "timezone_daylight_savings" => $row->data->timezone_daylight_savings);
			
			MySQLQueries::edit_settings(json_encode((object)$data));
		}
	} else {
		$instance_key = Functions::generate_random(9) . uniqid() . Functions::generate_random(8);
		$data = array("instance_key" => $instance_key);	  
		MySQLQueries::edit_settings(json_encode((object)$data));
	}
	
	$servers = array();
	$result = MySQLQueries::get_servers();
	while($row = MySQLConnection::fetch_object($result)) {
		$servers[] = $row;
	}
	
	$payload = '{"event":"' . $instance_key . '","properties":{"token":"678f0669ff58d890eeb50633c91a633d","distinct_id":"' . $instance_key . '","ip":"' . Functions::get_remote_ip() . '","servers":"' . count($servers) . '","version":"' . Version::app . '","ip-address":"' . Functions::get_remote_ip() . '","mp_name_tag":"' . $instance_key . '","time":"' . time() . '"}}';
	$curl = new Curl();
	$curl->get_request("https://api.mixpanel.com/track/?data=" . base64_encode($payload));
	$curl->close();
	
	echo '{"instance_key":"' . $instance_key . '"}';
?>