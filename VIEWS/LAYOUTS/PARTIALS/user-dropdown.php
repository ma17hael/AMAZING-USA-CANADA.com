<?php
/** @var string $lang */

$user = $auth->user();
$initials = strtoupper(substr($user->username, 0, 2));
?>

<div class="user-wrapper">
    <div class="btn-user js-user-btn"
        aria-haspopup="true"
        aria-expanded="false"
        aria-controls="userDropdown"
        tabindex="0">
        <div class="user-avatar" aria-hidden="true">
            <?php if ($user->avatarPath): ?>
                <img src="/<?= $lang ?>/uploads/avatars/<?= $user->avatarPath ?>"
                     alt="<?= htmlspecialchars($user->username) ?>"
                     style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
            <?php else: ?>
                <?= $initials ?>
            <?php endif; ?>
        </div>
        <span class="user-name"><?= htmlspecialchars($user->username) ?></span>
        <i class="ti ti-chevron-down user-chevron" aria-hidden="true"></i>
    </div>

    <div class="user-dropdown" id="userDropdown" role="menu">
        <!-- En-tête -->
        <div class="dropdown-header">
            <div class="dropdown-avatar" aria-hidden="true">
                <?php if ($user->avatarPath): ?>
                    <img src="/<?= $lang ?>/uploads/avatars/<?= $user->avatarPath ?>"
                         alt=""
                         style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                <?php else: ?>
                    <?= $initials ?>
                <?php endif; ?>
            </div>
            <div>
                <div class="dropdown-username"><?= htmlspecialchars($user->username) ?></div>
                <div class="dropdown-email"><?= htmlspecialchars($user->email) ?></div>
            </div>
        </div>

        <!-- Items -->
        <div class="dropdown-body">
            <a href="/<?= $lang ?>/compte/profil" class="dropdown-item" role="menuitem">
                <i class="ti ti-user" aria-hidden="true"></i>
                <?= $translator->safe('account.profile') ?>
            </a>

            <a href="/<?= $lang ?>/compte/cartes" class="dropdown-item" role="menuitem">
                <i class="ti ti-map" aria-hidden="true"></i>
                <?= $translator->safe('account.my_maps') ?>
                <!-- À brancher sur user_map_access count -->
            </a>

            <a href="/<?= $lang ?>/compte/commandes" class="dropdown-item" role="menuitem">
                <i class="ti ti-receipt" aria-hidden="true"></i>
                <?= $translator->safe('account.orders') ?>
            </a>

            <a href="/<?= $lang ?>/compte/factures" class="dropdown-item" role="menuitem">
                <i class="ti ti-file-invoice" aria-hidden="true"></i>
                <?= $translator->safe('account.invoices') ?>
            </a>

            <div class="dropdown-sep" aria-hidden="true"></div>

            <a href="/<?= $lang ?>/compte/parametres" class="dropdown-item" role="menuitem">
                <i class="ti ti-settings" aria-hidden="true"></i>
                <?= $translator->safe('account.settings') ?>
            </a>

            <div class="dropdown-sep" aria-hidden="true"></div>

            <form method="POST" action="/<?= $lang ?>/deconnexion">
                <?= \App\Core\Csrf::field() ?>
                <button type="submit" class="dropdown-item danger" role="menuitem"
                        style="width:100%; text-align:left;">
                    <i class="ti ti-logout" aria-hidden="true"></i>
                    <?= $translator->safe('account.logout') ?>
                </button>
            </form>
        </div>
    </div>
</div>