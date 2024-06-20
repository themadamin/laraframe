
<?php require('views/components/header.php') ?>

<?php require('views/components/navbar.php') ?>

<?php require('views/components/banner.php') ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <p class="mb-6">
            <a class="text-blue-500 underline" href="/notes">go back...</a>
        </p>
        <?= htmlspecialchars($note['body']) ?>
    </div>
</main>

<?php require('views/components/footer.php') ?>
