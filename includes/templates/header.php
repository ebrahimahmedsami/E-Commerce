<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title><?php getTitle(); ?></title>
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>front.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>all.min.css" />
</head>
<body>

<div class="upper-bar">
  <div class="container">
    <?php
        if(isset($_SESSION['member'])){

            echo 'Welcome ' . $_SESSION['member'];
            echo '<a href="profile.php"> | My Profile</a>';
            echo '<a href="newad.php"> | New Ad</a>';
            echo '<a href="logout.php"> | Logout</a>';

            $statususer = checkUserStatus($_SESSION['member']);
            if ($statususer == 1) {
              echo ' You must be activate by admin';
              
            }
      }else{
        ?>
            <a href="login.php">
              <span">Login | SignUp</span>
            </a>
        <?php
      }
    ?>
  </div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    <li class="nav-item"><a href="index.php" class="nav-link"><?php echo lang('HOME'); ?></a></li>
      <?php
        foreach (getCat() as $cat) {
          echo 
          '<li class="nav-item">
            <a href="category.php?pageid='.$cat['ID'].'&pagename='.str_replace(' ','-',$cat['name']).'" class="nav-link">' . $cat['name'] . '</a>
          </li>';
        }
      ?>
    </ul>
  </div>
</nav>