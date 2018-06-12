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
            <?php
            if (isset($applyFilter) && $applyFilter>0)
            {

                ?>
<br/>
                <div class="pull-center alert alert-success"  style="width: 98%; margin-left: 10px">
                    <strong>Success!</strong> Filter a successful add. <b>Filtered <a href="?rule_id=<?=$rule_id;?>"><?=$applyFilter?> records</a></b>
                </div>
            <?php
            }
            ?>
            <form id="searchForm" action="index.php?op=bugs_filter" class="dashed-section-inner form-horizontal" method="post">
                <input type="hidden" name="op" value="bugs_filter" />
                <h4>Add Filer</h4>
                <div class="row">
                    <div class="col-sm-8"  >
                        <input id="Filter" name="FilterAdd" class="form-control" placeholder="Enter Filter string"
                                                 value="<?= @$filter['searchField'] ?>">
                    </div>



                    <div class="col-sm-3"> <input type="submit" name="saveFilter" class="btn btn-primary" value="Add"  /></div>

                    <div><br></div>
                </div>

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

    <table id="info_tbl" class="table" width="100%" cellspacing="0">
        <thead>
        <tr>

            <th width="5%">Id</th>
            <th width="70%">Filter</th>

            <th width="5%" colspan="2"></th>

        </tr>
        </thead>
        <tbody id="rowContainer"></tbody>

    </table>
    <nav aria-label="Page navigation">
        <ul class="pagination" id="pagination2"></ul>
    </nav>
    <script id="rowTable" type="text/x-jQuery-tmpl">
            <tr   style="border:1px solid #000">
                 <td>${id}</td>
                 <td align="ledt">
<a href="?rule_id=${id}">${rule}</a>

                </td>

                 <td>
                    <a   class="btn " onClick="Remove(${id});return false;"   >
                 <span class="glyphicon glyphicon-remove" title="remove Rule"></span> </a>
                 </td>
             </tr>


    </script>

</section>


<div id="showLoad" style="display: hidden;   position: fixed;bottom: -15px;width: 100%; text-align: center "   class="alert alert-info">
        <strong>Info!</strong> Loading info.
    </div>
<script>

var pages=1;


function Remove(id){

   if (!confirm('Are you sure?')) return false;
    $.get("?op=is-remove-filter&json=1&id=" + id, function (data) {
        $("#rowContainer").html('');
        LoadTabe(pages);

    });
        return true;

}

function ShowPages(pagesTotal){
    $('#pagination,#pagination2').twbsPagination({
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
    $.getJSON("index.php", {"json": 1, 'op': 'bugs_filter',"page":page})
        .done(function (json) {
            $('#paging_info').html('Total:' + json.recordsFiltered);
            $("#rowTable").tmpl(json.data).appendTo("#rowContainer");
            $('#showLoad').hide();
          if (json.TotalPages ){
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
                LoadTabe(pages+1 );
                pages=pages+1


            }
        });

    });
</script>