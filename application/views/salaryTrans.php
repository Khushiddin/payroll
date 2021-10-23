<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Salary Transactions
        <!-- <small>Add, Edit, Delete</small> -->
      </h1>
    </section>
    <section class="content">
        <div class="row" style="color: #1aa21a;padding-left: 15px;">
            <?php print_r($this->session->flashdata('uploadSuccess')); ?>
            <!-- <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNew"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div> -->
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box" style="overflow-x: scroll;
                        width: auto;
                        white-space: nowrap;">
                <div class="box-header" style="height: 150px;">
                    <h3 class="box-title" style="margin-bottom: 40px;">Upload Salary Transactions</h3>
                    <div  style="margin-top: auto;top: auto;margin-right: 986px;">

                        <form method="POST" action="<?php echo base_url() ?>uploadCsv" id="csvForm" enctype="multipart/form-data">
                            <input type="file" name="userfile" required="" style="margin-bottom: 10px;">

                            <input type="submit" name="submit" value="Upload" >
                            <a href="<?php echo base_url(); ?>assets/salaryformat.csv" class="btn btn-warning" download="">Sample Download</a>
                        </form> 
                       
                        
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  
                  
                </div><!-- /.box-body -->
               
              </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
   function abc()
   {

    var date = $("#date").val();
    console.log(date);
var CurrentDate = new Date($('#date').val());
console.log("Current date:", CurrentDate);
CurrentDate.setMonth(CurrentDate.getMonth() + 6);
 
 var dd = CurrentDate.getDate();
var mm = CurrentDate.getMonth() + 1; 

var yyyy = CurrentDate.getFullYear();
var today = yyyy + '-' + ("0" + (mm )).slice(-2) + '-' + ("0" + (dd )).slice(-2);


alert(today);
$('#f_date').val(today);     

   }
</script>
