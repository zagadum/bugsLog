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
    <div class="fluid-height-center">

        <div class="dashed-section">
            <form id="searchForm" action="index.php" class="dashed-section-inner form-horizontal">

                <h4>Search</h4>
                <div class="row">
                    <div class="col-sm-3" style="width: 12%">
                        <input id="searchField" name="searchField" class="form-control" placeholder="Search"
                                                 value="<?= @$filter['searchField'] ?>">
                    </div>
                    <div class="col-sm-3" style="width: 12%">
                    <input id="bug_name" name="bug_name" class="form-control" placeholder=" Search Module"
                           value="<?= @$filter['bug_name'] ?>">
                    </div>
                    <div class="col-sm-3" style="width: 12%">
                        <input id="bug_users" name="bug_users" class="form-control" placeholder=" Search User"
                               value="<?= @$filter['bug_users'] ?>">
                    </div>
                    <div class="col-sm-2" style="width: 10%"> <input id="rev_id" name="rev_id" class="form-control" placeholder="Rev ID"
                        value="<?= @$filter['rev_id'] ?>"></div>
                    <div class="col-sm-3" style="width: 17%">
                        <select id="typeCode" name="host" class=" form-control  "    data-show-subtext="true" data-live-search="true"
                                data-placeholder="select Host" placeholder="select Host"
                                style="width: 100%;  ">
                            <option value="-">All</option>
                            <?php foreach ($hostList as $id => $host) :
                                $sel = '';
                                if ($filter['host'] == $host) {
                                    $sel = 'selected="selected"';
                                }
                                ?>
                                <option value="<?= $host ?>" <?= $sel ?>><?= $host; ?></option>

                            <?php endforeach; ?>
                        </select>

                    </div>
                    <div class="col-sm-2"  style="width: 10%">
                        <select id="typeCode" name="typeCode" class="form-control select"
                                data-placeholder="Type"
                                 >
                            <option value="-">All</option>
                            <?php
                            $listType = array('' => 'None', 'php' => 'PHP', 'swf' => 'SWF','pos'=>'POS', 'ingore'=>'show ignore','is_arhive'=>'show arhive');
                            foreach ($listType as $k => $val) {
                                $sel = '';
                                if ($filter['bug_type'] == $k) {
                                    $sel = 'selected="selected"';
                                }
                                print ' <option value="' . $k . '" ' . $sel . '>' . $val . '</option>';
                            }
                            ?>


                        </select>

                    </div>
                    <div class="col-sm-3"> <input type="submit" class="btn btn-primary" value="Search"  /></div>

                    <div><br></div>
                </div>
                <?php
                 if (isset($filter['rule_id']) && $filter['rule_id']>0){
                     print '<div class="red">the filter includes ignored records</div>';
                 }

                ?>
               <div class="pull-right"><a href="index.php?bug_name=&typeCode=&host=&rev_id=&rule_id=&bug_users=" >Reset Filter</a></div>
            </form>
        </div>

    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination" id="pagination"></ul>
         <span id="paging_info" style="    margin-top: 14px;
    position: relative;
    display: block;
    float: right; right: 40px;
"></span>
     </nav>

    <table id="info_tbl" class="table" width="100%" cellspacing="0" >
        <thead>
        <tr>
            <th width="15%" ><a href="" onclick="setSort('las_seen','las_seen');return false">Last Seen&nbsp;<span id="las_seen" data-id="<?=@$sort['las_seen'];?>"  </span></a></th>
            <th width="5%">Rev ID</th>
            <th width="5%">Type</th>
            <th width="11%">User</th>
            <th width="7%"><a href="" onclick="setSort('total_ico','total');return false">Total&nbsp;<span id="total_ico" data-id="<?=@$sort['total'];?>"  </span></a></th>
            <th width="40%">Host</th>
            <!-- <th width="15%">Resolved Date</th> -->
            <th width="15%" colspan="2"></th>

        </tr>
        </thead>
        <tbody id="rowContainer"></tbody>

    </table>
    <nav aria-label="Page navigation">
        <ul class="pagination" id="pagination2"></ul>
    </nav>
    <script id="rowTable" type="text/x-jQuery-tmpl">
            <tr  {{if is_red==1 && resolved==1}} class="red"   {{/if}}  {{if  resolved==1}} class="green"   {{/if}} style="border:1px solid #000" id="tr_${id}">
                 <td><a href="?op=bugs_details&id=${id}" >${las_seen}</a></td>
                 <td align="center">

                    {{if rev_id }}
                   <a href="https://awerycloud.atlassian.net/browse/${rev_id}" target="_blank">${rev_id}</a>
                    {{/if}}
  <a class="setRevId" onclick="setRevId(${id},'${rev_id}');" title="Set Rev ID"><span class="glyphicon glyphicon-pencil"></span></a>
                </td>
                 <td align="center"><a href="?typeCode=${bug_type}" Title="Filtred by ${bug_type}">${bug_type}</a></td>
                 <td align="left" style="word-break: break-all"><a href="?bug_users=${bug_users}" Title="Filtred by ${bug_users}">${bug_users}</a></td>
                 <td align="center">${bugs_cnt}</td>
                 <td><a href="?host=${last_host}" Title="Filtred by ${last_host}">${last_host}</a></td>

                 <td align="rigth" style="wrap:nowrap" >
				    {{if resolved==0 }}
                        <a class="btn isResolve" onclick="isResolved(${id});" style="display:inline"><span class="glyphicon glyphicon-thumbs-up" title="Is Resolved" style="color: #337ab7;"></a>

                    {{/if}}
					<a class="btn " onclick="isArhive(${id});" style="display:inline" title="Archive"><span class="glyphicon glyphicon-briefcase" title="Is Archive" style="color: #337ab7;"></a>
						 
						<a class="btn " onclick="isIgnore(${id});" style="display:inline" title="Ignore"><span class="glyphicon  glyphicon-ban-circle" title="set Ignore" style="color: #337ab7;"></a>
						 
					<a class="btn " onclick="isDelete(${id});" style="display:inline" title="Remove"><span class="glyphicon glyphicon-remove" title="Is Resolved" style="color: #337ab7;"></a>
                    
					 
                     {{if resolved==1 }}
                         <span style="font-size:8px;display:block">Resolved!   ${resolved_date} </span>
                    {{/if}}
					
                      <a href="?op=bugs_details&id=${id}" class="btn" style="font-size:10px">more
                 <span class="icon-arrow-forward"></span> &nbsp; </a>
                 </td>

             </tr>
             <tr  {{if is_red==1 && resolved==1}} class="red"   {{/if}}  {{if  resolved==1}} class="green"   {{/if}}  style="font-size:10px">
                <td colspan="9">
                <div>Module: <a href="?bug_name=${bug_name}" Title="Filtred by ${bug_name}">${bug_name}</a> </div>
by
                 <pre style="font-size:10px; " {{if  resolved==1}} class="green"   {{/if}}   id="error_${id}">
                  ${error_text}
<div style="float:right"><a href="#" onClick="showFull(${id});return false;" >Show Full Error</a></div>
                 </pre>

             </td>
            </tr>

    </script>

</section>

<div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="saveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enter Rev Id From </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form >
                    <input type="hidden" id="save_rev_id" value="">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Rev Id:</label>
                        <input type="text" class="form-control" id="rev_id_save" value="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveRevId()">Save changes</button>

            </div>
        </div>
    </div>
</div>
<div id="showLoad" style="display: hidden;   position: fixed;bottom: -15px;width: 100%; text-align: center "   class="alert alert-info">
        <strong>Info!</strong> Loading info.
    </div>
<script>

var pages=1;
var params= {};
function saveRevId() {
    var rev_id=$('#rev_id_save').val();
    var id=$('#save_rev_id').val();
    $.get("?op=is-set-rev&json=1&id=" + id+'&rev_id_save='+rev_id, function (data) {
        LoadTabe(pages);
        $('#saveModal').modal('hide');
    });
}

function setRevId(id,rev_id) {

    $('#rev_id_save').val(rev_id);
    $('#save_rev_id').val(id);
    $('#saveModal').modal('show');
}
    function isResolved(id) {

        if (confirm("is Resolved?")) {
            $.get("?op=is-resolve&json=1&id=" + id, function (data) {
                LoadTabe(pages,params);

            });
        }
    }
	
    function isDelete(id) {

        if (confirm("is Delete?")) {
            $.get("?op=is-delete&json=1&id=" + id, function (data) {
                //LoadTabe(pages,params);
				$('#tr_'+id).hide();

            });
        }
    }
	
	 function isIgnore(id) {

        if (confirm("set Ignore?")) {
            $.get("?op=is-ignore&json=1&id=" + id, function (data) {
               
				$('#tr_'+id).hide();

            });
        }
    }
	
	    function isArhive(id) {

        if (confirm("is Archive?")) {
            $.get("?op=is-arhive&json=1&id=" + id, function (data) {
                //LoadTabe(pages,params);
				$('#tr_'+id).hide();

            });
        }
    }
	
    function showFull(id) {
        $('#error_'+id).load('?op=is-full-error&json=1&id=' + id);

    }
function setSort(id,fields){

    var vals=$('#'+id).attr("data-id");
	 
	pages=1;
  if (vals=='asc'){
      $('#'+id).attr("data-id",'desc');
      vals='desc';
	  
  }else{
	  
	  if (vals=='desc'){ 
			$('#'+id).attr("data-id",'');
		   vals='';
	  }else{
		   if (vals==''){ 
			$('#'+id).attr("data-id",'asc');
			vals='asc';
			}
	  }
  }
  
 
    setIco(id);
    params[fields]=vals;
    $("#rowContainer").html('');
    LoadTabe(pages,params);
}
function setIco(id){
    var vals=$('#'+id).attr("data-id");
    $('#'+id).removeClass('glyphicon glyphicon-chevron-up');
    $('#'+id).removeClass('glyphicon glyphicon-chevron-down');
    if (vals=='asc'){
        $('#'+id).addClass('glyphicon glyphicon-chevron-up');
    }
	if (vals=='desc'){
        $('#'+id).addClass('glyphicon glyphicon-chevron-down');
      }
}
setIco('total_ico');
function ShowPages(pagesTotal){
    $('#pagination,#pagination2').twbsPagination({
        totalPages: pagesTotal,
        visiblePages: 7,
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
            pages=page;
            $("#rowContainer").html('');
           LoadTabe(pages,params);
        }
    });
}


function LoadTabe(page,params){
    $.getJSON("index.php", {"json": 1, 'op': 'bugs_list',"page":page,"params":params})
        .done(function (json) {
            $('#paging_info').html('Total:' + json.recordsFiltered);
            $("#rowTable").tmpl(json.data).appendTo("#rowContainer");
            $('#showLoad').hide();
			 
          if (json.TotalPages){
              ShowPages(json.TotalPages);
          }

        })
        .fail(function (jqxhr, textStatus, error) {
            var err = textStatus + ", " + error;
            console.log("Request Failed: " + err);
        });
}
    $(document).ready(function () {

        LoadTabe(1);
        $(window).scroll(function() { //detact scroll
            if($(window).scrollTop() + $(window).height() >= $(document).height()){ //scrolled to bottom of the page
                //contents(el, settings); //load content chunk
                $('#showLoad').show();
                LoadTabe(pages+1 ,params);
                pages=pages+1;
              // $('#pagination,#pagination2').data('currentPage', pages);
             //   $('#pagination,#pagination2').twbsPagination('destroy');

            }
        });
 
  $('#typeCode').selectpicker();
 
    });
</script>