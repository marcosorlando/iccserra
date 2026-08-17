<ul class="main_nav_social">
    <?php
    $msg = urlencode(
        "Olá estou no site da ICC SERRA agora, e quero conversar com um Agente de Crédito "
        . "para ter mais informações sobre *MICROCRÉDITO*. \n\n *Aguardo um retorno em breve!*"
    );
    ?>
    <li>
        <a class="icon-whatsapp" target="_blank" title="Contate-nos pelo WhatsApp" href="https://wa.me/<?= SITE_ADDR_WHATS_TRIM; ?>?text=<?= $msg; ?>"></a>
    </li>
    <?php if (SITE_SOCIAL_FB) { ?>
        <li>
        <a target="_blank" title="<?= SITE_NAME; ?> no Facebook" class="icon-facebook" href="https://www.facebook.com/<?= SITE_SOCIAL_FB_PAGE; ?>"></a>
        </li><?php
    }

    if (SITE_SOCIAL_INSTAGRAM) {
        ?>
        <li>
        <a target="_blank" title="<?= SITE_NAME; ?> no Instagram" class="icon-instagram" href="https://www.instagram.com/<?= SITE_SOCIAL_INSTAGRAM; ?>"></a>
        </li><?php
    }

    if (SITE_SOCIAL_LINKEDIN) {
        ?>
        <li>
        <a target="_blank" title="<?= SITE_NAME; ?> no Linkedin" class="icon-linkedin" href="https://www.linkedin.com/company/<?= SITE_SOCIAL_LINKEDIN; ?>"></a>
        </li><?php
    }

    if (SITE_SOCIAL_TWITTER) {
        ?>
        <li>
        <a target="_blank" title="<?= SITE_NAME; ?> no X" class="icon-twitter" href="https://x.com/<?= SITE_SOCIAL_TWITTER; ?>"></a>
        </li><?php
    }

    if (SITE_SOCIAL_YOUTUBE) {
        ?>
        <li>
        <a target="_blank" class="icon-youtube2" title="<?= SITE_NAME; ?> no YouTube" href="https://www.youtube.com/user/<?= SITE_SOCIAL_YOUTUBE; ?>?sub_confirmation=1"></a>
        </li><?php
    }
    ?>
</ul>
