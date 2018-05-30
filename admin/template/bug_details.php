
  <!-- Content -->
        <section class="content">
            <div class="top-bar results clearfix">
                <div class="pagination pull-right">
                    <span id="prev_page" class="icon-arrow-left"></span>
                    <span id="paging_info"></span>
                    <span id="next_page" class="icon-arrow-right"></span>
                </div>

                <div class="absolute-center">
                    <h3 class="text-center">Bug Details</h3>
                </div>
            </div>

            <table id="info_tbl" class="table table-striped" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Time Error</th>
                    <th>Host</th>
                    <th>User</th>
                    <th>CDN</th>
                    <th>Module</th>
                    <th>Sw ver</th>
                    <th>Opened Tab</th>
                    <th>Free memory</th>
                    <th>Used memory</th>
                    <th>Lang</th>
                    <th>OS</th>
                    <th>Screen</th>
                    <th>Fplayer</th>
                    <th>Local Time</th>
                    <th>Type Error</th>
                </tr>
                </thead>
            </table>

        </section>
      <script>
          $(document).ready(function(){

              var info_table = $('#info_tbl').dataTable({
                  "pageLength": 100,
                  "processing": true,
                  "serverSide": true,
                  "infoCallback": function( settings, start, end, max, total, pre ) {
                      $('#paging_info').text(start + ' - ' + end + '   Total: ' + total);
                  },
                  "dom": '<"top"ip>rt<"bottom"><"clear">',
                  "ajax": {
                      "url": "index.php",
                      "type": "POST",
                      "data": { "search": " search_str ","json":1,'op':'bugs_details','id':'<?php print (int)$_REQUEST['id'];?>' }
                  },
                  "columns": [
                      { data: "error_time" },
                      { data: "host" },
                      { data: "user" },
                      { data: "cdn" },
                      { data: "module" },
                      { data: "sw_ver" },
                      { data: "opened_tab" },
                      { data: "free_mem" },
                      { data: "used_mem" },
                      { data: "lang" },
                      { data: "os" },
                      { data: "screen" },
                      { data: "fplayer" },
                      { data: "local_time" },
                      { data: "error_type" },



                  ],

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