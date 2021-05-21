<style>
	.custom-menu {
        z-index: 1000;
	    position: absolute;
	    background-color: #ffffff;
	    border: 1px solid #0000001c;
	    border-radius: 5px;
	    padding: 8px;
	    min-width: 13vw;
}
a.custom-menu-list {
    width: 100%;
    display: flex;
    color: #4c4b4b;
    font-weight: 600;
    font-size: 1em;
    padding: 1px 11px;
}
	span.card-icon {
    position: absolute;
    font-size: 3em;
    bottom: .2em;
    color: #ffffff80;
}
.file-item{
	cursor: pointer;
}
a.custom-menu-list:hover,.file-item:hover,.file-item.active {
    background: #80808024;
}
a.custom-menu-list span.icon{
		width:1em;
		margin-right: 5px
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<?php include('db_connect.php');
	$files = $conn->query("SELECT f.*,u.name as uname FROM files f inner join users u on u.id = f.user_id where  f.is_public = 1 order by date(f.date_updated) desc");
	?>
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-8">
					<h1 class="m-0">File and Task Management System</h1>
				</div><!-- /.col -->
				<div class="col-sm-4">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item active">Home</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<!-- Small boxes (Stat box) -->
			<?php if ($_SESSION['login_type'] == 1) : ?>
				<div class="row">
					<div class="col-lg-6 col-6">
						<!-- small box -->
						<div class="small-box bg-dark">
							<div class="inner">
								<h3><?php echo $conn->query('SELECT * FROM users')->num_rows ?></h3>
								<p>Total Users</p>
							</div>
							<div class="icon text-light">
								<i class="ion fa fa-users"></i>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-6">
						<!-- small box -->
						<div class="small-box bg-dark">
							<div class="inner">
								<h3><?php echo $conn->query('SELECT * FROM files')->num_rows ?></h3>
								<p>Total Files</p>
							</div>
							<div class="icon text-light">
								<i class="ion fa fa-file"></i>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<div class="row">
			<div class="col-12">
				<div class="card card-outline card-dark">
					<div class="card-header">
						<b>Public Files<b>
					</div>
					<!-- /.card-header -->
					<div class="card-body table-responsive p-0">
						<table class="table table-hover text-nowrap">
							<thead>
								<tr>
									<th width="20%" class="">Uploader</th>
									<th width="30%" class="">Filename</th>
									<th width="20%" class="">Date</th>
									<th width="30%" class="">Description</th>
								</tr>
							</thead>
							<tbody>
								<?php
								while ($row = $files->fetch_assoc()) :
									$name = explode(' ||', $row['name']);
									$name = isset($name[1]) ? $name[0] . " (" . $name[1] . ")." . $row['file_type'] : $name[0] . "." . $row['file_type'];
									$img_arr = array('png', 'jpg', 'jpeg', 'gif', 'psd', 'tif');
									$doc_arr = array('doc', 'docx');
									$pdf_arr = array('pdf', 'ps', 'eps', 'prn');
									$icon = 'fa-file';
									if (in_array(strtolower($row['file_type']), $img_arr))
										$icon = 'fa-image';
									if (in_array(strtolower($row['file_type']), $doc_arr))
										$icon = 'fa-file-word';
									if (in_array(strtolower($row['file_type']), $pdf_arr))
										$icon = 'fa-file-pdf';
									if (in_array(strtolower($row['file_type']), ['xlsx', 'xls', 'xlsm', 'xlsb', 'xltm', 'xlt', 'xla', 'xlr']))
										$icon = 'fa-file-excel';
									if (in_array(strtolower($row['file_type']), ['zip', 'rar', 'tar']))
										$icon = 'fa-file-archive';

								?>
									<tr class='file-item' data-id="<?php echo $row['id'] ?>" data-name="<?php echo $name ?>">
										<td><i><?php echo ucwords($row['uname']) ?></i></td>
										<td>
											<large><span><i class="fa <?php echo $icon ?> mr-2"></i></span><b> <?php echo $name ?></b></large>
											<input type="text" class="rename_file" value="<?php echo $row['name'] ?>" data-id="<?php echo $row['id'] ?>" data-type="<?php echo $row['file_type'] ?>" style="display: none">

										</td>
										<td><i><?php echo date('Y/m/d h:i A', strtotime($row['date_updated'])) ?></i></td>
										<td><i><?php echo $row['description'] ?></i></td>
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
	</section>
</div>

<div id="menu-file-clone" style="display: none;">
	<a href="javascript:void(0)" class="custom-menu-list file-option download"><span><i class="fa fa-download mr-2"></i> </span>Download</a>
</div>
<script>
	//FILE
	$('.file-item').bind("contextmenu", function(event) { 
    event.preventDefault();

    $('.file-item').removeClass('active')
    $(this).addClass('active')
    $("div.custom-menu").hide();
    var custom =$("<div class='custom-menu file'></div>")
        custom.append($('#menu-file-clone').html())
        custom.find('.download').attr('data-id',$(this).attr('data-id'))
    custom.appendTo("body")
	custom.css({top: event.pageY + "px", left: event.pageX + "px"});

	
	$("div.file.custom-menu .download").click(function(e){
		e.preventDefault()
		window.open('download.php?id='+$(this).attr('data-id'))
	})

	

})
	$(document).bind("click", function(event) {
    $("div.custom-menu").hide();
    $('#file-item').removeClass('active')

});
	$(document).keyup(function(e){

    if(e.keyCode === 27){
        $("div.custom-menu").hide();
    $('#file-item').removeClass('active')

    }
})
</script>