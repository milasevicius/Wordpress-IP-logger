<?php
function drawForm(){
  ?> 
  <h3>Optional IPs</h3><div id="logThis">Currently logging <?php echo get_option('Which_ip_to_logg');?> IP's</div>

  <form  id="checkIPs" class="checkIPs" />
    <input type="radio" name="logIP" value="all" id="all" > Log all IP's<br>
    <input type="radio" name="logIP" value="selected" id="selected"> Log selected IP's<br>
  </form>
  <br>
  <form  id="addAddress" class="addAddress" />
    <input type="text" name="ROUTE"  placeholder="IP address" id="dname" />
    <input type="submit" value="Add" class="button-primary" >
  </form>
  <?php
}

function drawOptional(){

    global $wpdb;
    $optional = $wpdb->prefix . "optional_ips";

    $optionalData = $wpdb->get_results(
    "
    SELECT ID, address
    FROM $optional
    "
    );

  ?>
   <table class="widefat" id="optional">
    <thead>
      <tr>
        <th>#</th>
        <th>IP adress</th>
        <th>Delete</th>
      </tr>
    </thead>
      <tfoot>
      <tr>
        <th>#</th>
        <th>IP adress</th>
        <th>Delete</th>
      </tr>
      </tfoot>
      <tbody id="tbodyOptional">
      <?php
      $numeric = 0;
        foreach ($optionalData as $optionalData) {
          $numeric++;
      ?>
        <tr <?php echo "data-id=".$optionalData->ID.""?> >
      <?php
        echo"<td>".$numeric."</td>";
        echo"<td>".$optionalData->address."</td>";
        echo"<td><button class='button-secondary deleteRow' type='button'>Delete</button></td>";
      ?>
        </tr>
      <?php
        }
      ?>
      </tbody>
    </table>
  <?php
}


function drawLogged(){

  global $wpdb;
  $logged = $wpdb->prefix . "logged_ips";


  // $loggedData = $wpdb->get_results(
  //   "
  //   SELECT ID, date, address, post_ID, page_name, page_url
  //   FROM $logged
  //   "
  //   );

  // $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
  $pagenum = filter_input(INPUT_GET, 'pagenum') ? absint(filter_input(INPUT_GET, 'pagenum')) : 1;

  $limit = 50; 
  $offset = ( $pagenum - 1 ) * $limit;
  $total = $wpdb->get_var( "SELECT COUNT(`id`) FROM $logged" );
  $num_of_pages = ceil( $total / $limit );


  $loggedData = $wpdb->get_results(
    "
    SELECT ID, date, address, post_ID, page_name, page_url 
    FROM $logged 
    LIMIT $offset, $limit" 
    );

  $page_links = paginate_links( array(
    'base' => add_query_arg( 'pagenum', '%#%' ),
    'format' => '',
    'prev_text' => __( '&laquo;', 'text-domain' ),
    'next_text' => __( '&raquo;', 'text-domain' ),
    'total' => $num_of_pages,
    'current' => $pagenum
) );

if ( $page_links ) {
    echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
}
  ?>
    <h3>Logged IPs</h3>
    <table class="widefat">
      <thead>
        <tr>
          <th>#</th>
          <th>IP adress</th>
          <th>Date</th>
          <th>Post ID</th>
          <th>Post Name</th>
          <th>Post Link</th>
        </tr>
      </thead>
        <tfoot>
        <tr>
          <th>#</th>
          <th>IP adress</th>
          <th>Date</th>
          <th>Post ID</th>
          <th>Post Name</th>
          <th>Post Link</th>
        </tr>
        </tfoot>
        <tbody>
        <?php
        $numeric = 0;
          foreach ($loggedData as $loggedData) {
            $numeric++;
        ?>
          <tr>
        <?php
          echo"<td>".$numeric."</td>";
          echo"<td>".$loggedData->address."</td>";
          echo"<td>".$loggedData->date."</td>";
          echo "<td>".$loggedData->post_ID."</td>";
          echo "<td>".$loggedData->page_name."</td>";
          echo "<td> <a href='".$loggedData->page_url."'>Link</a></td>";
        ?>
          <tr>
        <?php
          }
        ?>
        </tbody>
      </table>
  <?php
  if ( $page_links ) {
    echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
  }
}
?>