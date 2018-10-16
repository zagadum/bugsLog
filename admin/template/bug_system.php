<style>
    .red {
        background-color: pink !important;
    }

    .green {
        background-color: #c9e2b3 !important;
    }
</style>
<!-- Content -->
<section class="content">
    <div class="top-bar results clearfix">


</div>
 
		 <?php if (!empty($info)) :?>
<div id="showLoad" style="   width: 100%; text-align: center "   class="alert alert-info">
        <strong>Info!</strong>   <?=$info?>
    </div>
<?php endif;?>

		 <?php if (empty($info)) :?>
	<div class="center" style="text-align:center">
        <form action="?op=bugs_system" method="post">
		
		 <div class="form-group" >
		  Delete before date  
                <div class='input-group date' id='datetimepicker1' style="width:200px" />
                    <input type='text' class="form-control" name="crash_date"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
				 <input type="submit" name="crash_date_go" value="Remove before date "  class="btn btn-warning"/>
            </form>
			</div>
		
		
		
		<hr style="border:1px solid #ddd"/>
		<div class="center"><a href="?op=bugs_system&crash_all=1" onClick="return confirm('Remove All ?');" class="btn btn-danger"> Delete Full DB?</a></div>
		 
		 
	<?php endif;?>	
 </div>
</section>
	
	<script>
    $('#datetimepicker1 input').datepicker({ dateFormat: 'dd-mm-yy'
});
        </script>