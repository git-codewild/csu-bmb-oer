<?php

use codewild\csubmboer\core\Application;
use codewild\csubmboer\core\components\Modal;

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo strip_tags($this->title)." | BC401: Fundamentals of Biochemistry | Colorado State University"; ?></title>
    <!-- Bootstrap -->
    <link href="/css/csu-bmb-oer.css" rel="stylesheet">
  </head>
  <body class="d-flex flex-column min-vh-100">
    <header class="bg-primary text-light">
      <nav class="navbar-expand-sm navbar-dark">
          <div class="brandbar">
            <div class="container position-relative">
              <div class="row">
                <div class="signature">
                  <hgroup id="logoGroup">
                    <div id="primaryLogo">
                      <h1>
                        <a href="https://www.colostate.edu">
                        <img src="https://static.colostate.edu/logo/reslogo-v2/assets/img/csu-responsive-symbol.min.svg" type="image/svg+xml" id="svgSignature" alt="Colorado State University"></a>
                      </h1>
                    </div>
                    <div id="secondaryLogo">
                      <h2>
                        <a href="/">BC401: The Fundamentals of Biochemistry</a>
                      </h2>
                    </div>
                  </hgroup>
                </div>
              </div>
            </div>
          <?php include_once('_navbar.php'); ?>
        </div>
      </nav>
    </header>
    <main>
        <h3 class="pageTitle py-2"><?php echo $this->title ?></h3>
        <div class="d-flex flex-column flex-grow-1 overflow-hidden my-3">
            <div id="alerts" class="col-sm-4 mx-auto text-center">
                <?php if (Application::$app->session->getFlash('success')): ?>
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <?php echo Application::$app->session->getFlash('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (Application::$app->session->getFlash('info')): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?php echo Application::$app->session->getFlash('info') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (Application::$app->session->getFlash('danger')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo Application::$app->session->getFlash('danger') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>
            {{content}}
        </div>
    </main>
    <footer class="bg-primary text-light text-center">
        <div class="container">
            <div class="row p-2 justify-content-between">
                <nav class="col-md-2 nav justify-content-center">
                    <a href="/about" class="nav-link link-light">About</a>
                    <a href="/contact" class="nav-link link-light">Contact Us</a>
                </nav>
                <div class="col-md-8 mb-2">
                    <small>All content is licensed under a <a rel="license" href="https://creativecommons.org/licenses/by-nc-sa/4.0/" class="link-light text-decoration-none">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.</small>
                    <br />
                    <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Creative Commons graphic license" title="CC BY-NC-SA 4.0 License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a>
                </div>
                <div class="col-md-2 my-auto">
                    <a href="https://github.com/git-codewild/csu-bmb-oer" target="_blank"><img class="image-thumbnail" src="/img/logos/GitHub-Mark-32px.png" alt="GitHub logo" title="CSU-BMB-OER on GitHub"></a>
                </div>
            </div>
        </div>
    </footer>
    <?php
        $modals = Modal::getInstances();
        foreach ($modals as $modal){
            echo $modal->write();
        }
    ?>
  </body>
  <script src="/lib/bootstrap.bundle.js"></script>
  <script src="/lib/jquery-3.4.1.min.js"></script>
  <?php echo implode('', $this->scripts) ?>
</html>
