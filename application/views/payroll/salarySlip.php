<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Salary Slip's
        <small>Salary Slip</small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
          <form action="<?php echo base_url() ?>payroll/index" method="POST" id="searchList">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group">
              <div class="input-group">
                <!-- <input id="fromDate" type="text" name="fromDate" value="<?php //echo $fromDate; ?>" class="form-control datepicker" placeholder="From Date" autocomplete="off" />
                  <span class="input-group-addon"><label for="fromDate"><i class="fa fa-calendar"></i></label></span>
 -->
                <select name="month" class="form-control">
                  <option value="0">Select Month</option>
                  <?php
                      if(!empty($months)){
                          foreach ($months as $k=>$m)
                          {
                          ?>
                            <option value="<?php echo $k ?>" <?php if($k==$month){echo "selected=selected";}?>><?php echo $m ?></option>
                          <?php
                        }
                      }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group">
              <div class="input-group">
                <select name="year" class="form-control">
                  <option value="0">Select Year</option>
                  <?php
                      if(!empty($years)){
                          foreach ($years as $y)
                          {
                          ?>
                            <option value="<?php echo $y ?>" <?php if($y==$year){echo "selected=selected";}?>><?php echo $y ?></option>
                          <?php
                        }
                      }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-6 col-xs-6 form-group">
              <button type="submit" class="btn btn-md btn-primary btn-block searchList pull-right"><i class="fa fa-search" aria-hidden="true"></i></button> 
            </div>
            <div class="col-lg-1 col-md-1 col-sm-6 col-xs-6 form-group">
              <button class="btn btn-md btn-default btn-block pull-right resetFilters"><i class="fa fa-refresh" aria-hidden="true"></i></button>
            </div>
          </form>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= !empty($userInfo) ? $userInfo->name." : ".$userInfo->email : "All users" ?></h3>
                    <div class="box-tools">
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>Month</th>
                      <th>Action</th>
                    </tr>
                    <?php
                    if(!empty($userRecords))
                    {
                        foreach($userRecords as $record)
                        {
                    ?>
                    <tr>
                      <td><?php echo $months[$record->month];?></td>
                      <td><a href="<?php echo base_url().'payroll/download/'.$record->id; ?>" class="btn btn-primary">Download</a></td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                  </table>
                  
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                  
                </div>
              </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();            
            var link = jQuery(this).get(0).href;
            jQuery("#searchList").attr("action", link);
            jQuery("#searchList").submit();
        });

        jQuery('.datepicker').datepicker({
          autoclose: true,
          format : "dd-mm-yyyy"
        });
        jQuery('.resetFilters').click(function(){
          $(this).closest('form').find("input[type=text]").val("");
        })
    });
</script>
