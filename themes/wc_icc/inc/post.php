<?php use App\Helpers\Check;

$HeadingTag = $PostHeadingTag ?? 'h1'; ?>
<article class="box box<?= (!empty($BOX) ? $BOX : 1); ?> post_list animate zoomIn ">
    <a class="post_list_thumb" href="<?= BASE; ?>/artigo/<?= $post_name; ?>" title="<?= $post_title; ?>">
        <img src="<?= BASE; ?>/uploads/<?= $post_cover; ?>" alt="<?= $post_title; ?>" title="<?= $post_title; ?>"
             width="<?= IMAGE_W; ?>" height="<?= IMAGE_H; ?>" loading="lazy" decoding="async"/>
    </a>

	<div class="post_list_content">
        <<?= $HeadingTag; ?>><a href="<?= BASE; ?>/artigo/<?= $post_name; ?>" title="<?= $post_title; ?>"><?= $post_title; ?></a></<?= $HeadingTag; ?>>
        <p class="tagline"><?= Check::chars((string)$post_subtitle, 120); ?></p>
        <a class="link icon-eye" href="<?= BASE; ?>/artigo/<?= $post_name; ?>" title="<?= $post_title; ?>">LER MAIS!<span class="sr_only">: <?= $post_title; ?></span></a>

    </div>
</article>
