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
 		private static $count = 0;
 	
 		private static function generate_token() {
 			$token = sha1(uniqid(rand(), true));
 			CSRF::$count++;
 			$_SESSION['security_token_' . CSRF::$count] = $token;
 			return $token;
 		}
 		
 		public static function generate_hidden_field() {
 			$token = CSRF::generate_token();
 			return '<input type="hidden" name="security_token_' . CSRF::$count . '" id="security_token_' . CSRF::$count . '" value="' . $token . '" />';
 		}
 		
 		public static function generate_get_parameter() {
 			$token = CSRF::generate_token();
 			return 'security_token_' . CSRF::$count . '=' . $token;
 		}
 		
 		public static function is_valid($count = 1, $method_get = false) {
		 	if($method_get) {
 				if(isset($_SESSION['security_token_' . $count]) && isset($_GET['security_token_' . $count]) && $_SESSION['security_token_' . $count] === $_GET['security_token_' . $count]) {
 					return true;
 				} else {
 					return false;
 				}
 			} else {
 				if(isset($_SESSION['security_token_' . $count]) && isset($_POST['security_token_' . $count]) && $_SESSION['security_token_'. $count] === $_POST['security_token_' . $count]) {
 					return true;
 				} else {
 					return false;
 				}
 			}
 		}
	}
?>