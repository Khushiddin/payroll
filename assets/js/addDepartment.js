/**
 * File : addDepartment.js
 * 
 * This file contain the validation of add user form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Kishor Mali
 */

$(document).ready(function(){
	
	var addDepartmentForm = $("#addDepartment");
	
	var validator = addDepartmentForm.validate({
		
		rules:{
			departmentName :{ required : true },
		},
		messages:{
			fname :{ required : "This field is required" },
			
		}
	});
});
