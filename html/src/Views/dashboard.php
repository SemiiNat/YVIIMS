<?php 
use App\Http\View;

View::includePartial('header'); 
?>

<body class="text-lime-500 font-inter">
    <?php 
    View::includePartial('navigation'); 
    ?>
    <main id="main-content" class="ml-64 p-6">
        <h2 class="text-black text-lg">
            <?php View::renderSection('content'); ?>
        </h2>
    </main>
</body>

<?php 
View::addScript('dashboardScript'); 
View::includePartial('footer'); 
?>
