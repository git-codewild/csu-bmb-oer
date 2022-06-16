<?php

/**
 * @var string $q
 * @var array $results
 */

use codewild\phpmvc\table\Table;

$this->title = 'Search';

?>

<div class="container">
    <div class="row align-items-center">
        <div class="col-xl-4 col-lg-6 col-sm-8 mx-auto">
            <form class="form" action="/search" method="get">
                <div class="input-group input-group-lg">
                    <input type="search" class="form-control form-control-lg" name="q" value="<?php echo $q?>">
                    <button class="btn btn-lg" type="submit">Find...</button>
                </div>
            </form>
            <?php if (is_null($q) || empty($q)): ?>
                <p class="lead">What are you looking for?</p>
            <?php elseif (empty($results)): ?>
                <p class="lead">No results returned for "<?php echo $q?>"</p>
            <?php else: ?>
                <p class="lead">Search results for "<?php echo $q?>"</p>
            <?php endif ?>
        </div>
    </div>
    <?php if (!empty($results)) : ?>
    <div class="row">
        <?php
            $table = Table::begin($results[0], ['type', 'title', 'match', ''], 'table-secondary table-hover table-striped');
            foreach ($results as $r){
                echo $table->row($r, ['type', 'title', 'match'])->lastColumn('link', $r->url);
            }

            Table::end();
        ?>

    </div>
    <?php endif; ?>



</div>
