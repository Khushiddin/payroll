<?php
$id = $paymentInfo->id;
$name = $paymentInfo->name;
$empCode = $paymentInfo->empCode;
$bankName = $paymentInfo->bankName;
$acNum = $paymentInfo->acNum;
$userId = $paymentInfo->userId;
$makerAmount = $paymentInfo->makerAmount;
$makerRemark = $paymentInfo->makerRemark;
$checkerAmount = $paymentInfo->checkerAmount;
$checkerRemark = $paymentInfo->checkerRemark;
$approvedBy = $paymentInfo->approvedBy;
$approved = $paymentInfo->approved;
$checker = $paymentInfo->checker;
$ifsc = $paymentInfo->ifscCode;
$role = $this->session->userdata('role');
$empRole = $this->session->userdata('empRole');
$Rolearray =  explode(',', $empRole);
$currentUser = $this->session->userdata('userId');
$vendorLimit = $this->session->userdata('vendorLimit');
// print_r($vendorLimit);die(); 
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Vendor Payment Management
        <small>View Vendor Payment</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">View Vendor Payment Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>checkPayment" method="post" id="checkPayment" role="form">
                        <input type="hidden" id="vendorLimit" name="vendorLimit" value="<?php echo $vendorLimit; ?>">
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
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">IFSC</label>
                                        <div><?=$ifsc?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Vendor Req. Amount</label>
                                        <div><?=$makerAmount?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Remarks</label>
                                        <div><?=$makerRemark?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Vendor</label>
                                        <div><?=$paymentInfo->vendorName?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Request Date</label>
                                        <div><?php echo date('d-m-Y',strtotime($paymentInfo->createdDtm));?></div>
                                    </div>
                                    
                                </div>
                                <?php if($paymentInfo->file_name != '') { ?>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">File</label>
                                        <div><button><a href="<?php echo base_url()?>paymentDownload/<?php echo $id ?>">Download</a></button></div>
                                    </div>
                                    
                                </div>
                                 <?php } ?>
                            </div>
                            
                            
                            <?php if(in_array(3, $Rolearray) && $approved=='1' && $userId!=$currentUser){ ?>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Vendor Approved Amount</label>
                                        <input type="text" class="form-control required" value="<?php echo $checkerAmount ?>" id="checkerAmount" name="checkerAmount" maxlength="128">
                                         <div id ="errors"></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Vendor Approved Remarks</label>
                                        <textarea name="checkerRemark" id="checkerRemark" class="form-control required"><?php echo $checkerRemark; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <?php if($approved=='2' || $approved=='4'){ ?>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Vendor Approved Amount</label>
                                        <div><?=$checkerAmount?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Vendor Approved Remarks</label>
                                        <div><?=$checkerRemark?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Approved By</label>
                                        <div><?=$checker?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Approved Date</label>
                                        <div><?php echo date('d-m-Y',strtotime($paymentInfo->updatedDtm)); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if((in_array(3, $Rolearray) && $approved=='1') || (in_array(4, $Rolearray) && $approved=='2')) { ?>
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />        
                        <div class="box-footer">
                            <?php if(!in_array(4, $Rolearray)){ ?>
                            <input type="button" class="btn btn-primary" value="Submitt" onclick="checkAmount()"/>
                            <?php } else { ?>
                            <input type="button" class="btn btn-primary" value="Approved" onclick="pushApprove(<?=$id;?>,'approved')" />
                            <?php } ?>
                            <input type="button" class="btn btn-default" value="Reject" onclick="pushApprove(<?=$id;?>,'reject')" />
                        </div>
                        <?php } ?>
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
    
    var checkAdvanceForm = $("#checkPayment");
    
    var validator = checkAdvanceForm.validate({
        
        rules:{
            checkerAmount :{ required : true },
            checkerRemark :{ required : true },
            }, 
        messages:{
            checkerAmount :{ required : "This field is required" },
            checkerRemark :{ required : "This field is required" }
        }
    });
});

function checkAmount()
        {
            var limit = parseInt($("#vendorLimit").val());
            var approve = parseInt($("#checkerAmount").val());
            console.log(approve);
            console.log(limit);

            if(limit== 'NULL' || approve <= limit)
            {
                document.getElementById('checkPayment').submit();
                        
            }else{
                 document.getElementById('errors').innerHTML="*You can Approve upto "+limit+"*";
            }
        }

</script>
<script>
        function pushApprove(id,status){
            var confirmMsg = confirm('Are you sure, you want to '+status+' this advance?');
            if(confirmMsg==true){
                $("#pushButton").hide();
                $.ajax({
                    url: '<?=base_url()?>paymentajaxapprove',
                    data:{"id":id,'status':status},
                    type: 'POST',
                    dataType: 'json',
                    success: function(response){
                        location.href="<?=base_url()?>paymentListing";
                    },
                    error: function(){ alert('Failed');}
                });
            }
        }
    </script>
