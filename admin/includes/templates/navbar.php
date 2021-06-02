<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="dashboard.php"><?php echo lang('HOME'); ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="categories.php"><?php echo lang('CATEGORIES'); ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="items.php"><?php echo lang('ITEMS'); ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="members.php"><?php echo lang('MEMBERS'); ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="comments.php"><?php echo lang('COMMENTS'); ?></a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php echo $_SESSION['username']; ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="../index.php"><?php echo lang('SHOP'); ?></a>
          <a class="dropdown-item" href="members.php?do=edit&userid=<?php echo $_SESSION['ID']; ?>"><?php echo lang('EDIT_PROFILE'); ?></a>
          <a class="dropdown-item" href="#"><?php echo lang('SETTINGS'); ?></a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php"><?php echo lang('LOG_OUT'); ?></a>
        </div>
      </li>
    </ul>
  </div>
</nav>