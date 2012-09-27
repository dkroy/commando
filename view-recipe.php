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
	
	$result = MySQLQueries::get_recipe_head_version($_GET['param1']);
	$head = MySQLConnection::fetch_object($result);
	
	if(empty($head)) {
		Error::halt(404, 'not found', 'Recipe \'' . $_GET['param1'] . '\' does not exist.');
	}

	if(isset($_GET['param2']) && !empty($_GET['param2'])) {
		$recipe_version = $_GET['param2'];
	} else {
		$recipe_version = $head->recipe_version;
	}
	
	$result = MySQLQueries::get_recipe_by_version($_GET['param1'], $recipe_version);
	$recipe = MySQLConnection::fetch_object($result);
	
	if(empty($recipe)) {
		Error::halt(404, 'not found', 'Recipe version \'' . $recipe_version . '\' does not exist.');
	}
	
	$recipe = Functions::format_dates($recipe);
	
	//Get recipe versions
	$recipe_versions = array();
	$result = MySQLQueries::get_recipe_versions($_GET['param1']);
	while($row = MySQLConnection::fetch_object($result)) {		
		////
		// Move head to the top of the array
		////
		if($row->id === $head->recipe_version) {
			array_unshift($recipe_versions, $row);
		} else {
			$recipe_versions[] = $row;
		}
	}
	
	//Calculate Statistics
	$recipe->lines = (substr_count($recipe->content, "\n") + 1);
	$recipe->length = Functions::format_bytes(strlen($recipe->content));
	
	//Get the correct language for code-pretty
	switch($recipe->interpreter) {
		case 'shell':
			$code_pretty_lang = "lang-sh";
			break;
		case 'bash':
			$code_pretty_lang = "lang-bsh";
			break;
		case 'node.js':
			$code_pretty_lang = "lang-js";
			break;
		case 'perl':
			$code_pretty_lang = "lang-perl";
			break;
		case 'python':
			$code_pretty_lang = "lang-py";
			break;
	}
	
	Header::set_title("Commando.io - View Recipe");
	Header::render(array("chosen", "code-pretty"));
	
	Navigation::render("recipes");
?> 
    <div class="container">
           
      <div class="row">
      	<div class="span12">
      		<h1 class="header" style="float: left;"><?php echo $recipe->name ?></h1> 
     	 
     	 	<div style="float: right;">
     	 		 <a id="recipe-id" class="btn btn-large disabled tip" rel="tooltip" data-placement="top" data-original-title="Copy to clipboard."><?php echo $recipe->id ?></a>
     	 	</div>
     	 </div>
      </div>
      
      <div class="row">
   	  	<div class="span12">
   	  		<div class="well">
	   	  		<?php if($recipe->recipe_version === $head->recipe_version): ?>
	      			<a href="<?php echo Links::render("edit-recipe", array($recipe->id)) ?>" class="btn btn-primary btn-large"><i class="icon-pencil icon-white"></i> Edit Recipe</a>
	      			<a id="delete-recipe" href="/actions/delete_recipe.php?id=<?php echo $recipe->id ?>&amp;<?php echo CSRF::generate_get_parameter() ?>" class="btn btn-large"><i class="icon-remove"></i> Delete Recipe</a>
	      		<?php else: ?>
	      			<div class="alert alert-info no-bottom-margin">
						<h4>Notice!</h4>
						You are viewing an <strong><u>old version</u></strong> of this recipe. Only the <strong><u>head</u></strong> version of recipes may be edited. If you would like to make modifications to this recipe, navigate to the <a href="<?php echo Links::render("view-recipe", array($recipe->id)) ?>">head</a>.
		  			</div>
		  		<?php endif; ?>
   	  		</div>
      	</div>
      </div>
      
	  <div class="row">
    	<div class="span12">
    		<div class="well">
    			<div class="clear"></div>
    			<?php
		  			preg_match_all('/\{\{include:([a-zA-Z0-9_]{25})\}\}/i', $recipe->content, $include_matches, PREG_PATTERN_ORDER);
					preg_match_all('/\{\{file:([a-zA-Z0-9]{24})\}\}/i', $recipe->content, $files_matches, PREG_PATTERN_ORDER);
					
					if(isset($include_matches[1])) {
						$include_matches = array_unique($include_matches[1]);
					}
					
					if(isset($files_matches[1])) {
						$files_matches = array_unique($files_matches[1]);
					}
					
		  			if(count($include_matches) > 0 && count($files_matches) > 0):
		  		?>
		  			<div class="alert alert-info">
						<h4>Notice!</h4>
						This recipe version <strong>includes</strong> <?php echo (count($include_matches) === 1) ? 'a recipe' : 'recipes' ?> and <strong>transfers</strong> <?php echo (count($files_matches) === 1) ? 'a file.' : 'files.' ?>
		  			</div>
		  		<?php elseif (count($include_matches) > 0): ?>
		  			<div class="alert alert-info">
						<h4>Notice!</h4>
						This recipe version <strong>includes</strong> <?php echo (count($include_matches) === 1) ? 'a recipe.' : 'recipes.' ?>
		  			</div>
		  		<?php elseif (count($files_matches)): ?>
		  			<div class="alert alert-info">
						<h4>Notice!</h4>
						This recipe version <strong>transfers</strong> <?php echo (count($files_matches) === 1) ? 'a file.' : 'files.' ?>
		  			</div>
		  		<?php endif; ?>
    			<?php if(!empty($recipe->notes)): ?>
    				<div id="recipe-notes" class="alert alert-grey fade in">
				 		<a class="close" data-dismiss="alert">&times;</a>
				 		<?php echo Markdown($recipe->notes) ?>
				 		<div class="clear"></div>
					</div>
    			<?php endif; ?>
				<div class="navbar">
	            	<div class="navbar-inner">
	              		<div class="container">
	                		<a class="brand" style="cursor: default;"><?php echo ucfirst($recipe->interpreter) ?></a>
	               			<ul class="nav">
			                	<li class="divider-vertical"></li>
			                	<li>
			                		<a style="cursor: default;"><?php echo $recipe->lines ?> <?php echo $recipe->lines == 1 ? 'line' : 'lines'; ?> / <?php echo $recipe->length ?></a>
			                	</li>
			                	<li class="divider-vertical"></li>
			                	<li>
			                		<a style="cursor: default;">Added: <?php echo $recipe->added ?></a>
			                	</li>
	                		</ul>
	                		<ul class="navbar-form nav pull-right">
	                			<?php if($recipe->recipe_version !== $head->recipe_version): ?>
	                				<li>
	                					<a href="/actions/edit_recipe_head.php?id=<?php echo $recipe->id ?>&amp;version=<?php echo $recipe->recipe_version ?>&amp;<?php echo CSRF::generate_get_parameter() ?>" rel="tooltip" class="tip" data-placement="top" data-original-title="Promote this version to head."><i class="icon-chevron-up"></i> Make Head</a>
	                				</li>
	                				<li class="divider-vertical"></li>
	                			<?php endif; ?>
	                			<li>
	                				<select name="versions" id="recipe-versions" class="span2" data-placeholder="">
										<?php foreach($recipe_versions as $recipe_version): ?>
											<option value="
												<?php
													if($recipe_version->id === $head->recipe_version) {
														echo Links::render("view-recipe", array($recipe->id));	
													} else {
														echo Links::render("view-recipe", array($recipe->id, $recipe_version->id));
													}
												?>
											" <?php if($recipe_version->id === $recipe->recipe_version) { echo 'selected="selected"'; } ?>><?php echo substr($recipe_version->version, 0, 10) ?><?php if($recipe_version->id === $head->recipe_version): ?> (HEAD)<?php endif; ?></option>
										<?php endforeach; ?>
									</select>
	                			</li>
	                		</ul>
	              		</div>
            		</div>
          		</div>
          		<div id="contents-loading" class="progress progress-striped active">
					<div class="bar" style="width: 100%;"></div>
				</div>
				<div id="recipe-contents" style="display: none;">
					<div style="float: right; position: relative; top: 0px; right: 0px;">
						<a href="<?php
							if($recipe->recipe_version === $head->recipe_version) {
								echo Links::render("download-recipe", array($recipe->id));
							} else {
								echo Links::render("download-recipe", array($recipe->id, $recipe->recipe_version));
							}
						?>
						" class="btn btn-medium"><i class="icon-download-alt"></i> Download</a>
						
						<a href="<?php
							if($recipe->recipe_version === $head->recipe_version) {
								echo Links::render("view-recipe-raw", array($recipe->id));
							} else {
								echo Links::render("view-recipe-raw", array($recipe->id, $recipe->recipe_version));
							}
						?>
						" class="btn btn-medium"><i class="icon-align-left"></i> Raw</a>
					</div>
					<pre class="prettyprint <?php echo $code_pretty_lang ?> linenums"><?php echo htmlentities($recipe->content) ?></pre>
    			</div>
    		</div>
		</div>
	  </div>
<?php
	Footer::render(array("chosen", "code-pretty", "zclip", "bootbox", "view-recipe"));
?>