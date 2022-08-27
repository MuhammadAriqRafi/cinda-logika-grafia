<?php $uri = service('uri') ?>

<li>
    <a class="<?= $uri->getSegment(2) === 'posts' ? 'active font-bold' : ''; ?>" href="<?= route_to('backend.posts.index'); ?>">Insights</a>
</li>
<li>
    <a class="<?= $uri->getSegment(2) === 'guestbooks' ? 'active font-bold' : ''; ?>" href="<?= route_to('backend.guestbooks.index'); ?>">Guestbooks</a>
</li>