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
	
 	class CSRF {
 		public static function generate_token() {
 			$token = sha1(uniqid(rand(), true));
 			$_SESSION['security_token'] = $token;
 			return $token;
 		}
 		
 		public static function generate_hidden_field() {
 			return '<input type="hidden" name="security_token" id="security_token" value="' . CSRF::generate_token() . '" />';
 		}
 		
 		public static function is_valid($method_get = false) {
 			if($method_get) {
 				 if(isset($_SESSION['security_token']) && isset($_GET['security_token']) && $_SESSION['security_token'] === $_GET['security_token']) {
 					return true;
 				} else {
 					return false;
 				}
 			} else {
 				if(isset($_SESSION['security_token']) && isset($_POST['security_token']) && $_SESSION['security_token'] === $_POST['security_token']) {
 					return true;
 				} else {
 					return false;
 				}
 			}
 		}
	}
?>