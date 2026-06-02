<!-- VIEWS/HOME/index.php -->
<h1><?= $translator->get('welcome') ?></h1>

<!-- Selecteur de langue -->
<a href="/fr<?= $router->getCurrentPath() ?>">FR</a>
<a href="/en<?= $router->getCurrentPath() ?>">EN</a>
<a href="/fr-ca<?= $router->getCurrentPath() ?>">FR-CA</a>
<a href="/en-ca<?= $router->getCurrentPath() ?>">EN-CA</a>