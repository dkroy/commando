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
	
	$db_version = Functions::get_db_version();
	
	Header::set_title("Commando.io");
	Header::render();
	
	Navigation::render();
?>

 	<?php if($db_version !== Version::db): ?>
		<div class="alert alert-error fade in" style="position: fixed; top: 0px; right: 0px; left: 0px; z-index: 99999;">
			<a class="close" data-dismiss="alert">&times;</a>
			<h4 style="line-height: 25px;">Warning! The MySQL schema version running is <u>out of date</u>. Please run <a href="<?php echo Links::render("db-upgrade") ?>">db-upgrade</a> to merge in the latest changes.</h4>
		</div>
	<?php endif; ?>
 	 
    <div class="container">

      <div class="hero-unit">
		<h2 class="headline">Dev-Ops <strong>Evolved</strong>.</h2>
		<img src="/img/main-header.png" alt="Commando.io" />
      </div>

      <div class="row">
       	<div class="span12">
        	<div class="well" style="padding-left: 50px;">
        		<h1 style="margin-bottom: 10px;">Contacts</h1>
	          	<div style="float: left;">
	          		<p><a href="http://commando.io">http://commando.io</a></p>
			  		<p><a href="mailto:commando@nodesocket.com">commando@nodesocket.com</a></p>
			  		<p style="margin-bottom: 6px;"><a href="https://github.com/nodesocket/commando">https://github.com/nodesocket/commando</a></p>
	          	</div>
	          	<div style="float: right;">
	          		<a href="https://twitter.com/commando_io" class="twitter-follow-button" data-show-count="true" data-size="large" data-show-screen-name="false">Follow @commando_io</a>
			  		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	          	</div>
	          	<div class="clear"></div>
        	</div>
        </div>
      </div>
      
      <div class="row">
        <div class="span3">
        	<div class="well">
        		<h1 style="margin-bottom: 10px;"><a href="<?php echo Links::render("servers") ?>">Servers</a></h1>
          		<p>Servers can be physical hardware, or virtualized/cloud instances.</p>
          		<p><a class="btn btn-primary btn-large" href="<?php echo Links::render("servers") ?>">Servers &raquo;</a></p>
        	</div>
        </div>
        <div class="span3">
        	<div class="well">
          		<h1 style="margin-bottom: 10px;"><a href="<?php echo Links::render("groups") ?>">Groups</a></h1>
          		<p>Groups are a way to organize servers into collections. You may choose to create groups based on server role or location.</p>
          		<p><a class="btn btn-primary btn-large" href="<?php echo Links::render("groups") ?>">Groups &raquo;</a></p>
          </div>
        </div>
		<div class="span3">
        	<div class="well">
        		<h1 style="margin-bottom: 10px;"><a href="<?php echo Links::render("recipes") ?>">Recipes</a></h1>
         		<p>Recipes are containers of commands that are fully versioned. Recipes can be written in pure shell, bash, perl, python, or node.js.</p>
          		<p><a class="btn btn-primary btn-large" href="<?php echo Links::render("recipes") ?>">Recipes &raquo;</a></p>
        	</div>
        </div>
        <div class="span3">
        	<div class="well">
        		<h1 style="margin-bottom: 10px;"><a href="<?php echo Links::render("files") ?>">Files</a></h1>
         		<p>Files is a centralized place to store text or binary files and then SCP transfer them to groups of servers via recipes.</p>
          		<p><a class="btn btn-primary btn-large" href="<?php echo Links::render("files") ?>">Files &raquo;</a></p>
        	</div>
        </div>
      </div>
<?php
	Footer::render(array("index"));
?>