<?php require base_path('views/components/head.php') ?>
<?php require base_path('views/components/nav.php') ?>
<?php require base_path('views/components/banner.php') ?>

<main>
    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="mt-5 md:col-span-2 md:mt-0">
                <form method="POST" action="/notes/<?= $note['id']?>">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="shadow sm:overflow-hidden sm:rounded-md">
                        <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                            <div>
                                <label
                                        for="title"
                                        class="block text-sm font-medium text-gray-700"
                                >Title</label>

                                <div class="mt-1">
                                    <input
                                            id="title"
                                            name="title"
                                            rows="3"
                                            value="<?= $note['title'] ?? '' ?>"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Here's an title for a note..."
                                    />

                                    <?php if (isset($errors['title'])) : ?>
                                        <p class="text-red-500 text-xs mt-2"><?= $errors['title'] ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div>
                                <label
                                        for="body"
                                        class="block text-sm font-medium text-gray-700"
                                >Body</label>

                                <div class="mt-1">
                                    <input
                                            id="body"
                                            name="body"
                                            rows="3"
                                            value="<?= $note['body'] ?? '' ?>"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Here's an idea for a note..."
                                    />

                                    <?php if (isset($errors['body'])) : ?>
                                        <p class="text-red-500 text-xs mt-2"><?= $errors['body'] ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6 flex gap-x-4 justify-end items-center">
                            <a
                                href="/notes"
                                class="inline-flex justify-center rounded-md border border-transparent bg-gray-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Update
                            </button>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
</main>

<?php require base_path('views/components/footer.php') ?>
