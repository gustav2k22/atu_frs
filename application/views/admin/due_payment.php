<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'inc/head.php'; ?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <?php include 'inc/navbar.php'; ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include 'inc/aside.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Due Payments</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header card-info">
                <!-- <h3 class="card-title"></h3> -->
                <div class="card-tools">
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body mt-3 table-responsive">

                <table class="table table-hover table-bordered table-striped" id="example1">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>NAME</th>
                      <th>EMAIL</th>
                      <th>COURSE</th>
                      <th>SEMESTER</th>
                      <th>DUE</th>
                      <th>ACTIONS</th>
                    </tr>
                  </thead>
                  <tbody>
  <?php  
  $count = 1;
  $this->db->where('SESSION', $current_session);
  $payments = $this->db->get('all_payments_tbl')->result_array();
  foreach ($payments as $row):
    $student = $this->db->get_where('student', array('ID' => $row['STUDENT']))->row();
  ?>
  <tr>
    <td><?php echo $count++; ?></td>
    <td><?php echo $student->NAME; ?></td>
    <td><?php echo $student->EMAIL; ?></td>
    <td><?php echo $this->db->get_where('class_tbl', array('ID' => $row['CLASS']))->row()->NAME; ?></td>
    <td><?php echo $this->db->get_where('term_tbl', array('ID' => $row['TERM']))->row()->NAME; ?></td>
    <td><span style="color: red; font-weight: bolder;">
      <?php echo date("d/F/Y", strtotime($row['EXPIRE_DATE'])); ?>
    </span></td>
    <td>
      <a style="padding: 2px; font-size: 12px;" href="<?php echo base_url() ?>admin/payment/single/<?php echo $row['CLASS'].'/'.$row['STUDENT'] ?>" class="btn btn-sm btn-info">VIEW PAYMENT</a>
      <button class="btn btn-sm btn-warning send-reminder-btn"
        data-student-id="<?php echo $row['STUDENT']; ?>"
        data-student-name="<?php echo $student->NAME; ?>"
        data-email="<?php echo $student->EMAIL; ?>"
        data-expire-date="<?php echo $row['EXPIRE_DATE']; ?>">SEND REMINDER</button>
    </td>
    
  </tr>
  <?php  
  endforeach;
  ?>
</tbody>


              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <?php include 'inc/footer.php'; ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<?php include 'inc/rscript.php'; ?>

<script>
 $(document).ready(function() {
    $('#example1').DataTable();

    $('.send-reminder-btn').click(function() {
        var studentId = $(this).data('student-id');
        var email = $(this).data('email');
        var name = $(this).data('student-name');
        var expireDate = $(this).data('expire-date');

        // Send AJAX request
        $.ajax({
            url: '<?php echo base_url("reminders/send_single_reminder"); ?>',
            type: 'POST',
            data: {
                student_id: studentId,
                email: email,
                name: name,
                expire_date: expireDate,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    alert(result.message);
                } else {
                    alert('Failed to send the reminder.');
                    console.log(result.error);
                }
            },
            error: function() {
                alert('An error occurred while sending the reminder.');
            }
        });
    });
});
</script>

</body>
</html>
