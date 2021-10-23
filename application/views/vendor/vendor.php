<link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<?php

$role = $this->session->userdata('role');
/* 3 => maker
2 => checker
4 => account */
$pendingClass='unactive';$approvedClass='unactive';$submittedClass='unactive';$rejectedClass='unactive'; $financeApprovedClass='';
            $rejectedCount=0;
            $approvedCount=0;
            $submittedCount=0;
            $pendingCount=0;
            $financeApprovedCount=0;
            if(!empty($advanceTotal)){
                foreach($advanceTotal as $countVal){
                    if($countVal->approved==0){
                        $pendingCount=$countVal->allcount;
                    }
                    if($countVal->approved==1){
                        $submittedCount=$countVal->allcount;
                    }
                    if($countVal->approved==2){
                        $approvedCount=$countVal->allcount;
                    }
                    if($countVal->approved==3){
                        $rejectedCount=$countVal->allcount;
                    }
                    if($countVal->approved==4){
                        $financeApprovedCount=$countVal->allcount;
                    }
                }
            }

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Vendor Management
        <small>Add, Edit</small>
      </h1>
    </section>
    
    <section class="content">
        <?php if($this->session->flashdata('success') != '')
{  ?>
  <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
<?php }?>
        <div class="row">
            <?php if($role=='3'){ ?>
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewVendor"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
            <?php } ?>
            <div class="tab-content">
                <ul class="nav nav-tabs">
                    <?php if($role=='3'){?>
                    <li class="<?php echo $pendingClass;?>"><a href="<?php echo base_url(); ?>adVendor/0" ><img src="<?php echo base_url(); ?>assets/dist/img/green.png"><strong>Pending (<?php echo $pendingCount;?>)</strong></a>
                    </li>
                    <?php } ?>
                    <?php if($role!='4'){ ?>
                    <li class="<?php echo $submittedClass;?>"><a href="<?php echo base_url(); ?>adVendor/1" ><img src="<?php echo base_url(); ?>assets/dist/img/orange.png">
                    <strong><?php if($role=='3'){ echo 'Submitted';}else if($role=='2'){ echo 'Pending';}?>
                    (<?php echo $submittedCount;?>)</strong></a>
                    </li>
                    <?php } ?>
                    <li class="<?php echo $approvedClass;?>"><a href="<?php echo base_url(); ?>adVendor/2" ><img src="<?php echo base_url(); ?>assets/dist/img/green.png"><strong>
                    <?php if($role=='4'){ echo 'Pending';}else { echo 'Approved';} ?> 
                     (<?php echo $approvedCount;?>)</strong></a>
                    </li>
                    <li class="<?php echo $rejectedClass;?>"><a  href="<?php echo base_url(); ?>adVendor/3" ><img src="<?php echo base_url(); ?>assets/dist/img/red.png"><strong>Rejected (<?php echo $rejectedCount;?>)</strong></a>
                    </li>
                    <li class="<?php echo $financeApprovedClass;?>"><a href="<?php echo base_url(); ?>adVendor/4" ><img src="<?php echo base_url(); ?>assets/dist/img/green.png"><strong>Finance Approved (<?php echo $financeApprovedCount;?>)</strong></a>
                    </li>
                </ul>
                
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Vendor List</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table id='empTable' class='display dataTable' width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Beneficiary Name</th>
                            <!-- <th>Account No.</th>
                            <th>Bank Name</th>
                            <th>Branch</th>
                            <th>City</th>
                            <th>IFSC</th>
                            <th>Pan NO.</th>
                            <th>GST No.</th> -->
                            <th>Created On</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                </table>
                  
                </div><!-- /.box-body -->
                
              </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#empTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
              'url':'<?=base_url()?>/advance/vendorList/<?=$type?>'
            },
            'columns': [
               { data: 'id' },
                { data: 'vendorName' },
                { data: 'address' },
                { data: 'contactNo' },
                { data: 'email' },
                { data: 'beneficiaryName' },
                { data: 'createdDtm' },
                { data: 'link', "searchable":false }
            ]
        });
    });
    </script>
    <script>
        function pushApprove(id,status){
            var confirmMsg = confirm('Are you sure, you want to assign this advance to checker?');
            if(confirmMsg==true){
                $("#pushButton").hide();
                $.ajax({
                    url: '<?=base_url()?>vendorajaxapprove',
                    data:{"id":id,'status':0},
                    type: 'POST',
                    dataType: 'json',
                    success: function(response){
                        location.href="<?=base_url()?>adVendor";
                    },
                    error: function(){ alert('Failed');}
                });
            }
        }

        function deleteUser(id,status){
            var confirmMsg = confirm('Are you sure, you want to delete this User?');
            if(confirmMsg==true){
                $("#pushButton").hide();
                $.ajax({
                    url: '<?=base_url()?>deleteVendor',
                    data:{"id":id,'status':0},
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
