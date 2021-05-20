<?php
include 'db_connect.php';
$projectID = $_GET['id'];
$stat = array("Pending", "Started", "On-Progress", "On-Hold", "Over Due", "Done");
$qry = $conn->query("SELECT * FROM `project_list` where `project_list`.`project_id` = $projectID")->fetch_array();
foreach ($qry as $k => $v) {
	$$k = $v;
}
$tprog = $conn->query("SELECT * FROM `task_list` where `task_list`.`project_id` = $projectID")->num_rows;
$cprog = $conn->query("SELECT * FROM `task_list` where `task_list`.`project_id` = $projectID and status = 3")->num_rows;
$prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
$prog = $prog > 0 ?  number_format($prog, 2) : $prog;
if ($status == 0 && strtotime(date('Y-m-d')) >= strtotime($start_date)) :
	if ($cprog > 0)
		$status = 2;
	else
		$status = 1;
elseif ($status == 0 && strtotime(date('Y-m-d')) > strtotime($end_date)) :
	$status = 4;
endif;
?>
<div class="content-wrapper">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-12">
				<div class="callout callout-info">
					<div class="col-md-12">
						<div class="row">
							<div class="col-sm-6">
								<dl>
									<dt><b>PROJECT NAME : </b></dt>
									<dd><?php echo ucwords($qry['project_name']) ?></dd>
									<dt><b>DESCRIPTION</b></dt>
									<dd><?php echo html_entity_decode($qry['description']) ?></dd>
								</dl>
							</div>
							<div class="col-md-6">
								<dl>
									<dt><b>START DATE : </b></dt>
									<dd><?php echo date("F d, Y", strtotime($qry['start_date'])) ?></dd>
								</dl>
								<dl>
									<dt><b>END DATE : </b></dt>
									<dd><?php echo date("F d, Y", strtotime($qry['end_date'])) ?></dd>
								</dl>
								<dl>
									<dt><b>STATUS :</b></dt>
									<dd>
										<?php
										if ($stat[$status] == 'Pending') {
											echo "<span class='badge badge-secondary'>{$stat[$status]}</span>";
										} elseif ($stat[$status] == 'Started') {
											echo "<span class='badge badge-primary'>{$stat[$status]}</span>";
										} elseif ($stat[$status] == 'On-Progress') {
											echo "<span class='badge badge-info'>{$stat[$status]}</span>";
										} elseif ($stat[$status] == 'On-Hold') {
											echo "<span class='badge badge-warning'>{$stat[$status]}</span>";
										} elseif ($stat[$status] == 'Over Due') {
											echo "<span class='badge badge-danger'>{$stat[$status]}</span>";
										} elseif ($stat[$status] == 'Done') {
											echo "<span class='badge badge-success'>{$stat[$status]}</span>";
										}
										?>
									</dd>
								</dl>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<span><b>Task List:</b></span>
					<div class="card-tools">
						<button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_task"><i class="fa fa-plus"></i> New Task</button>
					</div>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-condensed m-0 table-hover">
							<colgroup>
								<col width="5%">
								<col width="25%">
								<col width="30%">
								<col width="15%">
								<col width="15%">
							</colgroup>
							<thead class="text-center">
								<th>#</th>
								<th>Task Name</th>
								<th>Description</th>
								<th>Status</th>
								<th>Action</th>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$tasks = $conn->query("SELECT * FROM `task_list` where `task_list`.`project_id` = $projectID order by task asc");
								while ($row = $tasks->fetch_assoc()) :
									$trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
									unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
									$desc = strtr(html_entity_decode($row['description']), $trans);
									$desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);
								?>
									<tr>
										<td class="text-center">
											<?php echo $i++ ?>
										</td>
										<td>
											<b><?php echo ucwords($row['task']) ?></b>
										</td>
										<td>
											<p class="truncate"><?php echo strip_tags($desc) ?></p>
										</td>
										<td class="text-center">
											<?php
											if ($row['status'] == 1) {
												echo "<span class='badge badge-secondary'>Pending</span>";
											} elseif ($row['status'] == 2) {
												echo "<span class='badge badge-primary'>On-Progress</span>";
											} elseif ($row['status'] == 3) {
												echo "<span class='badge badge-success'>Done</span>";
											}
											?>
										</td>
										<td class="text-center">
											<a class="dropdown-item view_task" href="javascript:void(0)" data-id="<?php echo $row['task_id'] ?>" data-task="<?php echo $row['task'] ?>">View</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item edit_task" href="javascript:void(0)" data-id="<?php echo $row['task_id'] ?>" data-task="<?php echo $row['task'] ?>">Edit</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item delete_task" href="javascript:void(0)" data-id="<?php echo $row['task_id'] ?>">Delete</a>
										</td>
									</tr>
								<?php
								endwhile;
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.users-list>li img {
		border-radius: 50%;
		height: 67px;
		width: 67px;
		object-fit: cover;
	}

	.users-list>li {
		width: 33.33% !important
	}

	.truncate {
		-webkit-line-clamp: 1 !important;
	}
</style>
<script>
	$('#new_task').click(function() {
		uni_modal("New Task For <?php echo ucwords($qry['project_name']) ?>", "manage_task.php?pid=<?php echo $projectID ?>", "mid-large")
	})
	$('.edit_task').click(function() {
		uni_modal("Edit Task: " + $(this).attr('data-task'), "manage_task.php?pid=<?php echo $projectID ?>&id=" + $(this).attr('data-id'), "mid-large")
	})
	$('.view_task').click(function() {
		uni_modal("Task Details", "view_task.php?id=" + $(this).attr('data-id'), "mid-large")
	})
	
	$('.delete_task').click(function(){
	_conf("Are you sure to delete this task?","delete_task",[$(this).attr('data-id')])
	})

	function delete_task($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_task',
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