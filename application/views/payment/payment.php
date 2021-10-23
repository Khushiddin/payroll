<link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<?php

$role = $this->session->userdata('role');
$empRole = $this->session->userdata('empRole');
$userarray =  explode(',', $empRole);
/* 3 => maker
2 => checker
4 => account */
$pendingClass='unactive';$approvedClass='unactive';$submittedClass='unactive';$rejectedClass='unactive'; $financeApprovedClass='';
            $rejectedCount=0;
            $approvedCount=0;
            $submittedCount=0;
            $pendingCount=0;
            $financeApprovedCount=0;
            if(!empty($paymentTotal)){
                foreach($paymentTotal as $countVal){
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
        <i class="fa fa-users"></i> Vendor Payment Management
        <small>Add, Edit</small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <?php if(in_array(2, $userarray)){ ?>
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewPayment"><i class="fa fa-plus"></i> Add New Payment</a>
                    <?php } ?>
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>adVendor"><i class="fa fa-plus"></i> Add New Vendor</a>
                </div>
            </div>
            <?php  $myarray = array('2','3');?>
            <div class="tab-content">
                <ul class="nav nav-tabs">
                    <?php if(in_array(2, $userarray)){?>
                    <li class="<?php echo $pendingClass;?>"><a href="<?php echo base_url(); ?>paymentListing/0" ><img src="<?php echo base_url(); ?>assets/dist/img/green.png"><strong>Maker Pending (<?php echo $pendingCount;?>)</strong></a>
                    </li>
                    <?php } ?>
                    <?php if(array_intersect($myarray, $userarray)){ ?>
                    <li class="<?php echo $submittedClass;?>"><a href="<?php echo base_url(); ?>paymentListing/1" ><img src="<?php echo base_url(); ?>assets/dist/img/orange.png">
                    <strong><?php if(in_array(2, $userarray)){ echo 'Checker Pending';}else if(in_array(3, $userarray)){ echo 'Checker Pending';}?>
                    (<?php echo $submittedCount;?>)</strong></a>
                    </li>
                    <?php } ?>
                    <li class="<?php echo $approvedClass;?>"><a href="<?php echo base_url(); ?>paymentListing/2" ><img src="<?php echo base_url(); ?>assets/dist/img/green.png"><strong>
                    <?php if(in_array(4, $userarray)){ echo 'Finance Pending';}else { echo 'Finance Pending';} ?> 
                     (<?php echo $approvedCount;?>)</strong></a>
                    </li>
                    <li class="<?php echo $rejectedClass;?>"><a  href="<?php echo base_url(); ?>paymentListing/3" ><img src="<?php echo base_url(); ?>assets/dist/img/red.png"><strong>Rejected (<?php echo $rejectedCount;?>)</strong></a>
                    </li>
                    <li class="<?php echo $financeApprovedClass;?>"><a href="<?php echo base_url(); ?>paymentListing/4" ><img src="<?php echo base_url(); ?>assets/dist/img/green.png"><strong>Finance Approved (<?php echo $financeApprovedCount;?>)</strong></a>
                    </li>
                </ul>
                
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Vendor Payment List</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table id='empTable' class='display dataTable' width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Vendor Name</th>
                            <th>Maker Name</th>
                            <th>Request Amount</th>
                            <th>Approved Amount</th>
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
            dom: 'lBfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
            'ajax': {
              'url':'<?=base_url()?>/payment/paymentList/<?=$type?>'
            },
            'columns': [
                { data: 'id' },
                { data: 'vendorName' },
                { data: 'name' },
                { data: 'makerAmount' },
                { data: 'checkerAmount' },
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
                    url: '<?=base_url()?>payment/ajaxapprove',
                    data:{"id":id,'status':0},
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
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js" type="text/javascript"></script>