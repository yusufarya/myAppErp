<header class="main-header">
  <nav class="navbar navbar-static-top">
    <div class="container">
      <div class="navbar-header">
        <a href="index.php" class="navbar-brand"><b>PT. Shan Informasi Sistem - REGISTER UNTUK COBA GRATIS</b></a>

        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <i class="fa fa-bars"></i>
        </button>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->

      <!-- /.navbar-collapse -->
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <?php
          if(isset($_SESSION['user'])){
            $image = (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'img/user-location.png';
            echo '
            <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="'.$image.'" class="user-image" alt="User Image">
            <span class="hidden-xs">'.$user['firstname'].' '.$user['lastname'].'</span>
            </a>
            <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
            <img src="'.$image.'" class="img-circle" alt="User Image">

            <p>
            '.$user['firstname'].' '.$user['lastname'].'
            <small>Member since '.date('M. Y', strtotime($user['created_on'])).'</small>
            </p>
            </li>
            <li class="user-footer">
            <div class="pull-left">
            <a href="#" class="btn btn-default btn-flat">Profile</a>
            </div>
            <div class="pull-right">
            <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
            </div>
            </li>
            </ul>
            </li>
            ';
          }
          else{
            echo "
            <li><a href='login.php'>LOGIN</a></li>
            <li><a href='signup.php'>SIGNUP</a></li>
            ";
          }
          ?>
        </ul>
      </div>
    </div>
  </nav>
</header>