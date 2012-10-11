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

var notes;

$(document).ready(function() {
	$(".tip").tooltip();
	
	prettyPrint();
	
	$("#contents-loading").hide();
	$("#file-contents").slideDown(300);
	
	$("#file-id").zclip({
        path: "/js/zclip.swf",
        copy: $("#file-id").html(),
        afterCopy: function() {
        	$("#file-id").effect("highlight", {}, 1000);
        }
    });
	
	notes = CodeMirror.fromTextArea(document.getElementById('file-notes'), {
		mode: 'markdown',
		lineNumbers: false,
		lineWrapping: true,
		matchBrackets: false,
		undoDepth: 250
	});
	
	$("#file-notes").next().find(".CodeMirror-scroll").css("min-height", "83px");
	$("#file-notes").next().find(".CodeMirror-scroll").css("max-height", "152px");
	$("#file-notes").autosize();
	
	$("#edit-file-notes").click(function() {
		if($("#file-notes-markdown").is(":visible")) {
			$("#file-notes-markdown").hide();
		}
	
		$("#file-notes-container").slideToggle(300, function() {
			if(!$("#file-notes-container").is(":visible") && !$("#file-notes-markdown").is(":visible")) {
				$("#file-notes-markdown").show();
			}
		});
		
		notes.refresh();
	});
	
	$("#save-file-notes").click(function() {
		notes.save();
	
		$(this).addClass("disabled");
		
		Request.ajax("/actions/edit_file_notes.php", {
			security_token_2: $("#security_token_2").val(),
			id: $(".main-container").attr("data-id"),
			notes: $("#file-notes").val()
		}, function(response) {
			if(typeof response !== "undefined") {
				if(response.updated === true) {
					location.reload(true);
				}
			}
		});
	});
	
	$("#delete-file").click(function(e) {
		e.preventDefault();
		
		bootbox.setIcons({
			"CONFIRM" : "icon-ok-sign icon-white"
        });
		
		bootbox.confirm("Are you sure you wish to delete this file?", function(confirmed) {
			if(confirmed) {
				window.location = $('#delete-file').attr('href');
			}
		});	
	});
});