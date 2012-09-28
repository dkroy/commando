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

$(document).ready(function() {
	$(".tip").tooltip();
	
	Request.ajax("/actions/get_files.php", {}, function(response) {
		if(response.count == 0) {
			$("#files-did-you-know").slideDown(300);
		} else {
			$("#filter-files").removeAttr("disabled");
		}
		
		$("#table-container").html(response.html);
		$("#progress-container").hide();
		$("#table-container").slideDown(300);
	});
	
	$("#filter-files").bind("keyup input paste", function() {
	    var search_value = $(this).val().toUpperCase();
	    var $files = $("table tr");
	
	    if(search_value === '') {
	        $files.show();
	        return;
	    }
	
	    $files.each(function(index) {
	        if(index !== 0) {
	            $row = $(this);
	            
	            var id = $row.find("td").eq(2).parents("tr").attr("id").toUpperCase();
	            var name = $row.find("td").eq(3).children("a").html().toUpperCase();
	            var type = $row.find("td").eq(4).children("span").html().toUpperCase();
	
	            if ((id.indexOf(search_value) > -1) || (name.indexOf(search_value) > -1) || (type.indexOf(search_value) > -1)) {
	                $row.show();
	            } else {
	                $row.hide();
	            }
	        }
	    });
    });
	
	$(document).on("click", ".expand-file-id", function() {
		$(this).html($(this).parents("tr").attr("id"));
		$(this).removeClass("expand-west");
	});
	
	$("#upload-box").filedrop({
		url: '/actions/add_file.php',
	    paramname: 'file',
	    data: {}, 
	    headers: {},
	    error: function(err, file) {
	    	$("#upload-box").removeClass("green");
	       	$("#upload-box").find(".progress").hide();
	       	$("#upload-box").find(".bar").css("width", "0%");
	        $("#upload-box").find("h3").html("Drag a file here to upload…"); 
	        $("#upload-box").find("h5").html("Maximum file size is 20MB."); 
	        
	        switch(err) {
	            case 'BrowserNotSupported':
	                alert("Your browser does not support html5 drag and drop. Recommend using Google Chrome.");
	                break;
	            case 'TooManyFiles':
	                return;
	                break;
	            case 'FileTooLarge':
	                alert("File '" + file.name + "' exceeds the maximum file size of 20MB.");
	                break;
	            case 'FileTypeNotAllowed':
	                alert("File type is not allowed.");
	                break;
	            default:
	            	return;
	                break;
	        }
	    },
	    allowedfiletypes: [],
	    maxfiles: 1,
	    maxfilesize: 20, //Megabytes
	    dragOver: function() {
	        $("#upload-box").addClass("green");
	        $("#upload-box").find("h3").html("Drop it…");
	    },
	    dragLeave: function() {
	      	$("#upload-box").removeClass("green");
	      	$("#upload-box").find("h3").html("Drag a file here to upload…"); 
	    },
	    docOver: function() {
	        //Dragging files anywhere inside the browser document window
	    },
	    docLeave: function() {
	        //Dragging files out of the browser document window
	    },
	    drop: function() {
	   		$("#upload-box").removeClass("green");
	    	$("#upload-box").find("h3").html("Crunching…");
	      	$("#upload-box").find("h5").html("Please wait.");
	    },
	    uploadStarted: function(i, file, len) {
	       	$("#upload-box").find("h3").html("Uploading… <span id=\"progress-percent\"></span>");
	        $("#upload-box").find("h5").html("");
	        $("#upload-box").find(".progress").show();
	        
	        // a file began uploading
	        // i = index => 0, 1, 2, 3, 4 etc
	        // file is the actual file of the index
	        // len = total files user dropped
	    },
	    uploadFinished: function(i, file, response, time) {
	    	$("#upload-box").removeClass("green");
	       	$("#upload-box").find(".progress").hide();
	       	$("#upload-box").find(".bar").css("width", "0%");
	       	$("#upload-box").find("h3").html("Drag a file here to upload…"); 
	        $("#upload-box").find("h5").html("Maximum file size is 20MB.");
	        $("#table-container").hide();
	        $("#progress-container").show();
	        	       	
	        Request.ajax("/actions/get_files.php", {}, function(response) {	        	
	        	if(response.count == 0) {
	        		$("#filter-files").attr("disabled", "disabled");
					$("#files-did-you-know").show();
				} else {
					$("#filter-files").removeAttr("disabled");
					$("#files-did-you-know").hide();
				}
	        	
	        	$("#table-container").html(response.html);
	        	$("#progress-container").hide();
	        	$("#table-container").slideDown(300);
	        });
	    },
	    progressUpdated: function(i, file, progress) {
	    	$("#upload-box").find(".bar").css("width", progress + "%");
	    	$("#upload-box").find("#progress-percent").html(progress + "%");
	    },
	    globalProgressUpdated: function(progress) {
	        // progress for all the files uploaded on the current instance (percentage)
	        // ex: $('#progress div').width(progress+"%");
	    },
	    speedUpdated: function(i, file, speed) {
	        $("#upload-box").find("h5").html(speed.toFixed(1) + " kB/sec");
	    },
	    rename: function(name) {
	        // name in string format
	        // must return alternate name as string
	    },
	    beforeEach: function(file) {
	        // file is a file object
	        // return false to cancel upload
	    },
	    beforeSend: function(file, i, done) {
	        done();
	    },
	    afterAll: function() {}
	});
	
	
	$(document).on("click", "#files-delete-all-check", function() {
		if($("#files-delete-all-check").attr("checked")) {
			$(".file-delete-check").attr("checked", true);
		} else {
			$(".file-delete-check").attr("checked", false);
		}
		
		if($(".file-delete-check:checked").length > 0) {
			$("#delete-files").removeClass("disabled");
		} else {
			$("#delete-files").addClass("disabled");
		}
	});
	
	$(document).on("click", ".file-delete-check", function() {
		if($(".file-delete-check:checked").length > 0) {
			$("#delete-files").removeClass("disabled");
		} else {
			$("#delete-files").addClass("disabled");
		}
	});
	
	$(document).on("click", "#delete-files", function() {
		var files = get_checked_values(".file-delete-check");
		
		if(files.length === 0) {
			return;
		}
		
		bootbox.setIcons({
			"CONFIRM" : "icon-ok-sign icon-white"
        });
		
		bootbox.confirm("Are you sure you wish to delete <strong>" + files.length + "</strong> files(s)?", function(confirmed) {
			if(confirmed) {
				Request.ajax("/actions/delete_files.php", {
					ids: JSON.stringify(files)
				}, function(response) {
					if(typeof response !== "undefined") {
						for(var i = 0; i < files.length; i++) {
							$("#" + files[i]).fadeOut(300, function() {
								$("#" + files[this.i]).remove();
								
								if(this.i === (files.length - 1)) {
									if($(".file-delete-check:checked").length > 0) {
										$("#delete-files").removeClass("disabled");
									} else {
										$("#delete-files").addClass("disabled");
									}
									
									if($(".file").length === 0) {
										$("#progress-container").show();
										
										Request.ajax("/actions/get_files.php", {}, function(response) {
											if(response.count == 0) {
												$("#filter-files").attr("disabled", "disabled");
												$("#files-did-you-know").slideDown(300);
											}
											
											$("#table-container").html(response.html);
											$("#progress-container").hide();
											$("#table-container").slideDown(300);
										});	
									}
								}
							}.bind({ i: i }));
						}
					}
				});	
			} else {
				return;
			}
	    });
	});
});