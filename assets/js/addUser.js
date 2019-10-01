/**
 * File : addUser.js
 * 
 * This file contain the validation of add user form
 * 
 * Using validation plugin : jquery.validate.js
 * 
 * @author Kishor Mali
 */

$(document).ready(function(){
	
	var addUserForm = $("#addUser");
	
	var validator = addUserForm.validate({
		
		rules:{
			fname :{ required : true },
			email : { required : true, email : true, remote : { url : baseURL + "checkEmailExists", type :"post"} },
			password : { required : true },
			cpassword : {required : true, equalTo: "#password"},
			mobile : { required : true, digits : true },
			empCode: { required : true, remote : { url : baseURL + "checkEmpCode", type :"post"} },
			departmentId:{required : true},
			designationId:{required : true},
			doj: {required : true},
			stateId: {required : true},
			locationId: {required : true},
			panNo: {required : true},
			aadhar: {required : true},
			pfuan: {required : true},
			beneficiaryName: {required : true},
			acNum: {required : true},
			bankName: {required : true},
			branch: {required : true},
			city: {required : true},
			ifscCode: {required : true},
			basic: {required : true},
			transAllow: {required : true},
			spclAllow: {required : true},
			lta: {required : true},
			hra: {required : true},
			bonus: {required : true},

			//role : { required : true, selected : true}
		},
		messages:{
			fname :{ required : "This field is required" },
			email : { required : "This field is required", email : "Please enter valid email address", remote : "Email already taken" },
			password : { required : "This field is required" },
			cpassword : {required : "This field is required", equalTo: "Please enter same password" },
			mobile : { required : "This field is required", digits : "Please enter numbers only" },
			empCode : { required : "This field is required", remote : "Employee Code already taken" },
			departmentId : { required : "This field is required" },
			designationId : { required : "This field is required" },
			doj: { required : "This field is required" },
			stateId: {required : "This field is required"},
			panNo: {required : "This field is required"},
			aadhar: {required : "This field is required"},
			pfuan: {required : "This field is required"},
			beneficiaryName: {required : "This field is required"},
			acNum: {required : "This field is required", digits : "Please enter numbers only"},
			bankName: {required : "This field is required"},
			branch: {required : "This field is required"},
			city: {required : "This field is required"},
			ifscCode: {required : "This field is required"},
			basic: {required : "This field is required", digits : "Please enter numbers only"},
			transAllow: {required : "This field is required", digits : "Please enter numbers only"},
			spclAllow: {required : "This field is required", digits : "Please enter numbers only"},
			lta: {required : "This field is required", digits : "Please enter numbers only"},
			hra: {required : "This field is required", digits : "Please enter numbers only"},
			bonus: {required : "This field is required", digits : "Please enter numbers only"},

			//role : { required : "This field is required", selected : "Please select atleast one option" }			
		}
	});

	 jQuery('.datepicker').datepicker({
          autoclose: true,
          format : "dd-mm-yyyy"
        });

	 
});
