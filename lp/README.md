# ICC Serra — Landing Pages de Microcrédito
## Guia de Deploy e Operação

Versão: 1.0 | Mai/2026  
Responsável técnico: time de TI / agência parceira ICC Serra

---

## 1. ESTRUTURA DE ARQUIVOS

```
outputs/lps/
├── assets/
│   ├── css/styles.css           — CSS compartilhado (tokens, componentes, responsivo)
│   ├── js/main.js               — JS mínimo (menu mobile, eventos GA4, scroll)
│   └── img/                     — pasta reservada para cópias locais otimizadas
├── microcredito-produtivo.html
├── microcredito-mulher.html
├── credito-agricola.html
├── credito-reforma-ampliacao.html
├── troca-de-cheques.html
└── README.md (este arquivo)
```

As imagens são referenciadas diretamente de `../../references/` (relativo à pasta `outputs/lps/`).
Antes do deploy, copie as imagens para `assets/img/` e atualize os `src` nas 5 páginas.

---

## 2. HOSPEDAGEM RECOMENDADA

Hospedar como subpastas do domínio principal:

| Arquivo HTML | URL de destino |
|---|---|
| `microcredito-produtivo.html` | `iccserra.org.br/microcredito-produtivo/` |
| `microcredito-mulher.html` | `iccserra.org.br/microcredito-mulher/` |
| `credito-agricola.html` | `iccserra.org.br/credito-agricola/` |
| `credito-reforma-ampliacao.html` | `iccserra.org.br/credito-reforma-ampliacao/` |
| `troca-de-cheques.html` | `iccserra.org.br/troca-de-cheques/` |

Cada subpasta deve ter o HTML renomeado para `index.html`.

Exemplo de estrutura no servidor:
```
public_html/
├── microcredito-produtivo/
│   ├── index.html
│   └── assets/ → (symlink ou cópia de assets/)
├── microcredito-mulher/
│   └── index.html
...
```

O CSS e JS podem ser servidos de um caminho compartilhado. Se cada LP ficar em pasta separada, copie a pasta `assets/` para cada subpasta, ou use caminhos absolutos (`/assets/css/styles.css`).

### Opção alternativa: WordPress
Se o site roda em WordPress, publique cada LP como página com template "blank" (sem header/footer do tema) e cole o HTML no editor de código. O CSS pode ser enfileirado via `wp_enqueue_style` com handle único `iccserra-lps`.

---

## 3. TROCAR A URL DO SIMULADOR

A URL do simulador aparece em dois lugares em cada HTML:

**a) Atributo `href` dos botões CTA:**
```
href="https://iccserra.org.br/simulador"
```

**b) Atributo `data-simulador-url` (para rastreamento futuro):**
```
data-simulador-url="https://iccserra.org.br/simulador"
```

**Como trocar em todos os arquivos de uma vez (linha de comando):**
```bash
find /caminho/para/lps -name "*.html" \
  -exec sed -i 's|https://iccserra.org.br/simulador|https://NOVA-URL-AQUI|g' {} \;
```

**Ou busca e substituição no VS Code / editor:**
- Buscar: `https://iccserra.org.br/simulador`
- Substituir: `https://NOVA-URL-AQUI`
- Escopo: pasta `outputs/lps/`

---

## 4. ONDE COLAR AS TAGS DE TRACKING

Em cada HTML, localize os comentários no `<head>` (entre a meta description e o preconnect de fontes):

```html
<!-- GA4 PLACEHOLDER -->
<!-- GTM PLACEHOLDER -->
<!-- GOOGLE ADS CONVERSION TAG PLACEHOLDER -->
```

### GA4 (Google Analytics 4)
Substitua `<!-- GA4 PLACEHOLDER -->` por:
```html
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXXXXX');
</script>
```

### GTM (Google Tag Manager) — recomendado como alternativa ao GA4 direto
Substitua `<!-- GTM PLACEHOLDER -->` no `<head>` por:
```html
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-XXXXXXX');</script>
```

Substitua `<!-- GTM noscript PLACEHOLDER -->` logo após `<body>` por:
```html
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
```

### Google Ads — Conversion Tag
Substitua `<!-- GOOGLE ADS CONVERSION TAG PLACEHOLDER -->` por:
```html
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-XXXXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'AW-XXXXXXXXX');
</script>
```

A conversão de lead (clique no CTA) já está configurada via dataLayer no `main.js` com o evento `lead_cta_click`. No GTM, crie um Trigger de tipo "Custom Event" com nome `lead_cta_click` e aponte para a Tag de conversão do Google Ads.

---

## 5. EVENTOS DISPARADOS (dataLayer)

O arquivo `assets/js/main.js` já dispara os seguintes eventos para o dataLayer:

| Evento | Quando dispara | Parâmetros |
|---|---|---|
| `lead_cta_click` | Clique em qualquer botão CTA (`data-cta`) | `cta_location`, `cta_text`, `page_path` |
| `whatsapp_click` | Clique no botão flutuante do WhatsApp | `page_path` |
| `phone_click` | Clique em qualquer link `tel:` | `phone_number`, `page_path` |
| `scroll_75` | Usuário rola 75% da altura da página | `page_path` |

Para adicionar esses eventos como conversões no GA4 via GTM:
1. GTM > Triggers > Novo > Custom Event > nome = `lead_cta_click`
2. GTM > Tags > Nova > GA4 Event > Trigger = o trigger acima > Event Name = `lead_cta_click`

---

## 6. PLACEHOLDERS A PREENCHER ANTES DE PUBLICAR

### Conteúdo obrigatório (bloqueante para publicação)

- [ ] **URL do simulador**: substituir `https://iccserra.org.br/simulador` pela URL real (ver seção 3)
- [ ] **Foto agrícola**: `credito-agricola.html` usa `microcredito.webp` como fallback. Solicitar ao ICC foto real de produtor rural atendido. Substituir no `src` da imagem hero e remover o comentário `<!-- ASSET PENDENTE -->`.
- [ ] **Limites operacionais da Troca de Cheques**: valor mínimo/máximo por operação, prazo máximo dos cheques aceitos, quantidade máxima por vez. Inserir no Bloco 4 de `troca-de-cheques.html`.
- [ ] **Revisão jurídica da linha legal de Cheques**: o texto do rodapé de `troca-de-cheques.html` tem nota explícita de revisão pelo jurídico antes de publicar.

### Conteúdo recomendado (melhora conversão, não bloqueia)

- [ ] **Depoimentos** (3 por LP = 15 depoimentos total): todos marcados com `<!-- PLACEHOLDER DEPOIMENTO N -->`. Orientações de coleta estão diretamente nos arquivos HTML e nos arquivos de copy originais.
- [ ] **Prazo real de liberação**: confirmado como "5 a 10 dias úteis" — validar com operações se há cidade onde é mais rápido. Se sim, destacar.
- [ ] **Agentes mulheres**: LP Mulher menciona "agente da sua cidade" — se há agentes mulheres nas cidades-piloto, trocar para "uma agente" (feminino) no Bloco 3.
- [ ] **DAP/CAF na LP Agrícola**: confirmar se o produtor sem DAP/CAF é atendido. Se sim, destacar em negrito no Bloco 4 — é diferencial competitivo crítico para este público.
- [ ] **Imagens OG** (`og:image`): criar imagens 1200x630px para cada LP e hospedar. Atualizar o `content` de `<meta property="og:image">` em cada arquivo.
- [ ] **Tags de tracking**: colar GA4, GTM e Google Ads nos placeholders (ver seção 4).
- [ ] **Favicon**: atualizar o `<link rel="icon">` para o caminho correto no servidor (atualmente aponta para `../../references/iccserra-logomarca.png`).

---

## 7. CHECKLIST DE QA PRÉ-PUBLICAÇÃO

### Performance
- [ ] Rodar PageSpeed Insights (https://pagespeed.web.dev/) em cada LP — meta: score > 80 mobile
- [ ] Verificar que imagens têm `width` e `height` declarados (já configurado, validar após mover imagens)
- [ ] Confirmar que imagens hero têm `loading="eager"` e demais têm `loading="lazy"`
- [ ] Testar tempo de carregamento em 3G lento (Chrome DevTools > Network > Slow 3G)

### SEO
- [ ] Validar Schema.org em cada LP: https://validator.schema.org/ — colar a URL após publicar
- [ ] Submeter cada URL ao Google Search Console após publicação
- [ ] Verificar `<link rel="canonical">` aponta para a URL final correta em cada LP
- [ ] Confirmar H1 único por página (já configurado)
- [ ] Testar preview de compartilhamento OG: https://developers.facebook.com/tools/debug/

### Acessibilidade
- [ ] Verificar contraste: https://webaim.org/resources/contrastchecker/ — `#A8231D` sobre branco passa AA (ratio 5.1:1)
- [ ] Navegar por teclado (Tab) em cada LP — foco visível em todos os elementos interativos
- [ ] Testar com leitor de tela (NVDA/Windows ou VoiceOver/Mac) no hero e FAQ
- [ ] Confirmar `alt` em todas as imagens (já configurado)

### Funcionalidade
- [ ] Testar menu hamburger no mobile (375px, 414px)
- [ ] Testar todos os links CTA — devem apontar para o simulador real
- [ ] Testar link do WhatsApp flutuante — abrir conversa com mensagem pré-preenchida correta
- [ ] Testar link do telefone no header — deve abrir discador no mobile
- [ ] Testar FAQ accordion — abrir/fechar em mobile e desktop
- [ ] Testar scroll suave para âncoras internas
- [ ] Verificar eventos no dataLayer (Chrome DevTools > Console: `dataLayer`)

### Compliance Ad Grants
- [ ] Confirmar que a URL de destino dos anúncios é exatamente a URL da LP (sem redirect)
- [ ] Confirmar que cada LP tem `<title>` único e relevante para a keyword da campanha
- [ ] Confirmar que não há AdSense ou anúncios de terceiros nas páginas
- [ ] Confirmar que a conversão (clique no CTA) está configurada no Google Ads

### Mobile
- [ ] Testar em iPhone SE (375px) — menor breakpoint crítico
- [ ] Testar em Android padrão (360px)
- [ ] Confirmar que botões têm mínimo de 44px de altura (touch target — já configurado)
- [ ] Confirmar que texto tem mínimo 16px no mobile (já configurado — evita zoom automático do iOS)

---

## 8. MANUTENÇÃO E EVOLUÇÕES

### Trocar depoimentos
Localizar `testimonial-placeholder` no HTML e substituir por:
```html
<div class="testimonial-card">
  <blockquote>
    <p>Texto do depoimento aqui.</p>
  </blockquote>
  <p class="testimonial-author">Nome do Cliente</p>
  <p class="testimonial-meta">Cidade — Tipo de negócio</p>
</div>
```

### Adicionar nova cidade nos selos
Localizar o bloco `.trust-bar` ou os cards de credibilidade e atualizar o texto.

### Criar nova LP para nova linha de crédito
Copiar `microcredito-produtivo.html` como base, atualizar:
- `<title>`, `<meta description>`, `<link rel="canonical">`
- `og:title`, `og:description`, `og:url`
- Conteúdo de todos os blocos
- `href` do WhatsApp flutuante (mensagem pré-preenchida)
- `aria-current="page"` no nav (mover para o novo item)
- Schema.org JSON-LD

---

## 9. CONTATO TÉCNICO

Dúvidas sobre as LPs: agencia@zen.ppg.br  
Site cliente: https://iccserra.org.br  
Contato ICC Serra: iccserra@iccserra.org.br | (54) 3217-8787
