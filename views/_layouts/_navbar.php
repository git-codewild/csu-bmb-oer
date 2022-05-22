<?php
use codewild\csubmboer\core\Application;
?>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#headerNavbarContent" aria-controls="headerNavbarContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div id="headerNavbarContent" class="navbar-collapse collapse p-2">
    <ul class="navbar-nav mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/modules">Modules</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/appendix">Appendix</a>
        </li>
    </ul>
    <?php if (Application::isGuest()): ?>
        <ul class="navbar-nav ms-auto">
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
        </ul>
    <?php else:
        $user = Application::$app->user;
        ?>
        <ul class="navbar-nav ms-auto">
            <?php if ($user->isInRole(\codewild\csubmboer\models\UserRole::ROLE_ADMIN)): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/admin">Admin</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="/profile">Hello <?php echo $user->getDisplayName(); ?></a>
            </li>
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
    <!-- <div class="row w-75">
      <form id="searchForm" action="/search" method="GET" class="col-sm-8 d-flex mb-2">
        <input class="form-control me-2" type="search" name="q" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div> -->
</div>
