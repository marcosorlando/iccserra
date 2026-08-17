<?php use App\Helpers\Check; ?>
<article class="box box<?= (!empty($BOX) ? $BOX : 1); ?> post_list animate zoomIn ">
    <a class="post_list_thumb" href="<?= BASE; ?>/artigo/<?= $post_name; ?>" title="<?= $post_title; ?>">
        <img src="<?= BASE; ?>/uploads/<?= $post_cover; ?>" alt="<?= $post_title; ?>" title="<?= $post_title; ?>"/>
    </a>

	<div class="post_list_content">
        <h1><a href="<?= BASE; ?>/artigo/<?= $post_name; ?>" title="<?= $post_title; ?>"><?= $post_title; ?></a></h1>
        <p class="tagline"><?= Check::chars((string)$post_subtitle, 120); ?></p>
        <a class="link icon-eye" href="<?= BASE; ?>/artigo/<?= $post_name; ?>" title="<?= $post_title; ?>">LER MAIS!</a>

    </div>
</article>
