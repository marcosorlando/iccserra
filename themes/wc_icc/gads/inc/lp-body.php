<?php

declare(strict_types=1);

use App\Models\GadsLp;

/**
 * Corpo da LP de destino. O que muda entre as páginas vem de $Lp (page_data, editado no admin);
 * o que é igual em todas (estrutura, rótulos de seção, contato e rodapé institucional) fica aqui.
 *
 * Variáveis vindas do gads-lp.php: $GadsPage, $AssetsUrl, $E (escape).
 */
$Lp = GadsLp::data($GadsPage);
$Cta = GadsLp::ctaUrl($GadsPage);
$Slug = (string)$GadsPage['page_name'];
$Phone = '(54) 3217-8787';
$Links = GadsLp::published();
$Wa = 'https://wa.me/555432178787?text=' . rawurlencode('' !== $Lp['wa_text'] ? $Lp['wa_text'] : 'Olá! Quero saber sobre o crédito da ICC Serra.');

// Link de CTA: o destino é sempre o simulador (fim do funil)
$Btn = static function (string $class, string $id, string $label, string $extra = '') use ($Cta, $E): string {
    return sprintf(
        '<a href="%1$s" class="%2$s"%5$s data-cta="%3$s" data-simulador-url="%1$s">%4$s</a>',
        $E($Cta),
        $E($class),
        $E($id),
        $E($label),
        $extra
    );
};
?>
<header class="site-header" role="banner">
  <div class="header-inner">
    <a href="<?= BASE; ?>" class="header-logo" aria-label="ICC Serra — página inicial">
      <img src="<?= $AssetsUrl; ?>/img/iccserra-logo-h.png" alt="ICC Serra — Banco do Povo" width="160" height="36" loading="eager">
    </a>
<?php if ($Links) { ?>
    <nav class="header-nav" aria-label="Navegação principal">
<?php foreach ($Links as $Link) { ?>
      <a href="<?= BASE . '/' . $E($Link['name']); ?>"<?= $Link['name'] === $Slug ? ' aria-current="page"' : ''; ?>><?= $E($Link['label']); ?></a>
<?php } ?>
    </nav>
<?php } ?>
    <div class="header-actions">
      <a href="tel:+555432178787" class="header-phone" aria-label="Ligar para ICC Serra">
        <span aria-hidden="true">📞</span>
        <?= $Phone; ?>
      </a>
      <?= $Btn('btn-primary btn-header', 'header', $Lp['header_cta']); ?>

      <button class="hamburger" aria-label="Abrir menu" aria-expanded="false" aria-controls="header-nav">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </div>
</header>

<main>
  <section class="hero" aria-labelledby="hero-title">
    <div class="container">
      <div class="hero-inner">
        <div class="hero-content">
<?php if ($Lp['hero']['pill']) { ?>
          <div class="hero-overheadline">
            <span class="pill"><?= $E($Lp['hero']['pill']); ?></span>
          </div>
<?php } ?>
          <h1 class="hero-title" id="hero-title"><?= $E($Lp['hero']['title']); ?></h1>
          <p class="hero-subtitle"><?= $E($Lp['hero']['subtitle']); ?></p>
          <div class="hero-cta-group">
            <?= $Btn('btn-primary btn-primary--large', 'hero_primary', $Lp['hero']['cta']); ?>

<?php if ($Lp['hero']['cta2']) { ?>
            <?= $Btn('btn-outline', 'hero_secondary', $Lp['hero']['cta2']); ?>

<?php } ?>
          </div>
          <p class="hero-microcopy"><?= $E($Lp['hero']['microcopy']); ?></p>
        </div>
        <div class="hero-image-wrap">
          <img src="<?= $E(GadsLp::heroImage($GadsPage, $AssetsUrl)); ?>" alt="<?= $E($Lp['hero']['img_alt']); ?>" width="600" height="450" loading="eager">
        </div>
      </div>
    </div>
  </section>

<?php if ($Lp['trust']) { ?>
  <div class="trust-bar" role="region" aria-label="Diferenciais da ICC Serra">
    <div class="container">
      <div class="trust-bar-inner">
<?php foreach ($Lp['trust'] as $Item) { ?>
        <div class="trust-item">
          <span class="trust-icon" aria-hidden="true"><?= $E($Item['icon'] ?? ''); ?></span>
          <?= $E($Item['text'] ?? ''); ?>

        </div>
<?php } ?>
      </div>
    </div>
  </div>
<?php } ?>

<?php if ($Lp['pains']['items']) { ?>
  <section class="section section--bg-neutral" aria-labelledby="pains-title">
    <div class="container">
      <p class="section-tag">Identificação</p>
      <h2 class="section-title" id="pains-title"><?= $E($Lp['pains']['title']); ?></h2>
      <div class="pains-list">
<?php foreach ($Lp['pains']['items'] as $Item) { ?>
        <div class="pain-item">
          <span class="pain-icon" aria-hidden="true">×</span>
          <p class="pain-text"><?= $E($Item); ?></p>
        </div>
<?php } ?>
      </div>
<?php if ($Lp['pains']['transition']) { ?>
      <p class="pains-transition"><?= $E($Lp['pains']['transition']); ?></p>
<?php } ?>
      <div class="section-cta-subtle">
        <?= $Btn('btn-outline', 'pains_block', $Lp['pains']['cta']); ?>

      </div>
    </div>
  </section>
<?php } ?>

<?php if ($Lp['steps']['items']) { ?>
  <section class="section" aria-labelledby="steps-title">
    <div class="container">
      <p class="section-tag">Como funciona</p>
      <h2 class="section-title" id="steps-title"><?= $E($Lp['steps']['title']); ?></h2>
      <div class="steps-grid">
<?php foreach ($Lp['steps']['items'] as $I => $Item) { ?>
        <div class="step-card<?= 1 === $I ? ' step-card--highlight' : ''; ?>">
          <div class="step-number" aria-hidden="true"><?= $I + 1; ?></div>
          <h3 class="step-title"><?= $E($Item['title'] ?? ''); ?></h3>
          <p class="step-desc"><?= $E($Item['desc'] ?? ''); ?></p>
        </div>
<?php } ?>
      </div>
    </div>
  </section>
<?php } ?>

  <section class="section section--bg-neutral" aria-labelledby="line-title">
    <div class="container">
      <p class="section-tag"><?= $E($Lp['line']['tag']); ?></p>
      <h2 class="section-title" id="line-title"><?= $E($Lp['line']['title']); ?></h2>
      <div class="line-detail-grid">
        <div>
<?php if ($Lp['line']['who']) { ?>
          <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:1rem;color:var(--primary-dark)"><?= $E($Lp['line']['who_title']); ?></h3>
          <ul class="check-list" aria-label="<?= $E($Lp['line']['who_title']); ?>">
<?php foreach ($Lp['line']['who'] as $Item) { ?>
            <li class="check-item"><?= $E($Item); ?></li>
<?php } ?>
          </ul>
<?php } ?>
<?php if ($Lp['line']['use']) { ?>
          <h3 style="font-size:1.1rem;font-weight:700;margin:1.5rem 0 1rem;color:var(--primary-dark)"><?= $E($Lp['line']['use_title']); ?></h3>
          <ul class="check-list" aria-label="<?= $E($Lp['line']['use_title']); ?>">
<?php foreach ($Lp['line']['use'] as $Item) { ?>
            <li class="check-item"><?= $E($Item); ?></li>
<?php } ?>
          </ul>
<?php } ?>
<?php if ($Lp['line']['use_text']) { ?>
          <h3 style="font-size:1.1rem;font-weight:700;margin:1.5rem 0 1rem;color:var(--primary-dark)"><?= $E($Lp['line']['use_title']); ?></h3>
          <p style="font-size:var(--text-base);color:var(--text-muted);line-height:1.7;margin-bottom:1rem"><?= $E($Lp['line']['use_text']); ?></p>
<?php } ?>
<?php if ($Lp['line']['docs']) { ?>
          <div class="docs-section">
            <h4>Documentos necessários</h4>
            <ul class="doc-list" aria-label="Documentação necessária">
<?php foreach ($Lp['line']['docs'] as $Item) { ?>
              <li class="doc-item"><?= $E($Item); ?></li>
<?php } ?>
            </ul>
<?php if ($Lp['line']['docs_note']) { ?>
            <p class="docs-note"><?= $E($Lp['line']['docs_note']); ?></p>
<?php } ?>
          </div>
<?php } ?>
<?php if ($Lp['line']['prazo']) { ?>
          <div class="prazo-info">
            <strong>Prazo médio:</strong> <?= $E($Lp['line']['prazo']); ?>

          </div>
<?php } ?>
        </div>
        <div>
<?php if ($Lp['line']['cond']) { ?>
          <div class="conditions-box" role="region" aria-label="<?= $E($Lp['line']['cond_title']); ?>">
            <h3><?= $E($Lp['line']['cond_title']); ?></h3>
<?php foreach ($Lp['line']['cond'] as $Item) { ?>
            <div class="condition-item">
              <span class="condition-value"><?= $E($Item['value'] ?? ''); ?></span>
              <span class="condition-label"><?= $E($Item['label'] ?? ''); ?></span>
<?php if (!empty($Item['badge'])) { ?>
              <span class="condition-badge"><?= $E($Item['badge']); ?></span>
<?php } ?>
            </div>
<?php } ?>
          </div>
<?php } ?>
          <div style="margin-top:1.5rem">
            <?= $Btn('btn-primary', 'conditions_box', $Lp['line']['cta'], ' style="width:100%;justify-content:center"'); ?>

            <p class="hero-microcopy" style="margin-top:0.75rem;justify-content:center">Leva 2 minutos. Sem compromisso.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section" aria-labelledby="proof-title">
    <div class="container">
      <p class="section-tag"><?= $E($Lp['proof']['tag']); ?></p>
      <h2 class="section-title" id="proof-title"><?= $E($Lp['proof']['title']); ?></h2>
<?php if ($Lp['proof']['cards']) { ?>
      <div class="credibility-grid">
<?php foreach ($Lp['proof']['cards'] as $Item) { ?>
        <div class="cred-card">
          <div class="cred-icon" aria-hidden="true"><?= $E($Item['icon'] ?? ''); ?></div>
          <h3 class="cred-title"><?= $E($Item['title'] ?? ''); ?></h3>
          <p class="cred-text"><?= $E($Item['text'] ?? ''); ?></p>
        </div>
<?php } ?>
      </div>
<?php } ?>
<?php if ($Lp['proof']['testi']) { ?>
      <div class="testimonials-section">
        <h3 style="font-size:1.25rem;font-weight:700;color:var(--text)"><?= $E($Lp['proof']['testi_title']); ?></h3>
        <div class="testimonials-grid">
<?php foreach ($Lp['proof']['testi'] as $Item) { ?>
          <div class="testimonial-card">
            <p>"<?= $E($Item['text'] ?? ''); ?>"</p>
            <div class="testimonial-author"><?= $E($Item['author'] ?? ''); ?></div>
            <div class="testimonial-meta"><?= $E($Item['meta'] ?? ''); ?></div>
          </div>
<?php } ?>
        </div>
      </div>
<?php } ?>
    </div>
  </section>

<?php if ($Lp['faq']['items']) { ?>
  <section class="section section--bg-neutral" aria-labelledby="faq-title">
    <div class="container">
      <p class="section-tag">Dúvidas</p>
      <h2 class="section-title" id="faq-title"><?= $E($Lp['faq']['title']); ?></h2>
      <div class="faq-list" role="list">
<?php foreach ($Lp['faq']['items'] as $I => $Item) { ?>
        <details class="faq-item" role="listitem"<?= !empty($Item['open']) ? ' open' : ''; ?>>
          <summary class="faq-question"><?= $E($Item['q'] ?? ''); ?></summary>
          <div class="faq-answer">
            <p><?= $E($Item['a'] ?? ''); ?></p>
<?php if (!empty($Item['cta'])) { ?>
            <?= $Btn('faq-cta', 'faq_' . ($I + 1), $Item['cta']); ?>

<?php } ?>
          </div>
        </details>
<?php } ?>
      </div>
    </div>
  </section>
<?php } ?>

  <section class="cta-final" aria-labelledby="cta-final-title">
    <div class="container">
      <h2 class="cta-final-title" id="cta-final-title"><?= $E($Lp['final']['title']); ?></h2>
      <p class="cta-final-text"><?= nl2br($E(trim(str_replace("\r", '', $Lp['final']['text'])))); ?></p>
      <?= $Btn('btn-white', 'cta_final', $Lp['final']['cta']); ?>

    </div>
  </section>
</main>

<footer class="site-footer" role="contentinfo">
  <div class="container">
    <div class="footer-grid">
      <div>
        <p class="footer-col-title">ICC Serra — Banco do Povo</p>
        <p class="footer-text">
          CNPJ 05.036.783/0002-68<br>
          Rua Pinheiro Machado, 1707<br>
          Caxias do Sul — RS<br>
          <a href="tel:+555432178787" style="color:rgba(255,255,255,.75)">54 3217-8787</a><br>
          <a href="mailto:iccserra@iccserra.org.br" style="color:rgba(255,255,255,.75)">iccserra@iccserra.org.br</a><br>
          <a href="<?= BASE; ?>" style="color:rgba(255,255,255,.75)">iccserra.org.br</a>
        </p>
      </div>
<?php if ($Links) { ?>
      <div>
        <p class="footer-col-title">Linhas de crédito</p>
        <nav class="footer-links" aria-label="Outras linhas de crédito">
<?php foreach ($Links as $Link) { ?>
          <a href="<?= BASE . '/' . $E($Link['name']); ?>"<?= $Link['name'] === $Slug ? ' aria-current="page"' : ''; ?>><?= $E($Link['label']); ?></a>
<?php } ?>
        </nav>
      </div>
<?php } ?>
      <div>
        <p class="footer-col-title">Institucional</p>
        <nav class="footer-links" aria-label="Links institucionais">
          <a href="<?= BASE; ?>">Site oficial</a>
          <a href="<?= BASE; ?>/postos">Postos de atendimento</a>
          <a href="<?= BASE; ?>/quem-somos">Quem somos</a>
        </nav>
      </div>
    </div>
    <div class="footer-legal">
<?php if ($Lp['legal']) { ?>
      <p class="footer-legal-text"><?= $E($Lp['legal']); ?></p>
<?php } ?>
      <div class="footer-bottom">
        <span>© <?= date('Y'); ?> ICC Serra — Instituição Comunitária de Crédito da Serra. Todos os direitos reservados.</span>
        <span class="footer-oscip-seal">🏛️ OSCIP qualificada pelo Ministério da Justiça</span>
      </div>
    </div>
  </div>
</footer>

<a href="<?= $E($Wa); ?>" class="wa-float" target="_blank" rel="noopener noreferrer" aria-label="Falar com a ICC Serra pelo WhatsApp">
  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
</a>
