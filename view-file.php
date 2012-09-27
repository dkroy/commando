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
	
	$file = null;
	MongoConnection::connect();
	MongoConnection::grid_fs();
	$results = MongoConnection::grid_fs_find(array("_id" => new MongoId($_GET['param1'])));
	MongoConnection::close();
	
	foreach($results as $result) {
		$result->file['uploadDate'] = date(DATE_FORMAT, ($result->file['uploadDate']->sec + Functions::timezone_offset_in_seconds()));
		$file = $result->file;
		$file['data'] = $result->getResource();
	}
	
	if(empty($file)) {
		Error::halt(404, 'not found', 'File \'' . $_GET['param1'] . '\' does not exist.');
	}
	
	$data = null;
	if(strpos($file['type'], 'text') !== false) {
		while (!feof($file['data'])) {
			$data .= fread($file['data'], 8192);
		}
	}
	
	$file['data'] = $data;
	
	//Calculate Statistics
	$file['lines'] = (substr_count($file['data'], "\n") + 1);
	$file['length'] = Functions::format_bytes($file['length']);
	
	Header::set_title("Commando.io - View File");
	Header::render(array("code-pretty", "codemirror"));
	
	Navigation::render("files");
?> 
    <div class="container main-container" data-id="<?php echo $_GET['param1'] ?>">
           
      <div class="row">
      	<div class="span12">
      		<h1 class="header" style="float: left;"><?php echo $file['real_filename'] ?></h1> 
     	 
     	 	<div style="float: right;">
     	 		 <a id="file-id" class="btn btn-large disabled tip" rel="tooltip" data-placement="top" data-original-title="Copy to clipboard."><?php echo $file['_id'] ?></a>
     	 	</div>
     	 </div>
      </div>
      
      <div class="row">
   	  	<div class="span12">
   	  		<div class="well">
   	  			<a id="edit-file-notes" class="btn btn-primary btn-large"><?php echo (isset($file['notes']) && !empty($file['notes'])) ? '<i class="icon-pencil icon-white"></i> Edit Notes' : '<i class="icon-plus-sign icon-white"></i> Add Notes' ?></a>
	      		<a id="delete-file" href="/actions/delete_file.php?id=<?php echo $file['_id'] ?>&amp;<?php echo CSRF::generate_get_parameter() ?>" class="btn btn-large"><i class="icon-remove"></i> Delete File</a>
   	  		</div>
      	</div>
      </div>   
      
	  <div class="row">
    	<div class="span12">
    		<div class="well">
    			<?php if(isset($file['notes']) && !empty($file['notes'])): ?>
    				<div id="file-notes-markdown" class="alert alert-grey fade in">
				 		<a class="close" data-dismiss="alert">&times;</a>
				 		<?php echo Markdown($file['notes']) ?>
				 		<div class="clear"></div>
					</div>
				<?php endif; ?>
				<div id="file-notes-container" style="display: none; margin-bottom: 15px;">
					<form>
						<?php echo CSRF::generate_hidden_field() ?>
						<div class="control-group">
				    		<div class="controls">
				    		 	<textarea id="file-notes" name="notes"><?php echo (isset($file['notes']) && !empty($file['notes'])) ? $file['notes'] : null ?></textarea>
				    		 	<div style="float: left;">
				    				<p class="help-block" style="clear: both;">Optional notes and comments you wish to attach to the file. <a href="http://daringfireball.net/projects/markdown/">Markdown</a> is supported.</p>
				    			</div>
				    			<div style="float: right; margin-top: 5px;">
				    				<a id="save-file-notes" class="btn btn-small btn-primary">Save Notes</a>
				    			</div>
				    		</div>
				    	</div>
					</form>
					<div class="clear"></div>
   	  			</div>
				<div class="navbar">
	            	<div class="navbar-inner">
	              		<div class="container">
	                		<a class="brand" style="cursor: default;"><?php echo empty($file['type']) ? "unknown" : $file['type'] ?></a>
	               			<ul class="nav">
			                	<li class="divider-vertical"></li>
			                	<li>
			                		<a style="cursor: default;">
			                			<?php
			                				if($file['lines'] != 1) {
			                					echo $file['lines'];
			                					echo $file['lines'] == 1 ? ' line / ' : ' lines / ';
			                				}
			                			?>
			                		<?php echo $file['length'] ?></a>
			                	</li>
			                	<li class="divider-vertical"></li>
			                	<li>
			                		<a style="cursor: default;">Added: <?php echo $file['uploadDate'] ?></a>
			                	</li>
	                		</ul>
	              		</div>
            		</div>
          		</div>
				<?php if($file['data'] !== null): ?>
					<div id="contents-loading" class="progress progress-striped active">
						<div class="bar" style="width: 100%;"></div>
					</div>
					<div id="file-contents" style="display: none;">
						<div style="float: right; position: relative; top: 0px; right: 0px;">
							<a href="<?php echo Links::render("download-file", array($file['_id']->__toString())) ?>" class="btn btn-medium"><i class="icon-download-alt"></i> Download</a> <a href="<?php echo Links::render("view-file-raw", array($file['_id']->__toString())) ?>" class="btn btn-medium"><i class="icon-align-left"></i> Raw</a>
						</div>
						<pre class="prettyprint linenums"><?php echo htmlentities($file['data']) ?></pre>
					</div>
				<?php else: ?>
					<div class="alert alert-grey binary-file">
						<div style="float: right; position: relative; top: -11px; right: -11px;">
							<a href="<?php echo Links::render("download-file", array($file['_id']->__toString())) ?>" class="btn btn-medium"><i class="icon-download-alt"></i> Download</a>
						</div>
						<h3 style="margin-left: 112px; color: #cacaca;">Binary file.</h3>
					</div>
				<?php endif; ?>
    		</div>
		</div>
	  </div>
<?php
	Footer::render(array("code-pretty", "codemirror", "autosize", "zclip", "bootbox", "view-file"));
?>