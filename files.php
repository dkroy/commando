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
	
	Header::set_title("Commando.io - Files");
	Header::render();
	
	Navigation::render("files");
?>    
    <div class="container">
           
      <h1 class="header">Files</h1> 
      
	  <div class="row">
   	  	<div class="span12">
   	  		<div id="upload-box-container" class="well">
   	  			<div id="upload-box">
   	  				<div class="progress progress-striped active" style="display: none; margin-bottom: 10px;">
   	  					<div class="bar" style="width: 0%;"></div>
   	  				</div>
   	  				<h3>Drag a file here to upload…</h3>
   	  				<h5>Maximum file size is 20MB.</h5>
   	  			</div>
   	  		</div>
      	</div>
      </div>
      
      <div class="row">
   	 	<div class="span12">
      		<div class="well">
      			<div class="input-prepend" style="float: right">
					<span class="add-on">
						<i class="icon-search"></i>
					</span><input id="filter-files" type="text" class="span3 tip" rel="tooltip" data-placement="top" data-original-title="By id, name or type." maxlength="100" placeholder="Filter Files…" value="" disabled />
				</div>
				<div class="clear"></div>
      		</div>
      	</div>
      </div>
      
      <div class="row">
		<div class="span12">
			<div class="well">
				<div id="files-did-you-know" class="alert alert-info fade in" style="display: none;">
	  	  			<a class="close" data-dismiss="alert">&times;</a>
	  	  			<h4>Did you know?</h4>
	  	  			You may upload either <i><strong>text</strong></i> or <i><strong>binary</strong></i> files. Store <strong>configuration</strong> files here.
	  	  	  	</div>
	  	  	  	<div id="progress-container">
	  	  	  		<div class="progress progress-striped active">
						<div class="bar" style="width: 100%;"></div>
					</div>
	  	  	  	</div>
	      	  	<div id="table-container" style="display: none;"></div>
			</div>
	    </div>
	  </div>
<?php
	Footer::render(array("filedrop", "bootbox", "files"));
?>