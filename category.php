<?php
    session_start();
        include "init.php";
        $pagename = '';
        $pageTitle = 'Categories';
        if (!isset($_GET['pagename'])) {
            $_GET['pagename'] = 'Choose category to show';
        }
?>

<div class="container cats">
    <h1 class="text-center"><?php echo str_replace('-',' ',$_GET['pagename']); ?></h1>
    <div class="row">
    <?php
        $pageid =  isset($_GET['pageid']) && is_numeric($_GET['pageid']) ? intval($_GET['pageid']) : 0;
        foreach (getItem('cat_id', $pageid) as $item) {

            echo '<div class="text-center col-xs-12 col-sm-6 col-md-4 cat-info">';
            ?>
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="layout/images/computer.png" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $item['name']; ?></h5>
                        <p class="card-text"><?php echo $item['description']; ?></p>
                        <p class="card-text">
                            <span>Price:<br><?php echo $item['price']; ?></span>
                            <span>Country:<br><?php echo $item['countryMade']; ?></span>
                            <span>Status:<br><?php
                             if ($item['status'] == 1) {echo 'New';}
                             if ($item['status'] == 2) {echo 'Like New';}
                             if ($item['status'] == 3) {echo 'Used';}
                             if ($item['status'] == 4) {echo 'Very Old';}
                             ?></span>
                        </p>
                    </div>
                </div>
            <?php
            echo '</div>';
            
        }
    ?>
    </div>
</div> 

<?php include $tpl . "footer.php"; ?>