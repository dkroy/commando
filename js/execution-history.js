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
	
	$(".servers-popover").each(function() {
		if($(this).attr("data-content").length > 0) {
			$(this).popover({
				placement: 'top',
				trigger: 'click',
				delay: { show: 0, hide: 0 }
			});
		}
	});
	
	$(".expand-execution-history-id").on("click", function() {
		$(this).html($(this).parents("tr").attr("id"));
		$(this).removeClass("expand-west");
	});
	
	$("#filter-execution-history").bind("keyup input paste", function() {
	    var search_value = $(this).val().toUpperCase();
	    var $execution_history = $("table tr");
	
	    if(search_value === '') {
	        $execution_history.show();
	        return;
	    }
	
	    $execution_history.each(function(index) {
	        if(index !== 0) {
	            $row = $(this);
	            
	            var id = $row.find("td").eq(0).parents("tr").attr("id").toUpperCase();
	            var date = $row.find("td").eq(1).children("a").html().toUpperCase();
	            var name = $row.find("td").eq(3).text().toUpperCase();
	            var interpreter = $row.find("td").eq(4).text().toUpperCase();
	
	            if ((id.indexOf(search_value) > -1) || (date.indexOf(search_value) > -1) || (name.indexOf(search_value) > -1) || (interpreter.indexOf(search_value) > -1)) {
	                $row.show();
	            } else {
	                $row.hide();
	            }
	        }
	    });
    });
});