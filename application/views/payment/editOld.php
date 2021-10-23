<?php
$id = $paymentInfo->id;
$name = $paymentInfo->name;
$empCode = $paymentInfo->empCode;
$acNum = $paymentInfo->acNum;
$bankName = $paymentInfo->bankName;
$userId = $paymentInfo->userId;
$vendorId = $paymentInfo->vendorId;
$makerAmount = $paymentInfo->makerAmount;
$makerRemark = $paymentInfo->makerRemark;

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Vendor Payment Management
        <small>Add / Edit Vendor Payment</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Vendor Payment Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>editPayment" method="post" id="editPayment" role="form">
                        <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Name</label>
                                        <div><?php echo $name.' ('.$empCode.')'.' '.$acNum ?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Designation</label>
                                        <div>Manager</div>
                                    </div>
                                    
                                </div>
                        </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Bank Name</label>
                                        <div><?=$bankName?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Bank A/C No.</label>
                                        <div><?=$acNum?></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Vendor</label>
                                        <select name="vendorId" id="vendorId" class="form-control required">
                                            <option value="">Select</option>
                                            <?php foreach($vendorInfo as $res){?>
                                                <option value="<?=$res->id?>" <?php if($vendorId==$res->id){ echo 'selected';} ?> ><?=$res->name?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Advance Req. Amount</label>
                                        <input type="text" class="form-control required" value="<?php echo $makerAmount ?>" id="makerAmount" name="makerAmount" maxlength="128">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Remarks</label>
                                        <textarea name="makerRemark" id="makerRemark" class="form-control required"><?php echo $makerRemark; ?></textarea>
                                    </div>
                                    
                                </div>
                            </div>
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />        
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
    
    var editAdvanceForm = $("#editAdvance");
    
    var validator = editAdvanceForm.validate({
        
        rules:{
            makerAmount :{ required : true },
            makerRemark :{ required : true },
            }, 
        messages:{
            makerAmount :{ required : "This field is required" }
            makerRemark :{ required : "This field is required" }
        }
    });
});


</script>
