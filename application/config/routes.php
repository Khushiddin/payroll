<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = "login";
$route['404_override'] = 'error_404';
$route['translate_uri_dashes'] = FALSE;


/*********** USER DEFINED ROUTES *******************/

$route['loginMe'] = 'login/loginMe';
$route['dashboard'] = 'user';
$route['logout'] = 'user/logout';
$route['userListing'] = 'user/userListing';
$route['userListing/(:num)'] = "user/userListing/$1";
$route['easyuserListing'] = 'user/easyuserListing';
$route['easyuserListing/(:num)'] = "user/easyuserListing/$1";
$route['addNew/(:any)'] = "user/addNew/$1";
$route['addNewUser'] = "user/addNewUser";
$route['editOld'] = "user/editOld";
$route['editOld/(:num)/(:any)'] = "user/editOld/$1/$2";
$route['editUser'] = "user/editUser";
$route['deleteUser'] = "user/deleteUser";
$route['profile'] = "user/profile";
$route['profile/(:any)'] = "user/profile/$1";
$route['profileUpdate'] = "user/profileUpdate";
$route['profileUpdate/(:any)'] = "user/profileUpdate/$1";

$route['loadChangePass'] = "user/loadChangePass";
$route['changePassword'] = "user/changePassword";
$route['changePassword/(:any)'] = "user/changePassword/$1";
$route['pageNotFound'] = "user/pageNotFound";
$route['checkEmailExists'] = "user/checkEmailExists";
$route['login-history'] = "user/loginHistoy";
$route['login-history/(:num)'] = "user/loginHistoy/$1";
$route['login-history/(:num)/(:num)'] = "user/loginHistoy/$1/$2";

$route['forgotPassword'] = "login/forgotPassword";
$route['resetPasswordUser'] = "login/resetPasswordUser";
$route['resetPasswordConfirmUser'] = "login/resetPasswordConfirmUser";
$route['resetPasswordConfirmUser/(:any)'] = "login/resetPasswordConfirmUser/$1";
$route['resetPasswordConfirmUser/(:any)/(:any)'] = "login/resetPasswordConfirmUser/$1/$2";
$route['createPasswordUser'] = "login/createPasswordUser";

$route['departmentListing'] = 'department/departmentListing';
$route['departmentListing/(:num)'] = "department/departmentListing/$1";
$route['addNewDepartment'] = "department/addNewDepartment";
$route['addNewDepartmentSave'] = "department/addNewDepartmentSave";
$route['department/editOld'] = "department/editOld";
$route['department/editOld/(:num)'] = "department/editOld/$1";
$route['editDepartment'] = "department/editDepartment";
$route['deleteDepartment'] = "department/deleteDepartment";


$route['designationListing'] = 'designation/designationListing';
$route['designationListing/(:num)'] = "designation/designationListing/$1";
$route['addNewDesignation'] = "designation/addNewDesignation";
$route['addNewDesignationSave'] = "designation/addNewDesignationSave";
$route['designation/editOld'] = "designation/editOld";
$route['designation/editOld/(:num)'] = "designation/editOld/$1";
$route['editDesignation'] = "designation/editDesignation";
$route['deleteDesignation'] = "designation/deleteDesignation";

$route['getlocation'] = "user/getlocation";
$route['checkEmpCode'] = "user/checkEmpCode";

$route['payroll'] = "payroll/index";
$route['payroll/(:num)'] = "payroll/index/$1";
$route['payroll/(:num)/(:num)'] = "payroll/payroll/$1/$2";
$route['download/(:num)'] = "payroll/download/$1";
$route['upload'] = "upload/upload";
$route['saveupload'] = "upload/saveupload";
$route['employeeListing'] = "payroll/employeeListing";
$route['employeeListing/(:num)'] = "payroll/employeeListing/$1";

$route['advanceListing'] = "advance/advanceListing";
$route['advanceListing/(:num)'] = "advance/advanceListing/$1";
$route['addNewAdvance'] = "advance/addNewAdvance";
$route['addNewAdvanceSave'] = "advance/addNewAdvanceSave";
$route['adListing'] = "advance/index";
$route['adListing/(:any)'] = "advance/index/$1";
$route['ajaxapprove'] = "advance/ajaxapprove";
$route['advance/editOld'] = "advance/editOld";
$route['advance/editOld/(:num)'] = "advance/editOld/$1";
$route['editAdvance'] = "advance/editAdvance";
$route['checkAdvance'] = "advance/checkAdvance";
$route['viewAdvance/(:any)'] = "advance/viewAdvance/$1";
$route['deleteAdvance'] = "advance/deleteAdvance";

$route['vendorListing'] = "advance/vendorListing";
$route['vendorListing/(:num)'] = "advance/vendorListing/$1";
$route['adVendor'] = "advance/vendor";
$route['adVendor/(:any)'] = "advance/vendor/$1";
$route['viewVendor/(:any)'] = "advance/viewVendor/$1";
$route['addNewVendor'] = "advance/addNewVendor";
$route['addNewVendorSave'] = "advance/addNewVendorSave";
$route['vendor/editOld'] = "advance/editOldVendor";
$route['vendor/editOld/(:num)'] = "advance/editOldVendor/$1";
$route['editVendor'] = "advance/editVendor";
$route['deleteVendor'] = "advance/deleteVendor";
$route['vendorajaxapprove'] = "advance/vendorajaxapprove";


$route['expensesListing'] = "expenses/expensesListing";
$route['expensesListing/(:num)'] = "expenses/expensesListing/$1";
$route['addNewExpenses'] = "expenses/addNewExpenses";
$route['addNewExpensesSave'] = "expenses/addNewExpensesSave";
$route['exListing'] = "expenses/index";
$route['exListing/(:any)'] = "expenses/index/$1";
$route['expenses/ajaxapprove'] = "expenses/ajaxapprove";
$route['expenses/editOld'] = "expenses/editOld";
$route['expenses/editOld/(:num)'] = "expenses/editOld/$1";
$route['editExpenses'] = "expenses/editExpenses";
$route['checkExpenses'] = "expenses/checkExpenses";
$route['viewExpenses/(:any)'] = "expenses/viewExpenses/$1";
$route['pdfExpenses/(:any)'] = "expenses/pdfExpenses/$1";


$route['addNewPayment'] = "payment/addNewPayment";
$route['addNewPaymentSave'] = "payment/addNewPaymentSave";
$route['paymentListing'] = "payment/index";
$route['paymentListing/(:any)'] = "payment/index/$1";
$route['paymentajaxapprove'] = "payment/ajaxapprove";
$route['payment/editOld'] = "payment/editOld";
$route['payment/editOld/(:num)'] = "payment/editOld/$1";
$route['editPayment'] = "payment/editPayment";
$route['checkPayment'] = "payment/checkPayment";
$route['viewPayment/(:any)'] = "payment/viewPayment/$1";
$route['paymentDownload/(:num)'] = "payment/download/$1";

$route['roles'] = "user/viewRoles";
$route['roles/(:num)'] = "user/viewRoles/$1";
$route['manageRoles/(:num)'] = "user/manageRoles/$1";
$route['updateRole/(:num)'] = "user/updateRole/$1";

$route['uploadTrans'] = "upload/uploadTrans"; 
$route['uploadCsv'] = "upload/salaryCsv";  

$route['uploadStructure'] = "upload/uploadStructure";  
$route['structureCsv'] = "upload/structureCsv";  

$route['policy'] = 'user/policy';

$route['expenseTotal'] = "expenses/expenseTotal";
$route['expenseTotal/(:num)'] = "expenses/expenseTotal/$1";
$route['vendorTotal'] = "payment/vendorTotal"; 
$route['vendorTotal/(:num)'] = "payment/vendorTotal/$1";
$route['queriesListing'] = "helpdesk/index";
$route['deleteExpense'] = "expenses/deleteExpense";
/* End of file routes.php */
/* Location: ./application/config/routes.php */
