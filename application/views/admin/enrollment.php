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
            <h1 class="m-0 text-dark">
              <?php 
              echo $class_name = $this->db->get_where('class_tbl',array('ID'=>$class_id))->row()->NAME . ' : '; 
              echo $current_session = $this->db->get_where('settings_tbl',array('ID'=>1))->row()->SESSION; 
              ?>
            </h1>
          </div><!-- /.col -->
          <div class="col-sm-6 text-right">
            <button class="btn btn-info" data-toggle="modal" data-target="#addNewStudentModal"><i class="fa fa-user"></i>&nbsp; Add New Student</button>
          </div><!-- /.col -->

      <!-- Modal for adding new student -->
      <div class="modal fade" id="addNewStudentModal" role="dialog" aria-labelledby="addStudentModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="form">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">New Student Registration for <?php echo $class_name .' : '.$current_session.' Session'; ?></h5>
              </div>
              <form method="POST" action="<?php echo base_url() ?>admin/action/add_new_student/<?php echo $class_id; ?>" enctype="multipart/form-data">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="full_name">FULL NAME</label>
                        <input autocomplete="off" required type="text" name="name" class="form-control">
                      </div>
                      <hr>
                      <div class="form-group">
                        <label for="email">EMAIL</label>
                        <input autocomplete="off" required type="email" name="email" class="form-control">
                      </div>
                      <hr>
                      <div class="form-group">
                        <label for="phone">PHONE NUMBER</label>
                        <input autocomplete="off" required type="text" name="phone" class="form-control">
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Add Student</button>
                  </div>
              </form>
            </div>
        </div>
      </div>

        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header card-info">
                      <!-- <h3 class="card-title"></h3> -->
                      <div class="card-tools">
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body mt-3">

                      <table class="table table-hover table-bordered table-striped" id="example1">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>PHONE NUMBER</th>
                            <th>ACTIONS</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php  
                          $count = 1;
                          $this->db->where('CLASS', $class_id);
                          $this->db->where('SESSION', $current_session);
                          $students = $this->db->get('student')->result_array();
                          foreach ($students as $row):
                          ?>
                          <tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo $row['NAME']; ?></td>
                            <td><?php echo $row['EMAIL']; ?></td>
                            <td><?php echo $row['PHONE']; ?></td>
                            <td>
                              <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewStudentModal<?php echo $row['ID'] ?>">VIEW</button>
                            </td>
                          </tr>

                          <!-- Modal for each student -->
                          <div class="modal fade" id="viewStudentModal<?php echo $row['ID'] ?>" role="dialog" aria-labelledby="viewStudentModalLabel">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="form">
                                <div class="modal-content">
                                  <div class="modal-header">
                                      <h5 class="modal-title">View/Edit Student Info</h5>
                                  </div>
                                  <form method="POST" action="<?php echo base_url() ?>admin/action/edit_student/<?php echo $row['ID']; ?>/<?php echo $class_id; ?>" enctype="multipart/form-data">
                                      <div class="modal-body">
                                          <div class="form-group">
                                            <label for="full_name">FULL NAME</label>
                                            <input autocomplete="off" required type="text" value="<?php echo $row['NAME'] ?>" name="name" class="form-control">
                                          </div>
                                          <hr>
                                          <div class="form-group">
                                            <label for="email">EMAIL</label>
                                            <input autocomplete="off" required type="email" value="<?php echo $row['EMAIL'] ?>" name="email" class="form-control">
                                          </div>
                                          <hr>
                                          <div class="form-group">
                                            <label for="phone">PHONE NUMBER</label>
                                            <input autocomplete="off" required type="text" value="<?php echo $row['PHONE'] ?>" name="phone" class="form-control">
                                          </div>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                          <button type="submit" class="btn btn-primary">Update Student Info</button>
                                      </div>
                                  </form>
                                </div>
                            </div>
                          </div>

                          <?php endforeach; ?>
                        </tbody>
                      </table>

                    </div>
                  </div>
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
    $(function () {
      $('.select2').select2();
      $("#example1").DataTable();
      $('.money').simpleMoneyFormat();
      <?php if($this->session->flashdata('completed') != ''){ ?>
        new PNotify({
            title: 'Notification',
            text: '<?php echo $this->session->flashdata('completed'); ?>',
            type: 'success'
        });
      <?php } ?>
    });
  </script>

</body>
</html>
