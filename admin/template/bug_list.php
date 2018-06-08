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
        <div class="pagination pull-right">
            <span id="prev_page" class="icon-arrow-left"></span>
            <span id="paging_info"></span>
            <span id="next_page" class="icon-arrow-right"></span>
        </div>

        <div class="absolute-center">
            <h3 class="text-center">Bug List</h3>
        </div>
    </div>
    <div class="fluid-height-center">
        <div class="dashed-section">
            <form id="searchForm" action="index.php" class="dashed-section-inner form-horizontal">

                <h4>Search</h4>

                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">

                            <div class="col-xs-7">
                                <input id="searchField" name="searchField" class="form-control" placeholder="Search"
                                       value="<?= @$filter['searchField'] ?>">
                            </div>
                            <div class="col-xs-5">
                                <input id="rev_id" name="rev_id" class="form-control" placeholder="Rev ID"
                                       value="<?= @$filter['rev_id'] ?>">
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-4">
                        <div >
                            <label class="sr-only">Host</label>
                            <div class="col-xs-6">
                                <select id="typeCode" name="host" class="form-control select"
                                        data-placeholder="select Host" placeholder="select Host"
                                        style="width: 100%">
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
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div >
                            <label class="sr-only">Type Code</label>
                            <div class="col-xs-5">
                                <select id="typeCode" name="typeCode" class="form-control select"
                                        data-placeholder="Type"
                                        style="width: 100%">
                                    <option value="-">All</option>
                                    <?php
                                    $listType = array('' => 'None', 'php' => 'PHP', 'swf' => 'SWF');
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
                        </div>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Search"  />

                </div>
            </form>
        </div>

    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination" id="pagination"></ul>
    </nav>

    <table id="info_tbl" class="table  " width="100%" cellspacing="0">
        <thead>
        <tr>

            <th width="15%">Last Seen</th>
            <th width="8%">Rev ID</th>
            <th width="5%">Type</th>
            <th width="10%">Bugs Total</th>
            <th width="10%">Host</th>
            <th width="15%">Resolved Date</th>
            <th width="1%" colspan="2"></th>

        </tr>
        </thead>
        <tbody id="rowContainer"></tbody>

    </table>
    <script id="rowTable" type="text/x-jQuery-tmpl">
            <tr  {{if is_red==1 && resolved==1}} class="red"   {{/if}}  {{if  resolved==1}} class="green"   {{/if}} style="border:1px solid #000">
                 <td>${las_seen}</td>
                 <td align="center">

                    {{if rev_id }}
                   <a href="https://awerycloud.atlassian.net/browse/${rev_id}" target="_blank">${rev_id}</a>
                    {{/if}}
  <a class="setRevId" onclick="setRevId(${id},'${rev_id}');" title="Set Rev ID"><span class="glyphicon glyphicon-pencil"></span></a>
                </td>
                 <td>${bug_type}</td>
                 <td align="center">${bugs_cnt}</td>
                 <td>${last_host}</td>
                 <td>${resolved_date}</td>
                 <td align="rigth" >
                  {{if resolved==0 }}
                        <a class="btn isResolve" onclick="isResolved(${id});">Is Resolved</a>
                    {{/if}}
                     {{if resolved==1 }}
                         Resolved!
                    {{/if}}
                 </td>
                 <td>
                    <a href="?op=bugs_details&id=${id}" class="btn" >
                 <span class="icon-arrow-forward"></span> &nbsp; </a>
                 </td>
             </tr>
             <tr  {{if is_red==1 && resolved==1}} class="red"   {{/if}}  {{if  resolved==1}} class="green"   {{/if}}  style="font-size:10px">
                <td colspan="8">
                <div>Module: ${bug_name} </div>

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
<script>

var pages=1;
function saveRevId() {
    var rev_id=$('#rev_id_save').val();
    var id=$('#save_rev_id').val();
    $.get("?op=is-set-rev&json=1&id=" + id+'&rev_id='+rev_id, function (data) {
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
                LoadTabe(pages);

            });
        }
    }
    function showFull(id) {
        $('#error_'+id).load('?op=is-full-error&json=1&id=' + id);

    }
function ShowPages(pagesTotal){
    $('#pagination').twbsPagination({
        totalPages: pagesTotal,
        visiblePages: 7,
        onPageClick: function (event, page) {
            $('#page-content').text('Page ' + page);
            pages=page;
            $("#rowContainer").html('');
           LoadTabe(page);
        }
    });
}


function LoadTabe(page){
    $.getJSON("index.php", {"json": 1, 'op': 'bugs_list',"page":page})
        .done(function (json) {
            $('#paging_info').html('Total:' + json.recordsFiltered);
            $("#rowTable").tmpl(json.data).appendTo("#rowContainer");
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


    });
</script>