
<?php require('views/components/header.php') ?>

<?php require('views/components/navbar.php') ?>

<?php require('views/components/banner.php') ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">

        <ul>
            <?php foreach ($notes as $note): ?>
                <li>
                    <a href="/note?id=<?=$note['id']?>" class="text-blue-500 hover:underline">
                            <?=htmlspecialchars($note['body']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <p class="mt-6">
            <a href="/notes/note-create" class="text-blue-500 hover:underline">Create note</a>
        </p>
    </div>
</main>

<?php require('views/components/footer.php') ?>
