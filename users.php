<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">

				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item active"><a class="text-dark" href="index.php?page=users">Users</a></li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">Users</h3>
						<div class="card-tools">
							<button class="btn float-right text-light btn-md" style="background-color: #125E20;" id="new_user"><i class="fa fa-plus"></i> New user</button>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body table-responsive p-0 ">
						<table class="table table-sm table-hover text-nowrap table-bordered">
							<thead>
								<tr class="text-center">
									<th width="5%">#</th>
									<th width="70%">Name</th>
									<th width="20%">Username</th>
									<th width="5%">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								include 'db_connect.php';
								$users = $conn->query("SELECT * FROM users order by name asc");
								$i = 1;
								while ($row = $users->fetch_assoc()) :
								?>
									<tr>
										<td class="text-center">
											<?php echo $i++ ?>
										</td>
										<td>
											<?php echo $row['name'] ?>
										</td>
										<td class="text-center">
											<?php echo $row['username'] ?>
										</td>
										<td class="text-center">
											<a class="edit_user" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'><i class="fas fa-user-edit text-dark"></i></a>
											<div class="dropdown-divider"></div>
											<a class="delete_user" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'><i class="fas fa-trash-alt text-danger"></i></a>

										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
		</div>
		<!-- /.row -->
	</div>
</div>
<script>
	$('#new_user').click(function() {
		uni_modal('New User', 'manage_user.php')
	})
	$('.edit_user').click(function() {
		uni_modal('Edit User', 'manage_user.php?id=' + $(this).attr('data-id'))
	})
	$('.delete_user').click(function(){
	_conf("Are you sure to delete this user?","delete_user",[$(this).attr('data-id')])
	})

	function delete_user($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_user',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>