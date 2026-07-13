<?php
/**
 * Template pagination custom Sweet Bakery (bergaya Tailwind).
 * Tombol bulat rapi, halaman aktif berwarna coklat tua.
 */
$pager->setSurroundCount(2);
?>
<nav aria-label="Pagination" class="flex justify-center">
    <ul class="inline-flex items-center gap-1.5">

        <?php if ($pager->hasPrevious()): ?>
            <li>
                <a href="<?= $pager->getPrevious() ?>"
                   class="inline-flex items-center px-3.5 py-2 rounded-lg text-sm font-medium bg-white dark:bg-stone-800 border border-brownlite/40 text-browndark dark:text-brownlite hover:bg-brownlite hover:text-white transition">
                    &larr; Prev
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link): ?>
            <li>
                <a href="<?= $link['uri'] ?>"
                   class="inline-flex items-center justify-center min-w-[2.5rem] px-3 py-2 rounded-lg text-sm font-medium transition
                          <?= $link['active']
                                ? 'bg-browndark text-white shadow'
                                : 'bg-white dark:bg-stone-800 border border-brownlite/40 text-browndark dark:text-brownlite hover:bg-brownlite hover:text-white' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()): ?>
            <li>
                <a href="<?= $pager->getNext() ?>"
                   class="inline-flex items-center px-3.5 py-2 rounded-lg text-sm font-medium bg-white dark:bg-stone-800 border border-brownlite/40 text-browndark dark:text-brownlite hover:bg-brownlite hover:text-white transition">
                    Next &rarr;
                </a>
            </li>
        <?php endif ?>

    </ul>
</nav>
