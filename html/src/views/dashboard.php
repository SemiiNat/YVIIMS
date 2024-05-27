<?php include_once __DIR__ . '/partials/header.php'; ?>

<body class="text-lime-500 font-inter">
    <?php include_once __DIR__ . '/partials/navigation.php'; ?>
    <main id="main-content" class="ml-64 p-6">
        <h2 class="text-black text-lg">
            Welcome to the Dashboard
        </h2>
    </main>
</body>

<script defer>
    function toggleDropdown(dropdownId) {
        const element = document.getElementById(dropdownId);
        element.classList.toggle("hidden");
    }
</script>
<?php include_once __DIR__ . '/partials/footer.php'; ?>