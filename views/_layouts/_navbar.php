<?php
use codewild\csubmboer\core\Application;

$url = Application::$app->request->getUrl();

?>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#headerNavbarContent" aria-controls="headerNavbarContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div id="headerNavbarContent" class="navbar-collapse collapse p-2">
    <ul class="navbar-nav col mb-2 mb-lg-0 flex-shrink-0">
        <li class="nav-item">
            <?php if ($url === '/'): ?>
            <a class="nav-link active" href="/" aria-current="true">Home</a>
            <?php else: ?>
            <a class="nav-link" href="/">Home</a>
            <?php endif; ?>
        </li>
        <li class="nav-item">
            <?php if (preg_match('/\/modules\w?+/', $url)): ?>
                <a class="nav-link active" href="/modules" aria-current="true">Modules</a>
            <?php else: ?>
                <a class="nav-link" href="/modules">Modules</a>
            <?php endif; ?>
        </li>
        <li class="nav-item">
            <?php if (preg_match('/\/appendix\w?+/', $url)): ?>
                <a class="nav-link active" href="/appendix" aria-current="true">Appendix</a>
            <?php else: ?>
                <a class="nav-link" href="/appendix">Appendix</a>
            <?php endif; ?>
        </li>
    </ul>
    <form id="searchForm" action="/search" method="GET" class="col mx-4">
        <div class="input-group">
            <input class="form-control form-control-sm" type="search" name="q" placeholder="Search" aria-label="Search">
            <button class="btn btn-sm btn-outline-success" type="submit">Go</button>
        </div>
    </form>
    <ul class="navbar-nav col align-items-end justify-content-end order-3 text-end">
    <?php if (Application::isGuest()): ?>
        <li class="nav-item">
            <?php
            $currentPage = urlencode(Application::$app->request->getUrl());
            if (!$currentPage){
                $return = "/login";
            } else {
                $return = "/login?return=$currentPage";
            }
            ?>
            <a class="nav-link" href="<?php echo $return ?>">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/register">Register</a>
        </li>
    <?php else: $user = Application::$app->user; ?>

            <li class="nav-item">
                <a class="nav-link" href="/profile">Hello <?php echo $user->getDisplayName(); ?></a>
            </li>
        <?php
            if ($user->isInRole(\codewild\csubmboer\models\UserRole::ROLE_ADMIN)): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/admin">Admin</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <?php
                $currentPage = urlencode(Application::$app->request->getUrl());
                if (!$currentPage){
                    $return = "/logout";
                } else {
                    $return = "/logout?return=$currentPage";
                }
                ?>
                <a class="nav-link" href="<?php echo $return ?>">Logout</a>
            </li>
        </ul>
    <?php endif; ?>

</div>
