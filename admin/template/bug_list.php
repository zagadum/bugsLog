
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

            <table id="info_tbl" class="table table-striped" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Id</th>

                    <th>Last Seen</th>
                    <th>Bug Name</th>
                    <th>Bugs Total</th>
                    <th>Last Host</th>
                    <th>Is Resolved</th>
                    <th>Resolved Date</th>
                    <th>Error Text</th>
                    <th></th>

                </tr>
                </thead>
            </table>

        </section>
      <script>
          $(document).ready(function(){

              var info_table = $('#info_tbl').dataTable({
                  "processing": true,
                  "serverSide": true,
                  "infoCallback": function( settings, start, end, max, total, pre ) {
                      $('#paging_info').text(start + ' - ' + end + ' OF ' + total);
                  },
                  "dom": '<"top"ip>rt<"bottom"><"clear">',
                  "ajax": {
                      "url": "index.php",
                      "type": "POST",
                      "data": { "search": " search_str ","json":1,'op':'bug_details' }
                  },
                  "columns": [
                      { data: "id" },

                      { data: "las_seen" },
                      { data: "bug_name" },
                      { data: "bugs_cnt" },
                      { data: "last_host" },
                      { data: "resolved" },
                      { data: "resolved_date" },
                      { data: "error_text" },

                      { data: "(opt2)", "class": "min-width" },
                  ],
                  "columnDefs": [
                      {
                          "targets": -1,
                          "data": "(opt2)",
                          "render": function(data, type, row) {
                              return '<a href="' + 'json/' + row['id']
                              + '" class="btn btn-secondary btn-sm" style="margin-left:18px;">Details</a>'
                              + '<a href="' + 'json' + '/' + row['id']
                              + '" class="btn btn-secondary btn-sm btn-icon" style="margin-left:18px;">'
                              + '<span class="icon-arrow-forward"></span></a>';
                          },
                      },
                      {
                          "targets": [ 0 ],
                          "visible": false,
                      }],
              });

              $('body #prev_page').click(function(e){
                  e.preventDefault();
                  info_table.fnPageChange('previous');
              });
              $('body #next_page').click(function(e){
                  e.preventDefault();
                  info_table.fnPageChange('next');
              });

          });
      </script>