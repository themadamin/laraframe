<?php require base_path('views/components/head.php') ?>
<?php require base_path('views/components/nav.php') ?>
<?php require base_path('views/components/banner.php') ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <p class="mb-6">
            <a href="/notes" class="text-blue-500 underline">go back...</a>
        </p>
        <p><?= htmlspecialchars($note['title'])?></p>
        <p><?= htmlspecialchars($note['body']) ?></p>

        <footer class="mt-6">
            <button type="button" class="text-red-500 mr-4" onclick="document.querySelector('#delete-form').submit()">Delete</button>

            <a href="/notes/<?= $note['id'] ?>/edit" class="inline-flex justify-center rounded-md border border-transparent bg-gray-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Edit</a>
        </footer>
    </div>

    <form id="delete-form" class="hidden" method="POST" action="/notes/<?= $note['id'] ?>">
        <input type="hidden" name="_method" value="DELETE">
    </form>
</main>

<?php require base_path('views/components/footer.php') ?>
