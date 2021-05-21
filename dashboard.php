<!-- Info boxes -->
<div class="content-wrapper">
    <?php include('db_connect.php');
    $login_id = $_SESSION['login_id'];
    $login_name = $_SESSION['login_name'];
    $login_type = $_SESSION['login_type'];
    ?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0">File and Task Management System</h1>
                </div><!-- /.col -->
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Project Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-dark shadow-sm border">
                        <div class="inner">
                            <h3><?php
                                if ($login_type == 1) :
                                    $qryTP = "SELECT * FROM `project_list`";
                                endif;
                                if ($login_type == 2) :
                                    $qryTP = "SELECT * FROM `project_list` WHERE `project_list`.`user_id` = $login_id";
                                endif;

                                echo $conn->query($qryTP)->num_rows; ?></h3>
                            <p>Total Projects</p>
                        </div>
                        <div class="icon text-light">
                            <i class="fa fa-layer-group"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-dark shadow-sm border">
                        <div class="inner">
                            <h3><?php
                                if ($login_type == 1) :
                                    $qryTT = "SELECT * FROM task_list";
                                endif;
                                if ($login_type == 2) :
                                    $qryTT = "SELECT * FROM task_list WHERE `task_list`.`user_id` = '$login_id'";
                                endif;
                                echo $conn->query($qryTT)->num_rows; ?></h3>
                            <p>Total Tasks</p>
                        </div>
                        <div class="icon text-light">
                            <i class="fa fa-tasks"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-dark">
            <div class="card-header">
                <?php
                if ($login_type == 1) :
                    echo "<b>Project Progress</b>";
                endif;
                if ($login_type == 2) :
                    echo "<b>Student Projects List :</b>";
                endif;
                ?>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0 table-hover">
                        <colgroup>
                            <?php
                            if ($login_type == 1) :
                                echo "<col width='5%'>";
                                echo "<col width='35%'>";
                                echo "<col width='45%'>";
                                echo "<col width='15%'>";
                            endif;
                            if ($login_type == 2) :
                                echo "<col width='5%'>";
                                echo "<col width='35%'>";
                                echo "<col width='35%'>";
                                echo "<col width='15%'>";
                                echo "<col width='10%'>";
                            endif;
                            ?>
                        </colgroup>
                        <thead>
                            <?php
                            if ($login_type == 1) :
                                echo "<th>#</th>";
                                echo "<th>Project</th>";
                                echo "<th>Progress</th>";
                                echo "<th>Status</th>";
                            endif;
                            if ($login_type == 2) :
                                echo "<th>#</th>";
                                echo "<th>Project</th>";
                                echo "<th>Progress</th>";
                                echo "<th>Status</th>";
                                echo "<th>Action</th>";
                            endif;
                            ?>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            if ($login_type == 1) :
                                $qryStr = "SELECT * FROM `project_list` order by date_created asc";
                            endif;
                            if ($login_type == 2) :
                                $qryStr = "SELECT * FROM `project_list` WHERE `project_list`.`user_id` = $login_id order by date_created asc";
                            endif;
                            $stat = array("Pending", "Started", "On-Progress", "On-Hold", "Over Due", "Done");

                            $qry = $conn->query($qryStr);
                            while ($row = $qry->fetch_assoc()) :
                                $trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
                                unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                                $desc = strtr(html_entity_decode($row['description']), $trans);
                                $desc = str_replace(array("<li>", "</li>"), array("", ", "), $desc);

                                $prog = 0;
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
                                    <td>
                                        <?php echo $i++ ?>
                                    </td>
                                    <td>
                                        <a>
                                            <?php echo ucwords($row['project_name']) ?>
                                        </a>
                                        <br>
                                        <small>
                                            Due: <?php echo date("Y-m-d", strtotime($row['end_date'])) ?>
                                        </small>
                                    </td>
                                    <td class="project_progress">
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $prog ?>%">
                                            </div>
                                        </div>
                                        <small>
                                            <?php echo $prog ?>% Complete
                                        </small>
                                    </td>
                                    <td class="project-state">
                                        <?php
                                        if ($stat[$row['status']] == 'Pending') {
                                            echo "<span class='badge badge-secondary'>{$stat[$row['status']]}</span>";
                                        } elseif ($stat[$row['status']] == 'Started') {
                                            echo "<span class='badge badge-primary'>{$stat[$row['status']]}</span>";
                                        } elseif ($stat[$row['status']] == 'On-Progress') {
                                            echo "<span class='badge badge-warning'>{$stat[$row['status']]}</span>";
                                        } elseif ($stat[$row['status']] == 'On-Hold') {
                                            echo "<span class='badge badge-warning'>{$stat[$row['status']]}</span>";
                                        } elseif ($stat[$row['status']] == 'Over Due') {
                                            echo "<span class='badge badge-danger'>{$stat[$row['status']]}</span>";
                                        } elseif ($stat[$row['status']] == 'Done') {
                                            echo "<span class='badge badge-success'>{$stat[$row['status']]}</span>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($login_type != 1) : ?>
                                            <a class="btn btn-primary btn-sm" href="./index.php?page=view_project&id=<?php echo $row['project_id'] ?>" />
                                            <i class="fas fa-folder">
                                            </i>
                                            View
                                            </a>";
                                        <?php endif; ?>

                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>