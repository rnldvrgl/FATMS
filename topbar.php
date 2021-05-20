<style>
	.logo {
    margin: auto;
    font-size: 20px;
    background: white;
    padding: 5px 11px;
    border-radius: 50% 50%;
    color: #000000b3;
}
.bg-custom{
  background: black;
}
</style>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link text-dark" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars mr-3"></i><?php echo " " . $_SESSION['login_name'] . "  " ?> </a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link text-dark" href="ajax.php?action=logout">
          <i class="fa fa-power-off mr-3"></i>
        </a>
      </li>

    </ul>
    
  </nav>
  <!-- /.navbar -->