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
	
	require_once(__DIR__ . "/markdown/markdown.php");
	
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
	
	Header::set_title("Commando.io - View Execution History");
	Header::render(array("code-pretty"));
	
	Navigation::render("execute");
?> 
    <div class="container main-container">
           
      <div class="row">
      	<div class="span12">
      		<h1 class="header" style="float: left;"><?php echo $execution_history['executed'] ?></h1> 
     	 
     	 	<div style="float: right;">
     	 		 <a href="<?php echo Links::render("execution-history") ?>" class="btn btn-large"><?php echo $execution_history['_id'] ?></a>
     	 	</div>
     	 </div>
      </div>
      
	  <div class="row">
    	<div class="span12">
    		<div class="well">
    			<?php if(isset($execution_history['notes']) && !empty($execution_history['notes'])): ?>
    				<div class="alert alert-grey fade in">
				 		<a class="close" data-dismiss="alert">&times;</a>
				 		<?php echo Markdown($execution_history['notes']) ?>
				 		<div class="clear"></div>
					</div>
				<?php endif; ?>
				<div class="navbar">
	            	<div class="navbar-inner">
	              		<div class="container">
	                		<a class="brand servers-popover" data-content="
			      						<?php
			      							$out = '';
			      							
			      							foreach($execution_history['servers'] as $server) {
			      								$out .= $server['label'] . ', ';
			      							}
			      							
			      							echo rtrim($out, ', ');
			      					 	?>
			      					 " data-title="<?php if(!empty($execution_history['servers'])): ?>Servers<?php endif; ?>"><?php echo count($execution_history['servers']) ?> <?php echo (count($execution_history['servers']) < 2) ? 'Server' : 'Servers' ?></a>
	               			<ul class="nav">
			                	<li class="divider-vertical"></li>
			                	<li>
			                		<a style="cursor: default;"><?php echo $execution_history['recipe']['name'] ?></a>
			                	</li>
			                	<li class="divider-vertical"></li>
			                	<li>
			                		<a style="cursor: default;"><?php echo ucfirst($execution_history['recipe']['interpreter']) ?></a>
			                	</li>
	                		</ul>
	              		</div>
            		</div>
          		</div>
				<div id="contents-loading" class="progress progress-striped active">
					<div class="bar" style="width: 100%;"></div>
				</div>
				<div id="execution-history-contents" style="display: none;">
					<div style="float: right; position: relative; top: 0px; right: 0px;">
						<a href="<?php echo Links::render("download-execution-history", array($execution_history['_id']->__toString())) ?>" class="btn btn-medium"><i class="icon-download-alt"></i> Download JSON</a> <a href="<?php echo Links::render("view-execution-history-raw", array($execution_history['_id']->__toString())) ?>" class="btn btn-medium"><i class="icon-align-left"></i> Raw</a>
					</div>
					<pre class="prettyprint linenums"><?php echo htmlentities(@json_encode($execution_history, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) ?></pre>
				</div>
    		</div>
		</div>
	  </div>
<?php
	Footer::render(array("code-pretty", "zclip", "view-execution-history"));
?>