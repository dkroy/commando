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
	
	//Get execution history
	$execution_history = array();
	MongoConnection::connect();
	MongoConnection::select_collection("executions");
	$results = MongoConnection::find();
	$results->sort(array("executed" => -1));
	MongoConnection::close();
	
	foreach($results as $result) {
		$result['executed'] = date(DATE_FORMAT, ($result['executed']->sec + Functions::timezone_offset_in_seconds()));
		$execution_history[] = $result;
	}
	
	Header::set_title("Commando.io - Execution History");
	Header::render();
	
	Navigation::render("execute");
?>    
    <div class="container">
           
      <h1 class="header">Execution History</h1> 
      
      <div class="row">
   	 	<div class="span12">
      		<div class="well">
      			<div class="input-prepend" style="float: right">
					<span class="add-on">
						<i class="icon-search"></i>
					</span><input id="filter-execution-history" type="text" class="span3 tip" rel="tooltip" data-placement="top" data-original-title="By id, date, name or interpreter." maxlength="100" placeholder="Filter Execution History…" value="" <?php echo (count($execution_history) === 0) ? ' disabled="disabled"' : null ?> />
				</div>
				<div class="clear"></div>
      		</div>
      	</div>
      </div>
      
      <div class="row">
		<div class="span12">
			<div class="well">
				<div class="alert alert-grey no-bottom-margin" <?php if(count($execution_history) > 0): ?>style="display: none;"<?php endif; ?>>
			      	No execution history stored. Go <a href="<?php echo Links::render("execute") ?>">execute</a>.
				</div>
				<?php if(count($execution_history) > 0): ?>
		      	  	<div id="table-container">
				      <table class="table table-striped table-hover table-bordered table-condensed">
				      	<thead>
				      		<tr>
				      			<th>ID</th>
				      			<th>Execution Date</th>
				      			<th>Servers</th>
				      			<th>Recipe Name</th>
				      			<th>Recipe Interpreter</th>
				      		</tr>
				      	</thead>
				      	<tbody>
			      			<?php foreach($execution_history as $history): ?>
			      				<tr id="<?php echo $history['_id'] ?>" class="execution-history">
				      				<td><a class="btn btn-mini disabled expand-west expand-execution-history-id"><?php echo Functions::add_ellipsis_reverse($history['_id'], 8) ?></a></td>
			      					<td><a href="<?php echo Links::render("view-execution-history", array($history['_id']->__toString())) ?>"><?php echo $history['executed'] ?></a></td>
			      					<td><a class="servers-popover btn btn-small" data-content="
			      						<?php
			      							$out = '';
			      							
			      							foreach($history['servers'] as $server) {
			      								$out .= $server['label'] . ', ';
			      							}
			      							
			      							echo rtrim($out, ', ');
			      					 	?>
			      					 " data-title="<?php if(!empty($history['servers'])): ?>Servers<?php endif; ?>"><span class="icon-search"></span> <?php echo count($history['servers']) ?></td>
			      					<td><?php echo $history['recipe']['name'] ?></td>
			      					<td><?php echo ucfirst($history['recipe']['interpreter']) ?></td>
			      				</tr>
			      			<?php endforeach; ?>
				      	</tbody>
				      </table>
		      	  	</div>
		    	<?php endif; ?>
			</div>
	    </div>
	  </div>
<?php
	Footer::render(array("execution-history"));
?>