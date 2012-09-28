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
	$(".tip-delay").tooltip({
		delay: { show: 600, hide: 0 }
	});
	
	$(".tip").tooltip();
	
	$(".expand-recipe-id").on("click", function() {
		$(this).html($(this).parents("tr").attr("id"));
		$(this).removeClass("expand-east");
	});
	
	check_uncheck($("#recipe-delete-all-check"), $(".recipe-delete-check"), function() {
		if($(".recipe-delete-check:checked").length > 0) {
			$("#delete-recipes").removeClass("disabled");
		} else {
			$("#delete-recipes").addClass("disabled");
		}
	});
	
    $("#filter-recipes").bind("keyup input paste", function() {
	    var search_value = $(this).val().toUpperCase();
	    var $recipes = $("table tr");
	
	    if(search_value === '') {
	        $recipes.show();
	        return;
	    }
	
	    $recipes.each(function(index) {
	        if(index !== 0) {
	            $row = $(this);
	            
	            var id = $row.find("td").eq(2).parents("tr").attr("id").toUpperCase();
	            var name = $row.find("td").eq(3).children("a").html().toUpperCase();
	            var interpreter = $row.find("td").eq(4).text().toUpperCase();
	
	            if ((id.indexOf(search_value) > -1) || (name.indexOf(search_value) > -1) || (interpreter.indexOf(search_value) > -1)) {
	                $row.show();
	            } else {
	                $row.hide();
	            }
	        }
	    });
    });
	
	$(".recipe-delete-check").click(function() {
		if($(".recipe-delete-check:checked").length > 0) {
			$("#delete-recipes").removeClass("disabled");
		} else {
			$("#delete-recipes").addClass("disabled");
		}
	});
	
	$("#delete-recipes").click(function() {
		var recipes = get_checked_values(".recipe-delete-check");
		
		if(recipes.length === 0) {
			return;
		}
		
		bootbox.setIcons({
			"CONFIRM" : "icon-ok-sign icon-white"
        });
		
		bootbox.confirm("Are you sure you wish to delete <strong>" + recipes.length + "</strong> recipe(s)? All previous versions of the recipe(s) will be deleted as well.", function(confirmed) {
			if(confirmed) {
				Request.ajax("/actions/delete_recipes.php", {
					ids: JSON.stringify(recipes)
				}, function(response) {
					if(typeof response !== "undefined") {
						for(var i = 0; i < recipes.length; i++) {
							$("#" + recipes[i]).fadeOut(300, function() {
								$("#" + recipes[this.i]).remove();
								
								if(this.i === (recipes.length - 1)) {
									if($(".recipe-delete-check:checked").length > 0) {
										$("#delete-recipes").removeClass("disabled");
									} else {
										$("#delete-recipes").addClass("disabled");
									}
									
									if($(".recipe").length === 0) {
										$("#filter-recipes").attr("disabled", "disabled");
										$("#table-container").hide();
										$("#no-recipes").show();
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