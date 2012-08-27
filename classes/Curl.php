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
	
	class Curl {
 		public $curl_object;
		
		public function __construct($username = "", $password = "", $timeout = 10) {
			$this->curl_object = curl_init();
			
			curl_setopt($this->curl_object, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($this->curl_object, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->curl_object, CURLOPT_SSL_VERIFYPEER, false);
			
			if(isset($_SERVER['HTTP_USER_AGENT'])) {
				curl_setopt($this->curl_object, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			}
			
			if(!empty($username) && !empty($password)) {
				curl_setopt($this->curl_object, CURLOPT_USERPWD, $username . ":" . $password);
				curl_setopt($this->curl_object, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			}
		}
		
		public function get_request($url) {
			curl_setopt($this->curl_object, CURLOPT_URL, $url);
			return curl_exec($this->curl_object);
		}
		
		public function post_request($url, $post_data = "") {			
			curl_setopt($this->curl_object, CURLOPT_URL, $url);
			curl_setopt($this->curl_object, CURLOPT_POST, true);
			curl_setopt($this->curl_object, CURLOPT_POSTFIELDS, $post_data);
			return curl_exec($this->curl_object);
		}
		
		public function put_request($url) {
			curl_setopt($this->curl_object, CURLOPT_URL, $url);
			curl_setopt($this->curl_object, CURLOPT_CUSTOMREQUEST, 'PUT');
			return curl_exec($this->curl_object);		
		}
		
		public function delete_request($url) {
			curl_setopt($this->curl_object, CURLOPT_URL, $url);
			curl_setopt($this->curl_object, CURLOPT_CUSTOMREQUEST, 'DELETE');
			return curl_exec($this->curl_object);
		}
		
		public function close() {
			curl_close($this->curl_object);
		}
	}
?>