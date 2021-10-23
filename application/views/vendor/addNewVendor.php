<?php 

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Vendor Management
        <small>Add Vendor</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Vendor Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addVendor" action="<?php echo base_url() ?>addNewVendorSave" method="post" role="form">
                    
                    <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Vendor Name</label>
                                        <input type="text" class="form-control required" id="vendorName" name="vendorName" maxlength="128" placeholder="Vendor Name">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Address</label>
                                        <input type="text" class="form-control required" id="address" name="address" maxlength="128" placeholder="Address">
                                    </div>
                                    
                                </div>
                        </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Contact NO.</label>
                                        <input type="text" class="form-control required" id="contactNo" name="contactNo" maxlength="128" placeholder="Contact Number">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Email</label>
                                        <input type="email" class="form-control required" id="email" name="email" maxlength="128" placeholder="Email">
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Beneficiary Name</label>
                                        <input type="text" class="form-control required" id="beneficiaryName" name="beneficiaryName" maxlength="128" placeholder="Beneficiary Name">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Account No.</label>
                                        <input type="text" class="form-control required" id="acNumber" name="acNumber" maxlength="128" placeholder="Account Number">
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Bank Name</label>
                                        <input type="text" class="form-control required" id="bankName" name="bankName" maxlength="128" placeholder="Bank Name">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Branch</label>
                                        <input type="text" class="form-control required" id="branch" name="branch" maxlength="128" placeholder="Branch">
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">City</label>
                                        <input type="text" class="form-control required" id="city" name="city" maxlength="128" placeholder="City">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">IFSC</label>
                                        <input type="text" class="form-control required" id="ifsc" name="ifsc" maxlength="128" placeholder="IFSC">
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Pan No.</label>
                                        <input type="text" class="form-control required" id="panNumber" name="panNumber" maxlength="128" placeholder="Pan Number" required>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">GST No.</label>
                                        <input type="text" class="form-control required" id="gstNumber" name="gstNumber" maxlength="128" placeholder="GST Number" required>
                                    </div>
                                    
                                </div>
                            </div>
                        <!-- /.box-body -->
                        
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>    
    </section>
    
</div>
<script type="text/javascript">
    
$(document).ready(function(){
    
    var addAdvanceForm = $("#addVendor");
    
    var validator = addAdvanceForm.validate({
        
        rules:{
            vendorName :{ required : true }
            contactNo :{ required : true, digits : true  },
            acNumber :{ required : true, digits : true  },
            makerRemark :{ required : true }
            },
        messages:{
            vendorName :{ required : "This field is required" }
            contactNo :{ required : "This field is required",digits : "Please enter numbers only" },
            acNumber :{ required : "This field is required",digits : "Please enter numbers only" },
            makerRemark :{ required : "This field is required" }
      
        }
    });
});


</script>