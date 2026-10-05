<?php

    use App\Conn\Read;
    use App\Conn\Update;

    $Read ??= new Read();

    $Read->exeRead(DB_POSTS, 'WHERE post_name = :nm AND post_status = 1', "nm={$URL[1]}");
    if (!$Read->getResult()) {
        require REQUIRE_PATH . '/404.php';

        return;
    }

    extract($Read->getResult()[0]);
    $Update ??= new Update();
    $UpdateView = ['post_views' => $post_views + 1, 'post_lastview' => date('Y-m-d H:i:s')];
    $Update->exeUpdate(DB_POSTS, $UpdateView, 'WHERE post_id = :id', "id={$post_id}");

    $Read->fullRead(
        'SELECT category_title, category_name FROM ' . DB_CATEGORIES . ' WHERE category_id = :id',
        "id={$post_category}"
    );
    $PostCategory = $Read->getResult()[0];

    $Read->fullRead(
        'SELECT user_name, user_lastname, user_thumb FROM ' . DB_USERS . ' WHERE user_id = :user',
        "user={$post_author}"
    );
    $AuthorName = "{$Read->getResult()[0]['user_name']} {$Read->getResult()[0]['user_lastname']}";
    $AuthorThumb = "{$Read->getResult()[0]['user_thumb']}";
?>
<div class="top_conversion breadcrumbs">
	<div class="content">
		<p><a href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><?= SITE_NAME; ?></a> / <a
					href="<?= BASE; ?>/artigos/<?= $PostCategory['category_name']; ?>"
					title="Ver mais: <?= $PostCategory['category_title']; ?> em <?= SITE_NAME; ?>!"><?= $PostCategory['category_title']; ?></a>
			/ <?= $post_title; ?></p>
		<div class="clear"></div>
	</div>
</div>

<div class="container post_single">
	<div class="content">
		<div class="left_content">
			<div class="post_content">
				<header>
					<h1 class="title"><?= $post_title; ?></h1>
					<h2 class="tagline"><?= $post_subtitle; ?></h2>
					<p class="postby"><img src="<?= BASE . '/uploads/' . $AuthorThumb; ?>"
					                       alt="<?= $AuthorName; ?>"/> <strong
								class="text_color_red"><?= $AuthorName; ?></strong>
						<i class="icon-calendar"></i>
						<time datetime="<?= date('Y-m-d', strtotime($post_date)); ?>" pubdate="pubdate"><?= date(
                                'd/m/Y H\hi',
                                strtotime($post_date)
                            ); ?></time>
						<i class="icon-books"></i><b><?= $PostCategory['category_title']; ?></b><i
								class="icon-eye-plus"></i><b><?= $post_views; ?> views</b></p>
				</header>
                <?php
                    if ($post_video) {
                        echo "<div class='embed-container'>";
                        echo "<iframe id='mediaview' width='640' height='360' "
                            . "src='https://www.youtube.com/embed/{$post_video}?rel=0&amp;showinfo=0&autoplay=0&origin="
                            . BASE . "' frameborder='0' allowfullscreen></iframe>";
                        echo '</div>';
                    } else {
                        echo "<img class='cover' title='{$post_title}' alt='{$post_title}' src='"
                            . BASE . "/uploads/{$post_cover}'/>";
                    }
                ?>

                <?php
                    $WC_TITLE_LINK = $post_title;
                    $WC_SHARE_HASH = "CreditoParaBoasIdeias";
                    $WC_SHARE_LINK = BASE . "/artigo/{$post_name}";
                    require './_cdn/widgets/share/share.wc.php';
                ?>
				<div class="htmlchars">
                    <?= $post_content; ?>
				</div>
                <?php
                    require './_cdn/widgets/share/share.wc.php'; ?>

				<article class="post_comments">
                    <?php
                        if (APP_COMMENTS && COMMENT_ON_POSTS):
                            $CommentKey = $post_id;
                            $CommentType = 'post';
                            require './_cdn/widgets/comments/comments.php';
                        endif;
                    ?>
				</article>

				<div class="clear"></div>
			</div>
		</div><?php
            require REQUIRE_PATH . '/inc/sidebar.php'; ?>
		<div class="clear"></div>
	</div>
</div>

<?php
    $Read->exeRead(
        DB_POSTS,
        'WHERE post_status = 1 AND post_date <= NOW() AND post_category_parent = :ct AND post_id != :id '
        . 'ORDER BY post_date DESC LIMIT 4',
        "ct={$post_category_parent}&id={$post_id}"
    );
    if ($Read->getResult()) {
        echo '<section class="single_post_more">';
        echo "<div class='content'>";
        echo '<header class="site_header">';
        echo '<h1><b>Veja</b> Também!</h1>';
        echo '<p>Os artigos relacionados podem te interessar:</p>';
        echo '</header>';

        $BOX = 4;
        foreach ($Read->getResult() as $More) {
            extract($More);
            require REQUIRE_PATH . '/inc/post.php';
        }

        echo '<div class="clear"></div></div>';
        echo '</section>';
    }
?>
