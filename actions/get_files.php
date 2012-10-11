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
	
	//Get files
	$files = array();
	MongoConnection::connect();
	MongoConnection::grid_fs();
	$results = MongoConnection::grid_fs_find();
	$results->sort(array("uploadDate" => -1));
	MongoConnection::close();
	
	foreach($results as $result) {
		$result->file['uploadDate'] = date(DATE_FORMAT, ($result->file['uploadDate']->sec + Functions::timezone_offset_in_seconds()));
		$files[] = $result->file;
	}

	$html = "";
	
	if(count($files) === 0) {
		$html .= '<div id="no-files" class="alert alert-grey no-bottom-margin">No files added. Drag a file into the upload box above.</div>';
	} else {
		$html .= '<div class="control-group"><div class="controls"><a id="delete-files" class="btn disabled"><i class="icon-remove"></i> Delete Selected</a></div></div><table class="table table-striped table-hover table-bordered table-condensed"><thead><tr><th><input type="checkbox" id="files-delete-all-check" /></th><th>Action</th><th>ID</th><th>Name</th><th>Type</th><th>Size</th><th>Added</th></tr></thead><tbody>';
		
		foreach($files as $file) {
			$html .= '<tr id="' . $file['_id'] . '" class="file"><td><input type="checkbox" class="file-delete-check" value="' . $file['_id'] . '" /></td><td><a href="' . Links::render("download-file", array($file['_id']->__toString())) . '" class="btn btn-mini"><i class="icon-download-alt"></i></a>';
			$html .= (strpos($file['type'], 'text') !== false || $file['type'] === "application/json") ? ' <a href="' . Links::render("view-file-raw", array($file['_id']->__toString())) . '" class="btn btn-mini"><i class="icon-align-left"></i>' : null;
			$html .= '</td><td><a class="btn btn-mini disabled expand-west expand-file-id">' . Functions::add_ellipsis_reverse($file['_id'], 8) . '</a></td><td><a href="' . Links::render("view-file", array($file['_id']->__toString())) . '">' . $file['real_filename'] . '</a></td><td><span class="badge badge-info">';
			$html .= empty($file['type']) ? "unknown" : $file['type'];
			$html .= '</span></td><td>' . Functions::format_bytes($file['length']) . '</td><td>' . $file['uploadDate'] . '</td></tr>';
		}
		
		$html .= '</tbody></table>';
	}
	
	echo '{"count":' . count($files) . ',"html":' . json_encode($html) . '}';
?>