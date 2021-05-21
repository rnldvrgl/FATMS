<?php include 'db_connect.php';
$login_id = $_SESSION['login_id'];
$login_name = $_SESSION['login_name'];
?>
<div class="content-wrapper">
	<div class="col-lg-12">
		<div class="card card-outline card-success">
			<div class="card-header">
				<div class="card-tools">
					<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Add New project</a>
				</div>
			</div>
			<div class="card-body">
				<table class="table tabe-hover table-condensed" id="list">
					<colgroup>
						<col width="5%">
						<col width="35%">
						<col width="15%">
						<col width="15%">
						<col width="20%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th>Project</th>
							<th>Date Started</th>
							<th>Due Date</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$stat = array("Pending", "Started", "On-Progress", "On-Hold", "Over Due", "Done");
						$qry = $conn->query("SELECT * FROM `project_list` WHERE `project_list`.`user_id` = $login_id order by project_name asc");
						while ($row = $qry->fetch_assoc()) :
							$trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
							unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
							$desc = strtr(html_entity_decode($row['description']), $trans);
							$desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);

							$tprog = $conn->query("SELECT * FROM task_list where project_id = {$row['project_id']}")->num_rows;
							$cprog = $conn->query("SELECT * FROM task_list where project_id = {$row['project_id']} and status = 3")->num_rows;
							$prog = $tprog > 0 ? ($cprog / $tprog) * 100 : 0;
							$prog = $prog > 0 ?  number_format($prog, 2) : $prog;
							if ($row['status'] == 0 && strtotime(date('Y-m-d')) >= strtotime($row['start_date'])) :
								if ($cprog > 0)
									$row['status'] = 2;
								else
									$row['status'] = 1;
							elseif ($row['status'] == 0 && strtotime(date('Y-m-d')) > strtotime($row['end_date'])) :
								$row['status'] = 4;
							endif;
						?>
							<tr>
								<th class="text-center"><?php echo $i++ ?></th>
								<td>
									<p><b><?php echo ucwords($row['project_name']) ?></b></p>
									<p class="truncate"><?php echo strip_tags($desc) ?></p>
								</td>
								<td><b><?php echo date("M d, Y", strtotime($row['start_date'])) ?></b></td>
								<td><b><?php echo date("M d, Y", strtotime($row['end_date'])) ?></b></td>
								<td class="text-center">
									<?php
									if ($stat[$row['status']] == 'Pending') {
										echo "<span class='badge btn-block badge-secondary'>{$stat[$row['status']]}</span>";
									} elseif ($stat[$row['status']] == 'Started') {
										echo "<span class='badge btn-block badge-primary'>{$stat[$row['status']]}</span>";
									} elseif ($stat[$row['status']] == 'On-Progress') {
										echo "<span class='badge btn-block badge-info'>{$stat[$row['status']]}</span>";
									} elseif ($stat[$row['status']] == 'On-Hold') {
										echo "<span class='badge btn-block badge-warning'>{$stat[$row['status']]}</span>";
									} elseif ($stat[$row['status']] == 'Over Due') {
										echo "<span class='badge btn-block badge-danger'>{$stat[$row['status']]}</span>";
									} elseif ($stat[$row['status']] == 'Done') {
										echo "<span class='badge btn-block badge-success'>{$stat[$row['status']]}</span>";
									}
									?>
								</td>
								<td class="text-center">
									<a class="dropdown-item view_project bg-dark" href="./index.php?page=view_project&id=<?php echo $row['project_id'] ?>" data-id="<?php echo $row['project_id'] ?>"><i class="fas fa-eye"></i> View</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item edit_project bg-info" href="./index.php?page=edit_project&id=<?php echo $row['project_id'] ?>"><i class="fas fa-edit"></i> Edit</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item delete_project bg-danger" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i> Delete</a>
								</td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<style>
	table p {
		margin: unset !important;
	}

	table td {
		vertical-align: middle !important
	}
</style>
<script>
	$('#list').dataTable()

	$('.delete_project').click(function(){
	_conf("Are you sure to delete this project?","delete_project",[$(this).attr('data-id')])
	})

	function delete_project($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_task',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
</script>