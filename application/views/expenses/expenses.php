
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<style>
    .dt-buttons{
        margin-left: 9px !important;
    }
</style>
<?php

$role = $this->session->userdata('role');
$empRole = $this->session->userdata('empRole');
$userarray =  explode(',', $empRole);
//print_r($userarray); exit;
/* 3 => maker
2 => checker
4 => account */
$pendingClass='unactive';$approvedClass='unactive';$submittedClass='unactive';$rejectedClass='unactive'; $financeApprovedClass='';
            $rejectedCount=0;
            $approvedCount=0;
            $submittedCount=0;
            $pendingCount=$pendingCountt;
            $financeApprovedCount=0;
            if(!empty($expensesTotal)){
                foreach($expensesTotal as $countVal){
                    // if($countVal->approved==0){
                    //     $pendingCount=$countVal->allcount;
                    // }
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
        <i class="fa fa-users"></i> Expenses Management
        <small>Add, Edit</small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <?php if(in_array(2, $userarray)){ ?>
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewExpenses"><i class="fa fa-plus"></i> Add New Expenses</a>
                </div>
            </div>
            <?php } ?>
            <div class="tab-content">
                <ul class="nav nav-tabs">
                    <?php if(in_array(2, $userarray)){?>
                    <li class="<?php echo $pendingClass;?>"><a href="<?php echo base_url(); ?>exListing/0" ><img src="<?php echo base_url(); ?>assets/dist/img/green.png"><strong>Pending Maker (<?php echo $pendingCount;?>)</strong></a>
                    </li>
                    <?php } ?>
                    <?php if(in_array(2, $userarray) || in_array(3, $userarray)){ ?>
                    <li class="<?php echo $submittedClass;?>"><a href="<?php echo base_url(); ?>exListing/1" ><img src="<?php echo base_url(); ?>assets/dist/img/orange.png">
                    <strong>Pending Checker
                    (<?php echo $submittedCount;?>)</strong></a>
                    </li>
                    <?php } ?>
                    <li class="<?php echo $approvedClass;?>"><a href="<?php echo base_url(); ?>exListing/2" ><img src="<?php echo base_url(); ?>assets/dist/img/green.png"><strong>
                    <?php if(in_array(4, $userarray)){ echo ' Finance Pending';}else { echo 'Finance Pending';} ?> 
                     (<?php echo $approvedCount;?>)</strong></a>
                    </li>
                    <li class="<?php echo $rejectedClass;?>"><a  href="<?php echo base_url(); ?>exListing/3" ><img src="<?php echo base_url(); ?>assets/dist/img/red.png"><strong>Rejected (<?php echo $rejectedCount;?>)</strong></a>
                    </li>
                    <li class="<?php echo $financeApprovedClass;?>"><a href="<?php echo base_url(); ?>exListing/4" ><img src="<?php echo base_url(); ?>assets/dist/img/green.png"><strong>Finance Approved (<?php echo $financeApprovedCount;?>)</strong></a>
                    </li>
                </ul>
                
                <!-- <ul class="nav nav-tabs">
                     <?php if(in_array(2, $userarray)){?>
                    <li class="<?php echo $pendingClass;?>"><a href="<?php echo base_url(); ?>exListing/0" ><img src="<?php echo base_url(); ?>assets/dist/img/green.png"><strong>Pending (<?php echo $pendingCount;?>)</strong></a>
                    </li>
                    <?php } ?>
                    <?php if(in_array(2, $userarray) || in_array(3, $userarray)){ ?>
                    <li class="<?php echo $submittedClass;?>"><a href="<?php echo base_url(); ?>exListing/1" ><img src="<?php echo base_url(); ?>assets/dist/img/orange.png">
                    <strong><?php if(in_array(3, $userarray)){ echo 'Submitted';}else if(in_array(2, $userarray)){ echo 'Pending';}?>
                    (<?php echo $submittedCount;?>)</strong></a>
                    </li>
                    <?php } ?>
                    <li class="<?php echo $approvedClass;?>"><a href="<?php echo base_url(); ?>exListing/2" ><img src="<?php echo base_url(); ?>assets/dist/img/green.png"><strong>
                    <?php if(in_array(4, $userarray)){ echo 'Pending';}else { echo 'Approved';} ?> 
                     (<?php echo $approvedCount;?>)</strong></a>
                    </li>
                    <li class="<?php echo $rejectedClass;?>"><a  href="<?php echo base_url(); ?>exListing/3" ><img src="<?php echo base_url(); ?>assets/dist/img/red.png"><strong>Rejected (<?php echo $rejectedCount;?>)</strong></a>
                    </li>
                    <li class="<?php echo $financeApprovedClass;?>"><a href="<?php echo base_url(); ?>exListing/4" ><img src="<?php echo base_url(); ?>assets/dist/img/green.png"><strong>Finance Approved (<?php echo $financeApprovedCount;?>)</strong></a>
                    </li>
                </ul> -->
                
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Expenses List <small><span class="error">* After Assign Please download pdf and send to manager with supporting bills.<span></small></h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table id='empTable' class='display dataTable' width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Employee Code</th>
                            <th>Name</th>
                            <th>Month</th>
                            <th>A/C No.</th>
                            <th>Request Amount</th>
                            <th>Approved Amount</th>
                            <th>Bill Date</th>
                            <th>Maker Date</th>
                            <th>Checker Approve Date</th>
                            <th>Finance Approve Date</th>
                            <th>Courier Received Date</th>
                            <th>Courier Track Number</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                </table>
                  
                </div><!-- /.box-body -->
                
              </div><!-- /.box -->
            </div>
        </div>
    </section>
    <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
       Modal content
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update Courier Date & Track Number</h4>
        </div>
        <div class="modal-body">
            <div class="col-md-12">
                <div class="col-md-6">
                    <label>Courier Track Number</label>
                    <input type="text" name="courierNo" id="courierNo" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label>Courier Date</label>
                    <input type="date" name="courierdate" id="courierdate" class="form-control" required>
                </div>
            </div>
            <input type="hidden" name="id" id="iddata" value="" class="form-control">
        </div><br><br>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="updatecourierdates()" data-dismiss="modal">Update</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>-->
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<!-- <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
<link href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js" charset="utf-8"></script>-->
<script type="text/javascript">
    $(document).ready(function(){
        $('#empTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            "lengthMenu": [[10, 25, 50, 1000], [10, 25, 50, 1000]],
            dom: 'lBfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
            'ajax': {
              'url':'<?=base_url()?>/expenses/expensesList/<?=$type?>'
            },
            'columns': [
                { data: 'id' },
                { data: 'empCode' },
                { data: 'name' },
                { data: 'month' },
                { data: 'acNum' },
                { data: 'makerAmount' },
                { data: 'checkerAmount' },
                { data: 'entry_date' },
                { data: 'createdDtm' },
                { data: 'checker_approved_date' },
                { data: 'finance_approved_date' },
                { data: 'Courier_date' },
                { data: 'Courier_no' },
                { data: 'link', "searchable":false }
            ]
        });
    });
    </script>
    <script>
        function pushApprove(id,status){
            if(status==0){ $res = 'checker';}else{ $res = 'Account';}
            var confirmMsg = confirm('Are you sure, you want to assign this expenses to '+$res+' ?');
            if(confirmMsg==true){
                $("#pushButton").hide();
                $.ajax({
                    url: '<?=base_url()?>expenses/ajaxapprove',
                    data:{"id":id,'status':status},
                    type: 'POST',
                    dataType: 'json',
                    success: function(response){
                        //alert(response);
                        location.href="<?=base_url()?>exListing";
                    },
                    error: function(){ alert('Failed');}
                });
            }
        }

function updatecourierdates(){
    var getid= $('#iddata').val();
    var getcourierNo= $('#courierNo').val();
    var getdate= $('#courierdate').val();
    if(getdate !='' && getcourierNo !='' && getid !=''){
        $.ajax({
            url:'<?=base_url()?>expenses/updatecourier',
            data:{id:getid,getdate:getdate,courierNum:getcourierNo},
            type:'POST',
            datetype:'json',
            success: function(response){
                location.href="<?=base_url()?>exListing/<?=$type?>";
            },
            error: function(){ alert('Failed');}
        })
    }else{
        alert('Fill The Courier Date & Track Number!');
    }
}

function func(btnId){
   document.getElementById('iddata').value = $(btnId).attr('data');
}

    
    jQuery(document).on("click", ".deleteExpense", function(){
        
        var expenseId = $(this).data("expenseid"),
            hitURL = baseURL + "deleteExpense",
            currentRow = $(this);

        alert(hitURL);    
        
        var confirmation = confirm("Are you sure to delete this expense ?");
        
        if(confirmation)
        {
            jQuery.ajax({
            type : "POST",
            dataType : "json",
            url : hitURL,
            data : { expenseId : expenseId } 
            }).done(function(data){
                console.log(data);
                currentRow.parents('tr').remove();
                if(data.status = true) { alert("Expense successfully deleted"); }
                else if(data.status = false) { alert("Expense deletion failed"); }
                else { alert("Access denied..!"); }
            });
        }
    });    

</script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js" type="text/javascript"></script>
