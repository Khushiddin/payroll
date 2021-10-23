<?php
$id = $vendorInfo->id;
$vendorName = $vendorInfo->vendorName;
$address = $vendorInfo->address;
$bankName = $vendorInfo->bankName;
$acNumber = $vendorInfo->acNumber;
$contactNo = $vendorInfo->contactNo;
$email = $vendorInfo->email;
$beneficiaryName = $vendorInfo->beneficiaryName;
$branch = $vendorInfo->branch;
$city = $vendorInfo->city;
$ifsc = $vendorInfo->ifsc;
$panNumber = $vendorInfo->panNumber;
$gstNumber = $vendorInfo->gstNumber;
$approved = $vendorInfo->approved;
// $checker = $vendorInfo->checker;
$role = $this->session->userdata('role');
$empRole = $this->session->userdata('empRole');
$Rolearray =  explode(',', $empRole);
$currentUser = $this->session->userdata('userId');
$advanceLimit = $this->session->userdata('advanceLimit');

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Vendor Management
        <small>View Vendor</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">View Vendor Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>checkAdvance" method="post" id="checkAdvance" role="form">
                        <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Name</label>
                                        <div><?php echo $vendorName?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Address</label>
                                        <div><?=$address?></div>
                                    </div>
                                    
                                </div>
                        </div>
                        <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Contact Number</label>
                                        <div><?php echo $contactNo?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Email</label>
                                        <div><?=$email?></div>
                                    </div>
                                    
                                </div>
                        </div>
                        <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Beneficiary Name</label>
                                        <div><?php echo $beneficiaryName?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Bank A/C No.</label>
                                        <div><?=$acNumber?></div>
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
                                        <label for="fname">Branch</label>
                                        <div><?=$branch?></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">City</label>
                                        <div><?=$city?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">IFSC</label>
                                        <div><?=$ifsc?></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Pan Number</label>
                                        <div><?=$panNumber?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">GST Number</label>
                                        <div><?=$gstNumber?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Request Date</label>
                                        <div><?php echo date('d-m-Y',strtotime($vendorInfo->createdDtm));?></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <?php if((in_array(3, $Rolearray) && $approved=='1') || (in_array(4, $Rolearray) && $approved=='2')) { ?>
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />        
                        <div class="box-footer"> 
                            <?php if(!in_array(4, $Rolearray)){ ?>
                            <input type="button" class="btn btn-primary" value="Approved" onclick="pushApprove(<?=$id;?>,'approved')" />
                            <?php } else { ?>
                            <input type="button" class="btn btn-primary" value="Approved" onclick="pushApprove(<?=$id;?>,'approved')" />
                            <?php } ?>
                            <input type="button" class="btn btn-default" value="Reject" onclick="pushApprove(<?=$id;?>,'reject')" />
                        </div>
                        <?php } ?>
                           
                </div>
            </div>
        </div>    
    </section>
</div>

<script type="text/javascript">
    
$(document).ready(function(){
    
    var checkAdvanceForm = $("#checkAdvance");
    
    var validator = checkAdvanceForm.validate({
        
        rules:{
            checkerAmount :{ required : true },
            checkerRemark :{ required : true },
            }, 
        messages:{
            checkerAmount :{ required : "This field is required" }
            checkerRemark :{ required : "This field is required" }
        }
    });
});


</script>
<script>
        function pushApprove(id,status){
            var confirmMsg = confirm('Are you sure, you want to '+status+' this advance?');
            if(confirmMsg==true){
                $("#pushButton").hide();
                $.ajax({
                    url: '<?=base_url()?>vendorajaxapprove',
                    data:{"id":id,'status':status},
                    type: 'POST',
                    dataType: 'json',
                    success: function(response){
                        location.href="<?=base_url()?>adVendor";
                    },
                    error: function(){ alert('Failed');}
                });
            }
        }
    </script>
