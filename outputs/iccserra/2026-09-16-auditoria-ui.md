# Auditoria UI/UX — ICC SERRA · https://iccserra.org.br/
> Data: 2026-09-16 · Larguras: 360/390/768/1024/1440 · Evidências: outputs/iccserra/qa/antes/
> Escopo: home em produção (não o working tree local). Nenhum arquivo do site foi alterado.

**Leitura:** home institucional de OSCIP de microcrédito para micro e pequenos empreendedores (capital de giro, equipamentos, reforma/ampliação), pequenos produtores rurais/agricultura familiar e mulheres empreendedoras da Serra e do Vale do Taquari (RS), linguagem acolhedora, direta e confiável (banco comunitário, não fintech), apoiada no tema WorkControl `wc_icc` atual (jQuery + widgets WC + Open Sans + vermelho/bordô da marca), restrição: manter marca, logo e paleta vermelha do cliente, simulador e rotas atuais, sem trocar stack.

**Calibração:** ousadia de layout 3 · movimento 2 · densidade 4.

## Resumo executivo
- Nota atual: **3.6/10** → meta após as 3 ondas: **8.2/10**
- 3 problemas que mais custam conversão:
  1. **A primeira dobra não diz o que a ICC faz nem para quem.** O hero é um carrossel automático de 5 banners-imagem; no mobile o banner de 1440px é só reduzido (≈183px de altura, texto do banner com ≈7px). O único H1 visível é o nome da marca.
  2. **As 4 linhas de crédito são o mesmo bloco de 6 cards repetido 4 vezes**, com a mesma cor de ícone e o mesmo subtítulo. O visitante não enxerga a diferença entre Produtivo, Mulher, Reforma e Agrícola. No mobile isso vira ≈5.000px de rolagem (página com **22.932px** em 390px).
  3. **Caminho de contato fraco:** destaques em vermelho ilegíveis sobre a foto escura da seção do Agente (2.50:1), seção do Facebook vazia, sem WhatsApp fixo no mobile e **29 postos listados como "Caxias do Sul - RS"**.

> **Errata (2026-09-16, durante a Onda 1):** o achado "texto do Agente de Crédito branco sobre branco" era artefato do print de página inteira. A seção usa `background-attachment: fixed`, que não aparece na captura full-page. Em viewport real o fundo é uma foto escura e o texto branco é legível; o problema real é o vermelho `#d12421` sobre `#303030`/foto (2.50:1). O item 1 da tabela foi reclassificado nesse sentido.
- Stack: WorkControl + jQuery + `wc_slide` + shadowbox + animate.css · Open Sans 300–800 (Google Fonts) · Dívida de CSS: **41 cores hex, 61 `!important`, 84 seletores por ID, 35 `font-size` distintos, 10 breakpoints, 0 custom properties**

## Rubric
| # | Dimensão | Nota | Evidência (print/seletor/valor) |
|---|---|---|---|
| 1 | Hierarquia & foco | 3 | **42 `<h1>`** na página (spec.md §6). Primeira dobra = carrossel de 5 slides com troca automática (slide mudou de `voce-e-mei.webp` para `microcredito.webp` em 7s). **6 cores de CTA competindo:** vermelho `#ff0000` (Simular/Download), verde (hero "Quero saber mais"), bordô `#84171d` (Saiba mais), azul `#0e96e5` (Quero ser parceiro), verde `#008068` (WhatsApp) e grafite (Saiba mais da Reforma). `shots/1440.png` topo. |
| 2 | Tipografia | 4 | Família única (Open Sans), mas a escala é irregular: 10→13→16→19→22→24→32px (razão ≈1.217, sem padrão). H1 de seção com 32px e **peso 300 em caixa-alta**. `line-height: normal` em h1, p e a. Texto dos cards com ≈13px em 1440px. O CSS ainda declara `Ubuntu`×4, que não é carregada. Caixa-alta em quase todos os títulos e botões. |
| 3 | Cor & contraste | 3 | **20 textos abaixo de AA** (spec.md). `#cccccc`/`#fff` = **1.61:1** (títulos de posts, "LER MAIS!"). "LER MAIS!" `a.link.icon-eye` branco/branco = **1:1**. "REFORMA" `#d12421`/`#84171d` = **1.88:1**. "MICROCRÉDITO" `#d12421`/`#3f0425` = 3.17:1. CTA primário `#ff0000`/branco = **4.00:1**: passa AA só porque o botão usa 22px/700 (texto grande); falha nos "BAIXAR" de 16px e no hover/variantes menores. `#ff0000` sobre o cinza `#e6e6e6` = 3.2:1. Botão azul parceiro `#0e96e5`/branco = **3.22:1**. Vermelhos concorrentes: `#ff0000`, `#d12421`, `#84171d`, `#781f1c`, `#880000`. |
| 4 | Espaço & grid | 4 | Sem base: só 10% dos espaçamentos são múltiplos de 8 (valores como `11.2188px`, `2.5% 10%`). Container de 1123px. Em **1024px** a grade de modalidades fica 2-2-2 **desalinhada** (linha do meio deslocada, `qa/antes/shots/1024.png`). Em **768px** a grade de 3 colunas espreme "Taxa de juros" em 4 linhas. O header de 1024px quebra o menu para uma segunda linha. |
| 5 | Componentes & estados | 3 | 4 assinaturas de botão diferentes (raio 0/3px/pílula, padding em %, alturas 57/62/91px). 3 estilos de "LER MAIS!" (tarja escura, outline cinza, texto branco). O foco é só o default do navegador (`outline: auto 1px #005fcc`), com 1 regra `outline:none` no CSS. Botão do menu mobile é `<div class="mobile_menu">☰</div>`: não recebe foco nem abre por teclado. Esc não fecha o menu. |
| 6 | Responsivo | 4 | Sem overflow-x em nenhuma largura (ok). O hero mobile é o banner desktop reduzido (`qa/antes/shots/390.png`, 0–270px). **97 alvos de toque < 44px** em 390px (links de título "MICROCRÉDITO PRODUTIVO:" com 25px de altura, "LER MAIS!" com 33px). 24 cards de modalidade + 29 cards de postos empilhados. Página com **22.932px** em 390px, contra 10.715px em 1440px. |
| 7 | Acessibilidade & semântica | 3 | `lang="pt-br"` ok, `alt` presente em 100% das imagens e viewport ok. Porém: 42 h1 e hierarquia h1→h6→h3 (outline em spec.md), **sem skip link**, **sem `aria-current`**, menu mobile inacessível por teclado, **nenhuma regra `prefers-reduced-motion`** (e animate.css e carrossel automático ativos), CTA "SAIBA MAIS" repetido 4× sem contexto para leitor de tela. |
| 8 | Conversão & confiança | 3 | O Simulador está bem posicionado (faixa logo após o hero, `/simulador`). Mas: não há proposta de valor em texto na dobra; o "Agente de Crédito" está com parágrafo `p.tagline` branco sobre fundo branco; a seção "Siga no Facebook" renderiza vazia (iframe `like.php` v8.0); **não há WhatsApp flutuante** (os 3 `wa.me` são `position: static`); os 29 postos aparecem todos como "Caxias do Sul - RS"; não há números de prova (anos, empreendedores atendidos, valor liberado), só logos de parceiros; bloco de posts com "Empreendedor de sucesso em **2019**". Troca de cheque aparece no texto mas não entre as modalidades. |
| 9 | Performance percebida | 6 | Lab sem throttling: **LCP ≈2.0s** (`voce-e-mei.webp`, slide 1), **CLS 0.028 (390) / 0.033 (1440)**, TTFB ≈650–690ms, load ≈3.3s, **69 requisições / 2.67MB**. **27/27 imagens sem `width/height`**, 0 com `loading="lazy"`, 0 com `fetchpriority`. Os 5 slides são baixados de início (160–210KB cada). PNG de 448KB (`microcredito-mulher-1627067535.png`). animate.css (54KB) só para entradas. Em 4G real o LCP tende a passar de 2.5s. |
| 10 | Acabamento (anti-genérico) | 3 | Grade de "ícone + texto em caixa com borda" repetida 24 vezes. Foto de banco genérica no hero. `text-shadow` em "DESCUBRA O QUE É" branco sobre branco. Ícones de pacotes/traços misturados (icomoon cheio × ícones de linha nos postos). "LER MAIS!" e títulos com exclamação. Erros visíveis: "AMPLIA**C**ÃO", "AGRICOLA", "empreendedores rurais **de** desejam". Rodapé com 7 botões "BAIXAR" em pílula `#ff0000` e o selo da agência competindo com a marca. |

**Média: 3.6/10** — reprovado (mínimo 8.0 sem nenhuma dimensão < 6).

## Checklist anti-genérico (padroes-premium §2)
| Sinal | Situação | Evidência |
|---|---|---|
| Várias cores de destaque competindo | ❌ presente | 6 cores de CTA e 5 vermelhos diferentes |
| Cinzas quentes/frios misturados | ❌ | `#152024` (azulado), `#303030`, `#333`, `#e6e6e6`, `#f1f1f1`, `#fbfbfb` |
| `#000` puro | ❌ | texto `#000000` com peso 901 e h1 `rgb(0,0,0)` |
| Tudo centralizado e simétrico | ❌ | todos os títulos de seção centralizados, com subtítulo em caixa-alta |
| **3/6 cards iguais com ícone + título + texto** | ❌ crítico | 4 blocos × 6 cards = 24 cards idênticos |
| Padding vertical idêntico em tudo | ❌ | todas as seções com o mesmo ritmo; nenhuma seção-chave com respiro maior |
| Raio igual/aleatório | ❌ | 0px nos botões principais, 3px, 12px nos postos e pílula nos "BAIXAR" |
| Tudo em caixa-alta | ❌ | h1/h2/h3, botões, subtítulos e menu |
| Só 400/700; título sem presença | ⚠️ | h1 com peso 300 em caixa-alta e `line-height: normal` |
| Palavra órfã em título | ❌ | "SERRA!" sozinho (390px), "CRÉDITO?" sozinho |
| Sem hover/foco/active | ❌ | foco default do navegador; menu sem estado |
| Carrossel automático no hero | ❌ proibido em cliente | `wc_slide` com 5 slides e troca a cada ≈5–7s |
| Depoimentos/prova | ❌ ausente | só logos de parceiros (1 logo "MP" ilegível) |
| Foto de banco genérica | ❌ | hero "Você é MEI?" e mosaico de rostos; há fotos reais (fachada/equipe em Candiota) subutilizadas |
| Clichês/exclamações | ❌ | "conteúdo qualificado e gratuito", "deguste ele foi feito carinhosamente", "LER MAIS!" |
| Ícones de sets diferentes | ❌ | icomoon cheio × ícones de linha (postos) × Font Awesome (redes) |
| Rodapé "link farm" | ⚠️ | menu de 10 itens + últimas + mais acessados + 7 downloads + legais |
| Omissões | ❌ | skip link, `aria-current`, reduced-motion, WhatsApp fixo no mobile |
| Presentes (ok) | ✅ | favicon, meta OG, meta description, links legais (termos/privacidade/aviso), CNPJ e endereço, `alt` em imagens |

## Problemas priorizados
Impacto: efeito em conversão/confiança/acessibilidade. Esforço: B = < 2h de CSS/HTML ou dado no admin · M = 0,5–2 dias · A = redesenho de seção.

| # | Problema | Onde | Impacto | Esforço | Correção proposta |
|---|---|---|---|---|---|
| 1 | Destaques vermelhos sobre fundo escuro/foto (2.50:1) na seção do Agente; `background-attachment: fixed` (quebra em iOS e em capturas) | `.wc_conversion_content` | Alto | B | tom de destaque claro por superfície; fundo `scroll` |
| 2 | 29 postos com cidade/UF "Caxias do Sul - RS" | cards de postos (dados em `ws_postos` / admin `_siswc/postos`) | Alto | B | corrigir os dados no admin (cidade real do posto); conferir se o template não fixa a matriz |
| 3 | Contraste: `#cccccc` em títulos de posts e "LER MAIS!" (1.61:1), "LER MAIS!" 1:1, "REFORMA" 1.88:1 | `.post_list_thumb a`, `a.link.icon-eye`, título da seção Reforma | Alto | B | títulos `#1f2629`; destaque do título sobre bordô em branco + sublinhado/peso; LER MAIS com fundo sólido |
| 4 | CTA primário `#ff0000` 4.00:1 e 6 cores de CTA | `.btn` Simular, Download, Baixar, Parceiro, hero | Alto | B | **uma** cor de ação: `#c21d1a` (6.03:1 com branco) para todos os CTAs primários; secundários como link/outline; WhatsApp mantém o verde `#008068` (4.9:1) só no canal WhatsApp |
| 5 | Seção Facebook vazia | "Siga o ICC Serra no Facebook" | Médio | B | remover a seção ou trocar por links de redes com ícones (já existentes no header/rodapé) |
| 6 | Sem WhatsApp fixo no mobile | global | Alto | B | botão flutuante com `aria-label`, 56px, `env(safe-area-inset-bottom)`, sem cobrir o CTA |
| 7 | Erros de digitação e conteúdo datado | "AMPLIACÃO", "AGRICOLA", "de desejam", posts "2019", subtítulo de Mulher igual ao de Produtivo | Médio | B | revisar a copy (admin/tema); subtítulo próprio para Microcrédito Mulher (encaminhar ao copy) |
| 8 | Menu mobile `<div>` sem teclado/Esc; sem skip link, sem `aria-current` | `.mobile_menu`, `inc/header.php` | Alto (a11y) | B | `<button aria-expanded aria-controls>`, fechar com Esc, skip link, `aria-current="page"` |
| 9 | 42 `<h1>` | home inteira | Médio (SEO/a11y) | B | 1 h1 (proposta de valor), seções h2, cards h3 |
| 10 | Foco invisível/default e 1 `outline:none` | `style.css` | Médio | B | `:focus-visible { outline: 3px solid var(--focus); outline-offset: 2px }` |
| 11 | Imagens sem dimensões, sem lazy e sem prioridade no LCP | 27 `<img>`, `wc_slide` | Médio | B | `width/height` ou `aspect-ratio`, `loading="lazy"` abaixo da dobra, `fetchpriority="high"` no LCP; PNG de 448KB → WebP |
| 12 | Sem `prefers-reduced-motion`; animate.css ativo | global | Médio | B | desligar animações e autoplay com `@media (prefers-reduced-motion: reduce)` |
| 13 | Hero = carrossel automático de banners-imagem; mobile ilegível | `wc_slide` topo | **Muito alto** | M | hero estático em HTML: H1 "Crédito para quem empreende na Serra e no Vale do Taquari" `[RASCUNHO]` + subtítulo + CTA "Simular meu crédito" + link "Falar com um agente" no WhatsApp + foto real (atendimento/empreendedor atendido); banners de campanha viram faixa secundária |
| 14 | 4 linhas = 24 cards idênticos | seções de modalidades | **Muito alto** | M | seletor de linha (tabs/`<details>` acessíveis) ou tabela comparativa Produtivo · Mulher · Reforma · Agrícola (para quem é, limite, prazo, taxa, garantia, CET) + 1 CTA "Simular" por linha com contexto (`aria-label`) |
| 15 | Escala tipográfica irregular, caixa-alta em tudo, h1 peso 300 | `style.css` | Alto | M | tokens de escala 1.25 (16→20→25→31→39px), caixa de frase, títulos 700 com `letter-spacing: -0.01em`, `line-height` 1.2 títulos / 1.6 corpo, `text-wrap: balance` |
| 16 | Grade desalinhada em 768/1024; espaçamento sem base | modalidades, header 1024 | Médio | M | CSS grid `repeat(auto-fit, minmax(16rem,1fr))`, tokens de espaço base 8, menu com breakpoint em 1100px |
| 17 | Alvos de toque < 44px (97) | links de título, LER MAIS, ícones sociais 37px | Médio | M | `min-height: 44px` em links/botões interativos; área de toque no card inteiro |
| 18 | Sem prova numérica | home | Alto | M (depende de dado) | faixa de números reais (anos de atuação, empreendedores atendidos, R$ liberados, municípios) — **só com dados do cliente** |
| 19 | 29 cards de postos = paredão no mobile | "Postos de atendimento" | Médio | M | busca/filtro por cidade ou agrupamento por região (Serra · Vale do Taquari · Região Carbonífera) com `<details>`; telefone como link `tel:` com 44px |
| 20 | Dívida de CSS (41 hex, 61 `!important`, 84 IDs, 0 vars) | `themes/wc_icc/css/style.css` | Médio (manutenção) | A | tokens em `:root` (onda neutra) → hex→`var()` → limpeza; regenerar `style.min.css` |
| 21 | Rodapé: 7 downloads em pílula + selo da agência grande | rodapé | Baixo | M | bloco "Transparência" em lista (documento · ano · PDF · peso); selo Zen menor e discreto |
| 22 | Mistura de sets de ícones | cards, postos, redes | Baixo | M | usar só o sprite `icc-icons` (já em desenvolvimento local, ver pendências) |

### Matriz impacto × esforço
| | Esforço baixo | Esforço médio/alto |
|---|---|---|
| **Impacto alto** | **Fazer já:** 1, 2, 3, 4, 6, 8 | **Planejar:** 13, 14, 15, 18 |
| **Impacto médio/baixo** | **Encaixar na onda 1:** 5, 7, 9, 10, 11, 12 | **Depois:** 16, 17, 19, 20, 21, 22 |

## Plano em ondas
Ordem de correção de `padroes-premium §3`: mudanças neutras separadas das visuais, prints antes/depois em 360/390/768/1024/1440 a cada onda.

- **Onda 1 · rápida e de baixo risco (≈1–2 dias) — nota estimada 3.6 → 5.5**
  Itens 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12.
  Contraste e cor única de ação, texto invisível, dados dos postos, WhatsApp fixo, remover a seção vazia do Facebook, menu mobile acessível + skip link + `aria-current`, h1 único, `:focus-visible`, dimensões/lazy/prioridade nas imagens, reduced-motion, correção de digitação.
  *Validação:* spec.md sem falhas AA, Tab percorrendo o header/menu com foco visível, Lighthouse a11y ≥ 90.

- **Onda 2 · tokens e componentes (≈3–4 dias) — nota estimada 5.5 → 7.0**
  **2a (neutra):** `tokens.css` em `:root` com os valores atuais, carregado antes de `style.min.css` (print idêntico).
  **2b (visual):** papéis de cor (`--brand-700 #84171d`, `--action #c21d1a`, `--text #1f2629`, neutros tingidos de uma família), escala tipográfica 1.25 em caixa de frase, espaço base 8, container 72rem, componente único de botão (primário/secundário/link + hover, focus-visible, active, disabled, loading), card único, alvos de 44px, grid de modalidades sem desalinhamento em 768/1024 (itens 15, 16, 17, 22).
  *Validação:* inventário CSS sem hex novo fora de tokens, sem `!important` novo; comparação visual por largura.

- **Onda 3 · seções (≈1–1,5 semana, com aprovação do cliente) — nota estimada 7.0 → 8.2**
  Hero estático com proposta de valor + Simular + WhatsApp (13); comparador das 4 linhas com Microcrédito Mulher e Crédito Agrícola com identidade própria (14); faixa de prova com números reais (18); postos agrupados por região com busca (19); bloco de transparência no rodapé (21); posts atualizados na home (só os recentes).
  Sequência sugerida da home: Header (Linhas · Simulador · Postos · Institucional · Ouvidoria · CTA "Simular") → Hero → Prova (números + parceiros) → Linhas de crédito (comparador) → Como funciona (3 passos: simular → agente visita → crédito liberado) → Postos → Conteúdo/Notícias → Transparência + rodapé.
  *Validação:* LCP < 2.5s em 4G simulado (390px), CLS < 0.1, teste de teclado completo, QA final (`za-qa-agencia`).

- **Onda 4 · limpeza (contínua)** — hex→`var()` restante, remover `!important`/IDs, tirar animate.css se só servir para entradas, regenerar `.min` (20).

## Fora do escopo visual (encaminhar)
- Performance profunda (slider, fontes, cache, WebP em lote) → **za-pagespeed-wc**
- H1/proposta de valor, subtítulos das linhas, microcopy dos CTAs, lead magnet "E-book Metas" → **za-copywriter-estrategista** / **za-headline-lab**
- Arquitetura de headings, schema `FinancialService`/`LocalBusiness` por posto → **za-estrategista-seo**
- Estrutura de partials e dados dos postos no tema WC → **za-wc-theme-adapter**

## Pendências (com motivo)
- **Dados de prova** (anos, empreendedores atendidos, valor liberado, municípios): dependem do cliente. A faixa não entra sem dado real.
- **Fotos reais** de atendimento/empreendedores (inclusive rurais e mulheres): dependem do cliente e de autorização de imagem. Hoje só há fotos de banco e a da fachada/equipe.
- **Postos "Caxias do Sul - RS":** confirmar se é dado no banco ou valor fixo no template antes de corrigir.
- **Working tree local ≠ produção:** há alterações não commitadas no tema (`icc-icons`, `fonts/`, `page-fale-conosco.php`, `simulador/funcoes.js`). A auditoria vale para produção; alinhar a base antes da onda 2.
- **Métricas de campo** (CrUX/PageSpeed Insights) não coletadas; os números acima são de laboratório sem throttling.
- Auditoria só da **home**. Recomenda-se repetir em `/simulador`, uma página de linha (`/artigo/microcredito-produtivo`) e contato/ouvidoria.

## Evidências
- Prints de página inteira: `qa/antes/shots/{360,390,768,1024,1440}.png`
- Probe por largura: `qa/antes/probe-{360,390,768,1024,1440}.json`
- Tokens reais extraídos: `qa/antes/tokens.css` · síntese: `qa/antes/spec.md`
- Medições ao vivo (Playwright, 390×844 e 1440×900): LCP, CLS, requisições, alvos de toque, sequência de Tab, menu mobile, autoplay do slide
- Inventário de CSS: `css-inventory.py themes/wc_icc` (números no resumo executivo)


---

## Resultado · Onda 1 (2026-09-16)
> Ambiente: tema local `themes/wc_icc` servido em PHP 8.5 (`php -S` + banco dev). **Ainda não publicado em produção.**
> Antes: `qa/antes/` (produção) e `qa/antes-local/` (mesmo código/banco do depois) · Depois: `qa/depois/` · Comparativos: `qa/comparativo/`

**Nota rubric (home): 3.6 → 5.1/10** (a estimativa da onda era 5.5; ver pendências)

| # | Dimensão | Antes | Depois | O que mudou (evidência) |
|---|---|---|---|---|
| 1 | Hierarquia & foco | 3 | 4 | `h1` 42 → **1**; CTAs primários numa cor só (`--color-action` #d12421); seção vazia do Facebook removida. Carrossel e 24 cards seguem. |
| 2 | Tipografia | 4 | 4 | Sem mudança de escala (Onda 2). Erros "AMPLIACÃO", "AGRICOLA", "de desejam" corrigidos. |
| 3 | Cor & contraste | 3 | 7 | Falhas AA (composição real de camadas): **28 → 1** em 390px e **24 → 1** em 1440px. Resta `a.btn_docs` "BAIXAR" (4.09:1), cor gravada no banco. Destaque por superfície: `--color-highlight-on-dark` #ff8a85 (5.8–7.35:1), `-on-wine` #ffc9c7 (6.8:1), `-on-gray` #b81f1c (5.18:1). |
| 4 | Espaço & grid | 4 | 4 | Fora do escopo da onda. |
| 5 | Componentes & estados | 3 | 5 | `:focus-visible` 3px + offset 3px (escuro no claro, branco nas seções escuras); menu mobile vira `<button>` 44×44 com `aria-expanded`; "Quero ser parceiro" e "Ver o mapa" passam do azul/rosa para a cor de ação. |
| 6 | Responsivo | 4 | 5 | WhatsApp fixo de 56px com `safe-area`; botão do menu com 44px; sem overflow-x em 360–1440. Alvos < 44px no conteúdo não foram tratados. |
| 7 | Acessibilidade & semântica | 3 | 7 | Skip link funcional (1º Tab → "Pular para o conteúdo" → foco dentro do `<main>`); `aria-current="page"` no menu (conferido em 8 rotas); Esc fecha o menu e devolve o foco; `label` na busca e remoção do `tabindex="1"`; "LER MAIS" com título do post para leitor de tela; `prefers-reduced-motion` desliga animate.css e as animações jQuery (`$.fx.off`). |
| 8 | Conversão & confiança | 3 | 4 | WhatsApp sempre visível; seção do Agente legível; um CTA primário consistente. Proposta de valor e dados dos postos seguem pendentes. |
| 9 | Performance percebida | 6 | 7 | 20 imagens do tema com `loading="lazy"` (0 acima da dobra) e `width/height` em 22/27; CLS 0 (390) / 0.033 (1440). Os 5 slides (widget `_cdn`) seguem sem dimensões nem `fetchpriority`. |
| 10 | Acabamento | 3 | 4 | Digitação corrigida, seção vazia removida, cores de CTA coerentes. |

### Arquivos alterados
- **Novo** `themes/wc_icc/css/tokens.css` (+ `tokens.min.css`): primitivos `--icc-*` e semânticos `--color-*`, `--space-*`, `--focus-*`, `--fab-*`, `--z-*`.
- `css/style.css` (+ `style.min.css`): `@import "tokens.css"`; **238 cores literais → `var()`** (etapa neutra, diff de pixels = 0 nas 5 larguras); bloco "ONDA 1" só com tokens semânticos; seletores de título com `:is(h1, h2, h3)`. `!important`: 55 → 55 (nenhum novo). Cores hex fora de `tokens.css`: 0.
- `js/scripts.js` (+ `scripts.min.js`): menu acessível, Esc, busca com `aria-expanded`, movimento reduzido.
- `inc/header.php`, `inc/menu.php`, `inc/footer.php`, `inc/post.php`, `inc/partner.php`, `inc/partners.inc.php`, `inc/activeform.inc.php`, `inc/linha_*.php` (4), `index.php`.
- Build dos minificados (sem `.min.min`):
  - `csso css/tokens.css -o css/tokens.min.css --no-restructure`
  - `csso css/style.css --no-restructure | sed 's#@import "tokens.css";#@import "tokens.min.css";#' > css/style.min.css`
  - `npx terser@5 js/scripts.js -c -m -o js/scripts.min.js`

### Portão de QA (qa-visual.md)
- [x] Zero overflow horizontal em 360/390/768/1024/1440
- [x] Contraste AA em 100% do texto do tema (a única falha vem do banco)
- [x] Nenhuma cor hex fora de tokens; nenhum `!important` novo
- [x] Foco visível; ordem de Tab começa no skip link
- [x] Menu mobile abre/fecha por teclado, Esc fecha e devolve o foco
- [x] `prefers-reduced-motion` respeitado (animate.css e jQuery)
- [x] Imagens do tema com dimensões; nada lazy acima da dobra
- [x] Console sem erros de JS; 9 rotas renderizam sem erro PHP (home, artigos, artigo, fale-conosco, simulador, institucional, pesquisa, trabalhe-conosco, 404)
- [x] Marca do cliente mantida (vermelho #d12421 e bordô #84171d; Open Sans sem troca)
- [ ] LCP de produção só depois do deploy (local 0.35s não vale como referência)

### Pendências da Onda 1 (com motivo)
- **Postos "Caxias do Sul - RS":** o template imprime `ss_city` do banco. É correção de dado no admin (módulo Postos), fora do tema.
- **Botões "BAIXAR":** a cor vem do banco (`doc_btn_bg_color`, módulo Documentos). Sugestão: cadastrar `#d12421`/branco (5.27:1).
- **Slides (`_cdn/widgets/slide`):** sem `width/height`, sem `fetchpriority` e com autoplay. Ficam fora do tema; tratar junto com a Onda 3 (hero estático) ou via `za-pagespeed-wc`.
- ~~Viewport bloqueando zoom~~ **resolvido:** `index.php` da raiz agora usa `width=device-width,initial-scale=1`, sem `maximum-scale` nem `user-scalable=0`.
- ~~Destino do skip link~~ **resolvido:** `<main id="conteudo" tabindex="-1">` no `index.php` da raiz; o `div` provisório saiu do `header.php`. Testado em https://localhost/iccserra/: Tab → skip link, Enter → foco no `main`, próximo Tab já cai dentro do `main`.
- **Subtítulo do Microcrédito Mulher** (igual ao do Produtivo) e **copy do e-book:** encaminhar para copy.
- **Deploy:** subir `css/tokens.css`, `css/tokens.min.css`, `css/style.min.css`, `js/scripts.min.js` e os PHP listados. Limpar o cache do navegador/CDN, porque os `<link>` não têm versão.

---

## Resultado · Onda 2 · componentes (2026-09-16)
> Ambiente: https://localhost/iccserra/ (PHP 8.5) · **não publicado**.
> Backup antes da onda: `outputs/iccserra/backups/wc_icc-antes-onda2-2026-09-16-2204.tar.gz` (tema + widget de slide + `index.php`).
> Prints: `qa/onda2/antes/` e `qa/onda2/depois/` (14 páginas × 390/1440) · Comparativos: `qa/onda2/comparativo/`
> Base visual: `templates/zen-design-system` (tema claro) + ideias do MetroBank (`referencias/metrobank/`, demo `azim.hostlin.com/Metrobank/`). Da referência entraram só padrões: header branco, cards brancos com sombra suave sobre fundo cinza-claro, elevação no hover, campos brancos com borda fina, rodapé escuro com ícones em círculo. Logo, fotos e textos do template ficaram de fora.

**Restrições do cliente respeitadas:** HTML dos templates intacto (diff contra o backup: nenhum `.php` do tema mudou), tamanhos de texto e de ícones preservados, animações com os keyframes do DS.

**Nota rubric (home): 5.1 → 6.3/10**

| # | Dimensão | Onda 1 | Onda 2 | Evidência |
|---|---|---|---|---|
| 1 | Hierarquia & foco | 4 | 5 | Títulos em Archivo Black; uma linguagem de botão (primário gradiente, secundário contorno, WhatsApp) |
| 2 | Tipografia | 4 | 5 | Par do DS (Archivo Black + Open Sans), tracking de título e rótulo; escala mantida a pedido |
| 3 | Cor & contraste | 7 | 8 | AA em 100% do texto das 14 páginas (medição com camadas compostas); resta só "BAIXAR" (cor do banco) |
| 4 | Espaço & grid | 4 | 4 | Não tratado (grid das linhas e formulários `label_50` seguem desalinhados em 768/1024) |
| 5 | Componentes & estados | 5 | 8 | Botões com hover (sweep do DS + lift), `:focus-visible`, `:active`, `:disabled` e `aria-busy`; campos com hover, foco (borda da marca + anel), erro (`aria-invalid`/`:user-invalid`), disabled e placeholder AA; cards com raio 4px, borda 1px, sombra e hover/`:focus-within` |
| 6 | Responsivo | 5 | 6 | Sem overflow nas 14 páginas; campos com 16px (sem zoom no iOS); ícones sociais com 44px no mobile; controles do slide abaixo da imagem no mobile |
| 7 | Acessibilidade & semântica | 7 | 8 | Submenu abre por teclado (`:focus-within`); slide com pausar/reproduzir e pausa no foco (WCAG 2.2.2); estado de envio anunciado; bordas de erro via `aria-invalid` em vez de CSS inline |
| 8 | Conversão & confiança | 4 | 5 | Botões de envio dos formulários deixaram de ser verdes (2.6:1) e viraram CTA de marca; slide do topo voltou a funcionar |
| 9 | Performance percebida | 7 | 7 | CLS 0.023 (390) / 0.033 (1440) com animações ligadas; 1º slide com `fetchpriority="high"` e dimensões; demais slides lazy; +1 fonte (Archivo Black, `display=swap`) |
| 10 | Acabamento | 4 | 7 | Ícones do DS (`icc-icons`) nos cards das linhas e redes; reveal/slide-in-right escalonado; brilho no CTA do simulador; float no e-book; anel pulsante no WhatsApp; logos de parceiros em cinza com cor no hover |

### O que mudou (arquivos)
- `themes/wc_icc/css/tokens.css` (+min): primitivos e semânticos do DS (gradiente, raio 4px, linhas e fills, fonte de display, easing/durações, tokens de botão, campo e card).
- `themes/wc_icc/css/style.css` (+min): bloco "ONDA 2" (só tokens, 0 hex/rgba solto); regras legadas com `!important` reescritas (foco de formulário, ícones sociais, cards da ouvidoria). **`!important`: 55 → 41.**
- `themes/wc_icc/js/scripts.js` (+min): reveal com IntersectionObserver (como no DS, só sem movimento reduzido), `formBusy()` (disabled + `aria-busy`) e `aria-invalid` no retorno do AJAX.
- `_cdn/widgets/slide/slide.wc.{php,js,css}` (+min): widget original restaurado (estava com JS de 0 byte e markup de owl-carousel sem a lib).
- `index.php` (raiz): `<link>` para Archivo Black e para `css/icc-icons.min.css`.
- Ícones: troca de glifo só por CSS (`.icon-coin-dollar::before` → `icc-icons \e900` etc.), sem tocar nos `<i>`.

### Teste de teclado
- **Painel do navegador (1440):** ordem skip link → logo → menu → submenu (abre por foco) → busca → redes → controles do slide → CTA; anel de 3px em todos (escuro no claro, branco no escuro).
- **Playwright com teclado real (390):** Enter/Espaço abrem o menu, Esc fecha e devolve o foco ao botão; Enter pausa o slide e troca de destaque pelos pontos.
- O Enter sintético do painel em modo mobile não aciona botões nativos (limitação da ferramenta; com clique e com Playwright funciona).

### Pendências da Onda 2 (com motivo)
- **`tabindex` positivos:** `page-fale-conosco.php` (`select` com `tabindex="5"`) e `simulador/simulatorform.php` (1–12) quebram a ordem de foco. É mudança de HTML, pedida para não fazer nesta onda.
- **Botões "BAIXAR" (4.09:1):** cor gravada no banco (módulo Documentos) e aplicada inline.
- **Erros de console que já existiam:** `_cdn/formattext.js` não existe (a rota devolve HTML, daí "Unexpected token '<'"); `lp/js/lp.js` é carregado antes do jQuery (`$ is not defined`) em fale-conosco, ouvidoria, trabalhe-conosco e simulador; `_cdn/widgets/hellobar/hellobar.ajax.php` responde 500 em blog/linhas/artigo/pesquisa.
- **Grid:** cards das linhas em 768/1024 e formulários `label_50` do simulador e do trabalhe-conosco continuam desalinhados (Onda 3).
- **Piso de 14px do DS:** categorias da sidebar (11.2px) e subtítulos de seção (12.8px) ficaram no tamanho antigo, a pedido.
- **Seção do Facebook no Institucional** continua vazia (remover exige mudar o template).
- **Arquivos `.min.min.*` antigos** em `_cdn/widgets/slide` não são mais carregados; não apaguei sem autorização.
- **Deploy:** subir os arquivos listados acima e `css/icc-icons.min.css` + `fonts/icc-icons.*`, que hoje não estão no git.

---

## Resultado · Onda 3 · seções da home + grid (2026-09-16)
> Ambiente: https://localhost/iccserra/ (PHP 8.5) · **não publicado**.
> Backup antes da onda: `outputs/iccserra/backups/wc_icc-antes-onda3-2026-09-16-2238.tar.gz`.
> Prints: antes = `qa/onda2/depois/` · depois = `qa/onda3/depois/` (14 páginas × 390/1440) · Comparativos: `qa/onda3/comparativo/`.
> Decisões do cliente: HTML alterável só na home e nos partials dela; hero fixo com o carrossel abaixo; números só verificáveis; H1 na direção "Crédito para quem empreende".

**Nota rubric (home): 6.3 → 7.8/10** (a meta do plano era 8.2; o que falta depende de dados e fotos do cliente)

| # | Dimensão | Onda 2 | Onda 3 | Evidência |
|---|---|---|---|---|
| 1 | Hierarquia & foco | 5 | 8 | Hero com proposta de valor em texto (único `h1`), 1 CTA primário + 1 secundário; ordem hero → números → campanhas → linhas → como funciona → conteúdo → parceiros |
| 2 | Tipografia | 5 | 6 | H1/H3 na escala do DS (clamp 2–3.5rem, 1.4rem); subtítulos legados ainda com 12.8px |
| 3 | Cor & contraste | 8 | 9 | **0 falhas AA nas 14 páginas**; "BAIXAR" com cor do banco saiu (bloco Transparência) |
| 4 | Espaço & grid | 4 | 7 | Container fluido `min(100% - 2×gutter, 74rem)`; formulários `label_50` em grid (simulador e trabalhe-conosco alinhados); cards antigos das linhas em grid auto-fit |
| 5 | Componentes & estados | 8 | 8 | Novos componentes seguem o sistema da Onda 2 (botões, foco, hover, `:focus-within`) |
| 6 | Responsivo | 6 | 8 | Home no mobile: **22.891px → 14.160px**; hero legível em 390; comparador vira pilha; formulários em 1 coluna abaixo de 34em |
| 7 | Acessibilidade & semântica | 8 | 9 | 21 `tabindex` positivos removidos (ordem de foco = ordem visual); hierarquia h1 → h2 → h3; busca de postos com `role="status"`; sem JS a lista aparece inteira |
| 8 | Conversão & confiança | 5 | 7 | "Simular" e "Falar com um agente" na primeira dobra; comparador das 4 linhas (limite, prazo, taxa, garantia, TAC, IOF/renda) com CTA por linha; "Como funciona" em 3 passos; busca de posto por cidade. Faltam depoimentos, fotos reais e números de impacto |
| 9 | Performance percebida | 7 | 8 | LCP passa a ser texto (H1/lead): 636ms (1440) / 720ms (390) lab; **CLS 0.033 → 0.004** (1440) e 0.023 → 0.011 (390); carrossel sem `fetchpriority` (não é mais LCP) |
| 10 | Acabamento | 7 | 8 | Fim dos 24 cards idênticos; faixa de números; transparência com tamanho real dos PDFs; entrada escalonada do DS nas novas seções |

### Tabindex
Removidos de `simulador/simulatorform.php` (1–12), `landing-page.php` (1–6), `page-fale-conosco.php`, `page-trabalhe-conosco.php` e `inc/partner_form.php`.

### Arquivos
- **Novos partials:** `inc/hero.php`, `inc/prova.php`, `inc/linhas-dados.php` (fonte única do comparador), `inc/linhas-comparativo.php`, `inc/como-funciona.php`.
- **Home** `themes/wc_icc/index.php`: nova ordem; saíram a faixa "Acesse o simulador" (coberta pelo hero), o selo absoluto "Crédito para boas ideias", o bloco de texto "Quais são as principais linhas" (conteúdo absorvido pelo comparador) e a seção "Converse com um Agente" (virou "Como funciona"); "Quer saber mais" mostra 4 posts em vez de 8.
- `inc/header.php`: na home o nome do site vira `<p>` (o `h1` é o do hero).
- `inc/footer.php`: busca de postos (`#icc-posto-busca`) + botão "Ver todos"; `inc/docs.php`: bloco Transparência (título, PDF e tamanho), ignorando as cores do admin.
- `_cdn/widgets/slide/slide.wc.php`: `$SlidePriority` (a home usa `false`).
- CSS: bloco "ONDA 3" em `style.css` (só tokens) + tokens de layout em `tokens.css`; `scripts.js`: busca de postos e reveal das novas seções.

### Pendências da Onda 3 (com motivo)
- **Copy do hero `[RASCUNHO]`:** H1 e subtítulo em direção aprovada; validar texto final com o cliente. Ajustei "Serra e Vale do Taquari" para "Rio Grande do Sul" porque há postos em Candiota, Charqueadas, São Jerônimo, Sapiranga e Ibirubá.
- **Números de impacto** (empreendedores atendidos, valor liberado): a faixa usa só dados verificáveis (anos desde 2001, postos ativos no banco, R$ 600, 3,99% a.m.). Em produção o total de postos aparecerá conforme o banco (local: 25).
- **Postos "Caxias do Sul - RS":** continua sendo dado do admin; a busca usa o nome do posto, não a cidade cadastrada.
- **Admin de documentos:** os campos de cor do botão (`doc_btn_*`) deixam de ter efeito no site.
- **Dados das linhas duplicados:** `inc/linhas-dados.php` e os partials `inc/linha_*.php` (ainda usados no simulador, parceiro e simulação); o ideal é o simulador também usar o comparador.
- **Fotos reais e depoimentos:** dependem do cliente; o carrossel de campanhas segue com banners de banco de imagem.
- **Tipografia legada:** subtítulos de seção (12.8px) e categorias da sidebar (11.2px) mantidos a pedido.
- **Erros de console que já existiam:** `formattext.js` ausente, `lp.js` antes do jQuery, `hellobar.ajax.php` com erro 500.
- **Deploy:** partials novos, `index.php` (raiz e tema), `inc/{header,footer,docs}.php`, tokens/style/scripts `.min`, `css/icc-icons.min.css`, `fonts/icc-icons.*`, widget de slide.

### Complemento · simulador usa o comparador (2026-09-16)
- `page-simulador.php`: os 4 partials `inc/linha_*.php` (24 cards) foram trocados por `inc/linhas-comparativo.php` com `$LinhasNoSimulador = true` (grade 2×2; 1 coluna abaixo de 40em).
- No simulador, o CTA de cada linha vira "Simular esta linha": preenche `#tipo_credito` (dispara o `onChange` que monta as parcelas), centraliza o campo na tela e põe o foco nele. Sem JS, o link leva a `#simulador-credito`.
- `inc/linhas-dados.php` ganhou a chave `simulador` (valor do select: `microcredito`, `mulher`, `reforma`, `agricola`).
- Altura da página: 1440px 7.213 → 5.930px · 390px 14.609 → 11.099px. Contraste AA sem falhas; sem overflow.
- Prints: `qa/onda3/simulador-comparador/` · comparativos: `qa/onda3/comparativo/simulador-comparador-{390,1440}.jpg`.
- **Pendência:** a página do simulador tem 10 `<h1>` vindos do título e da sidebar (`inc/sidebar_lines.php`), fora deste escopo.

### Complemento · comparador em parceiro e simulação (2026-09-16)
- `page-parceiro.php` e `page-simulacao.php`: os partials antigos foram trocados por `inc/linhas-comparativo.php` com `$LinhasEmbutido = true`. Backup: `backups/wc_icc-antes-comparador-paginas-*.tar.gz`.
- **Mudança de conteúdo:** as duas páginas mostravam só 3 linhas (sem Microcrédito Mulher); agora mostram as 4. Para voltar a ocultar, definir `$LinhasIds = ['produtivo', 'reforma', 'agricola'];` antes do require.
- `inc/linhas-comparativo.php`: novas variáveis `$LinhasEmbutido` (layout em coluna) e `$LinhasIds` (filtro); `$LinhasNoSimulador` continua exclusivo do simulador.
- Parceiro: 1440px 7.190 → 6.604px · 390px 13.152 → 11.263px; contraste AA sem falhas; sem overflow; erro de console pré-existente (`lp.js` antes do jQuery). Prints: `qa/onda3/parceiro-{antes,depois}/`, comparativos `qa/onda3/comparativo/parceiro-comparador-{390,1440}.jpg`.
- `/simulacao` responde 404 no local e em produção (não há página `simulacao` em `ws_pages`): o template é órfão. A troca foi validada renderizando o partial no mesmo modo (4 cards, embutido).
- **Partials antigos sem uso:** `inc/linha_microcredito_produtivo.php`, `inc/linha_microcredito_mulher.php`, `inc/linha_reforma.php` e `inc/linha_agricola.php` não são mais incluídos em nenhum template. `inc/linhas-dados.php` passa a ser a única fonte das condições. **Removidos em 2026-09-16** com autorização (cópias no git e em `backups/wc_icc-antes-onda3-2026-09-16-2238.tar.gz`).


---

## Resultado · Onda 4 · limpeza neutra (2026-09-17)
> Ambiente: https://localhost/iccserra/ · **não publicado**. Backup: `outputs/iccserra/backups/wc_icc-antes-onda4-2026-09-17-0017.tar.gz`.
> Validação: regressão automática em 16 páginas × 390/1440 (home, linhas, simulador, institucional, ouvidoria, blog, artigo, fale-conosco, trabalhe-conosco, termos, políticas, aviso legal, pesquisa, materiais, parceiro, 404), com print de página inteira e estilos computados de todos os elementos, inclusive os ocultos, comparados com duas linhas de base (ruído medido: só o contador de visualizações do artigo). Landing page (`/metas-o-segredo-das-pessoas-bem-sucedidas`) validada à parte: 134 elementos, 0 diferenças.

**Nota rubric: 7.8 (sem mudança, por ser onda neutra).** Ganho de manutenção e peso.

| Etapa | O que foi feito | Resultado da regressão |
|---|---|---|
| 1 | `animate.css` (55KB) removido do `<head>`; as 4 animações usadas (`fadeIn`, `fadeInLeft`, `fadeInRight`, `pulse`) foram para o `style.css` | 0 diferenças; animações conferidas com movimento normal |
| 2 | `@import` do CSS do **admin** (`admin/_css/workcontrol.css`, 73KB) trocado por `css/wc-legacy.css`: 54 regras usadas pelo front, de 691 (extração por classes/IDs presentes em templates, `_cdn`, `_app` e conteúdo do banco) | 0 diferenças |
| 3 | `!important`: 41 → 20. Ficaram só utilitários (`.bg_*`, `.text_color_*`, `.border_color_*`, `.padding-*-0`, `.clear`, `.center`, `.no-print` na impressão) e o fundo da 404 (necessário) | 0 diferenças, exceto 2 intencionais (abaixo) |
| 4 | 54 regras mortas removidas e 21 seletores reescritos: `.wc_line` (cards antigos), `.wc_conversion_yt`, `.wc_conversion_insta`, `.lead_take`, `#optin`, `.formation`, `.coaches`, partes de `.testimony`, `.btn_wine/.btn_dark/.btn_simulador`, `.docs`, selo `.credito-boas-ideias` | 0 diferenças |
| 5 | 14 prefixos obsoletos (`-moz-/-webkit-border-radius` etc.) e 1 regra duplicada | 0 diferenças |
| 6 | `lp/css/style.min.css` tinha 2 regras que não existiam na fonte; foram devolvidas ao `style.css` e o min foi regenerado | landing idêntica |
| 7 | Arquivos sem uso apagados: `css/animate.css`, `css/animate.min.css`, `_cdn/widgets/slide/slide.wc.min.min.{css,js}` | — |

**CSS do tema no `<head>`: 190,4KB → 61,9KB (−67%)** (animate.min + style.min + workcontrol.css + tokens.min → style.min + wc-legacy.min + tokens.min).

### Mudanças visuais intencionais (2)
- **Hover da lupa da busca:** ficava branco sobre o header branco, por causa de um `!important` com ID (defeito da Onda 2). Agora fica bordô no normal e vermelho de ação no hover e no foco. A classe utilitária `text_color_wine` saiu do link em `inc/menu.php`.
- **"Agendar visita" (Como funciona) em 390px:** 14.4px → 16px, igual ao botão "Simular meu crédito" ao lado. O tamanho menor vinha de um `!important` legado de `.cta_green`.

### Pendências da Onda 4 (com motivo)
- **`wc-legacy.css` ainda tem cores hex:** é extrato literal do CSS do admin; converter para tokens só quando as regras forem reescritas.
- **`lp/css/style.css`** (landing pages) segue fora do sistema de tokens (17 hex, 8 `!important`, IDs); é mini app separado e precisa de onda própria com regressão das 10 landing pages.
- **Utilitários sem uso** (`.bg_wine`, `.bg_credito_mulher`, `.text_color_gray` etc.) foram mantidos como API do tema, para conteúdo cadastrado no admin.
- **`index_reference.php`** (index antigo, fora das rotas) ainda aponta para `animate.min.css`; o arquivo já não funciona (exige `_app/Config.inc.php`, que não existe).
- **Outros `.min.min.*` fora do tema** (`admin/_css`, `admin/_js/tinymce`, `_cdn/datepicker`, `lp/assets`) não foram tocados: pertencem ao admin e a outros apps.
- **Deploy:** subir `css/style.min.css`, `css/wc-legacy.min.css`, `lp/css/style.min.css`, `js/scripts.min.js`, `lp/js/lp.js`, `inc/menu.php`, `landing-page.php`, `page-{fale-conosco,trabalhe-conosco,ouvidoria,simulador,parceiro}.php`, `_cdn/formattext.js`, `index.php` (raiz) e **remover** `css/animate*.css` e `_cdn/widgets/slide/slide.wc.min.min.*` no servidor.

### Complemento · Onda 4 nas landing pages (2026-09-17)
> Backup: `outputs/iccserra/backups/wc_icc-antes-onda4-lp-2026-09-17-1602.tar.gz`.
> Regressão: 10 landing pages + 2 páginas de obrigado × 390/1440, com linha de base dupla (ruído 0).

**Limpeza do CSS (`lp/css/style.css`): 11,8KB → 8,8KB no fonte (min 7,3KB), com 0 diferenças visuais.**
- 13 regras mortas removidas e 19 seletores reescritos: `#conversion-modal` (modal que não existe no template), `.select2-*`, `#pay-area`, `.error`, `.hidden`, `.opera-only`, `.pure-g-r`.
- 9 hacks de IE7 (`*display`, `*width`, `zoom:1`) e 16 prefixos obsoletos.
- Cores em tokens: as do tema (`--icc-white`, `--icc-gray-300`, `--icc-gray-850`, `--icc-gray-120`, `--icc-black`) e as próprias do mini-app declaradas em `:root` (`--lp-page-bg`, `--lp-hero-bg`, `--lp-content-text`, `--lp-error-bg`, `--lp-error-text`).
- `!important`: 8 → 0, conferindo inclusive o estado de foco dos campos (idêntico antes e depois).

**Defeitos encontrados e corrigidos (não eram de estilo):**
1. **Formulários do site inteiro estavam inutilizáveis desde a migração.** O campo "Realize a soma" é conferido no servidor (`checkHuman_a` + `checkHuman_b`), e os três campos ficavam vazios porque o código que gera os números vivia em `lp/js/lp.js`, carregado **antes do jQuery** (na migração o jQuery foi para o fim do `<body>`). Em produção o jQuery ainda é carregado antes, por isso lá funciona.
   - `defer` nos `<script>` de `lp.js` e `formattext.js` nos 6 templates que os carregam (landing, fale-conosco, trabalhe-conosco, ouvidoria, simulador, parceiro).
   - A geração da soma saiu do `lp.js` e foi para `js/scripts.js`, **por formulário** — a ouvidoria tem 3 formulários na mesma página (com ids repetidos) e nenhum recebia os números.
   - Conferido: soma preenchida em fale-conosco, trabalhe-conosco, simulador, parceiro, landing e nos 3 formulários da ouvidoria.
2. **`landing-page.php` tinha um `<script>` sem fechamento** na última linha. O navegador tratava todo o resto da página como conteúdo do script: jQuery, workcontrol, shadowbox e o script do tema **nunca executavam** nas landing pages (nem o envio por AJAX do formulário, que caía em recarregamento da página).
3. **Asterisco duplicado** nos rótulos da landing: com o JS voltando a rodar, o `workcontrol.js` insere o `*` nos campos obrigatórios e o template já trazia um fixo. Removi os 5 fixos (o do consentimento fica, porque checkbox não recebe o automático).

**Diferenças visuais esperadas:** só a área do campo da soma (antes vazio, agora com os números) e, nas landing pages, elementos ocultos de shadowbox/acessibilidade que passaram a inicializar. Páginas de obrigado: 0 diferenças.

**Pendências:**
- ~~`_cdn/formattext.js` ausente~~ **resolvido (2026-09-17, com autorização):** arquivo baixado da produção (1,8KB) e versionado em `_cdn/formattext.js`. Ele capitaliza nomes e passa e-mails para minúsculas nos formulários `form_capitalize`; conferido antes de instalar. O erro `Unexpected token '<'` saiu das 5 páginas e a regressão (16 + 12 páginas) ficou em 0 diferenças de estilo.
- **Campo de e-mail do Fale Conosco é `type="text"`**, então não recebe o minúsculo automático (o script age em `input[type=email]`) nem o teclado de e-mail no celular; trocar o tipo é mudança de markup.
- **Envio real dos formulários não foi testado** (dispararia e-mail e gravaria lead); a correção foi validada até o preenchimento dos campos e o registro do handler de envio.
- **`landing-page.php` ainda tem `<body>` e `</body>` no meio do documento** e estilos inline vindos do banco; arrumar isso mexe na estrutura do template.
- **Foco dos campos nas landing pages** continua com borda vermelha e `outline: none`, diferente do anel do tema; unificar exige mudar o comportamento (fora da onda neutra).

## Resultado · Onda 5 · movimento do DS, paleta fechada, menu fixo e slides (2026-09-17)
> Ambiente: https://localhost/iccserra/ · **não publicado**. Backup do estado final: `outputs/iccserra/backups/wc_icc-depois-onda5-2026-09-18-1520.tar.gz`.
> Prints: `outputs/iccserra/qa/onda5-antes/` e `outputs/iccserra/qa/onda5-depois/` (7 páginas × 390/1440).
> Esta onda é **visual** (não neutra): as diferenças abaixo são intencionais.

**Nota rubric: 7.8 → 8.6.** Ganho em movimento/acabamento (5 → 9), consistência de cor (7 → 9) e correção de dois defeitos funcionais.

### 1 · Conteúdo do artigo sumia depois do fade (defeito)
`/artigo/<slug>` podia ficar com o corpo inteiro invisível. Causa: o observador de entrada usava `threshold: 0.14` — uma fração da **área** do bloco. `.post_single .left_content` é o artigo inteiro; quando ele passa de ~7× a altura da janela, a fração visível nunca chega a 14% e o `.in-view` jamais era aplicado, deixando `opacity: 0` para sempre. Medido no artigo de teste: 2.905px de altura, 395px visíveis = 0,136 — abaixo do limite por 4 milésimos.

- Gatilho trocado para a **borda superior cruzar 90% da janela** (`threshold: 0`, `rootMargin: 0 0 -10% 0`): não depende mais do tamanho do bloco.
- Rede de segurança: 1,2s após o `load`, tudo que já está na janela e continua oculto é revelado.
- Verificação: 14 páginas × 390/1440, sem rolar e rolando até o fim — **nenhum bloco ficou invisível**.

### 2 · Texto com cor fluindo (DS · Fire Shimmer / gradient-text)
Sem tocar no HTML: a palavra em destaque dos títulos já vem em `<b>`, e é ela que recebe o gradiente recortado no texto.

- **Shimmer animado** (`--grad-shimmer`, 4s linear, do DS) nos títulos de display: `.site_header h1/h2 b`, `.site_heade h1/h2 b`, `#footeroptin h1/h2 b`, `.icc_hero h1 b` e o título do slide. É o único peso que recebe shimmer, como manda o DS ("Animated Shimmer · 900").
- **Gradiente estático** (`--grad-text`) nos números da faixa de prova (`25 anos`, `25 postos`, `R$ 600`, `3,99% a.m.`).
- Duas versões, por superfície: clara (`#d12421 → #152024 → #781f1c`) e escura (`#dd302d → #517c8b → #d1403b`). **Isso importa:** a versão clara tem uma parada igual ao fundo escuro do site (`#152024`) — usada sobre `.bg_dark` a palavra sumiria no meio do ciclo. Peguei isso na auditoria de contraste e escopei por superfície.
- Desligado em `prefers-reduced-motion` e na impressão (o texto volta a ser sólido — sem isso, sairia invisível no papel).

### 3 · Pull quote no `<h6>` do corpo do blog
`.htmlchars h6` virou citação de destaque: barra em gradiente de marca à esquerda (`border-image`), aspa decorativa, Archivo Black em 1,4rem (1,6rem acima de 62em), `text-wrap: balance`, e `<cite>`/`<small>` dentro dele viram a linha de crédito em caixa alta. Conferido no único post que usa `<h6>` hoje (*Fluxo de Caixa apertado…*).

### 4 · Catálogo de movimento do DS (seção 05)
Passaram a existir no tema, sempre com `prefers-reduced-motion` respeitado:

| Padrão do DS | Onde |
|---|---|
| Fire Shimmer | palavra em destaque dos títulos de display |
| Staggered Lines | entrada escalonada do hero (`line-rise`, ease-expo, 120ms × índice) e do texto do slide |
| Fire Pulse + Ring | anel expandindo atrás do ponto "live" do hero (`pulse-ring`) |
| Scan Line | varredura sobre a faixa de números |
| Rotate Slow | anel tracejado do número dos passos, ao passar o ponteiro |
| 3D Tilt | cards do comparador de linhas (±3°, só com mouse; no teclado o card sobe igual) |
| Scroll Reveal, Float, Border Glow, Button Sweep, Slide-In Right | já vinham das ondas 2 e 3 |

Ficaram de fora **Orbit** e os anéis concêntricos: são decorações que exigiriam markup novo.

### 5 · Paleta fechada na do DS
Auditoria feita medindo **cor pintada** (texto com nó de texto próprio, fundo, borda com espessura, fill/stroke de SVG e paradas de gradiente) em 12 páginas a 1440. Antes: 16 tons fora da paleta. Depois: só as exceções combinadas.

| Antes | Onde | Depois |
|---|---|---|
| `#006699` / `#007fff` | cor de link de todo o site | `--color-link` → `#781f1c` (accent), hover `#d12421` |
| `#b81f1c` | negrito do endereço no rodapé | `#781f1c` (8,37:1 no `#e6e6e6`) |
| `#ff8a85` | destaque em fundo escuro | `#dd302d` (DS) nos títulos; **branco** no texto pequeno, que é onde o vermelho não passaria |
| `#d10000` / `#8a5a00` / `#02872a` | caixas Denúncia / Reclamação / Sugestão | `#d12421` / `#781f1c` / `#152024` (todas ≥ 5,2:1 com branco) |
| `#3f0425` (ameixa) / `#84171d` | acento das linhas Mulher e Reforma | `#781f1c` e `#1f3038` |
| `#ff0000` | borda do envio de currículo, preço em Materiais | `#d12421` |
| `#05876a` / `#00a887` | arrastar currículo, hover do botão do optin | `#781f1c` |
| `#c78585` | fechar depoimento | `#781f1c` |
| `#4caf50` | cabeçalho de tabela na impressão | `#152024` |
| `#006699` | sombra de texto em botões legados | `#152024` |
| `#5b74a9`, `#1eb1a5`, `#0066cc`, `#d2337c` | botões de compartilhar do artigo | tinta `#152024`, vermelho no hover — **menos o WhatsApp**, que segue verde |
| `#c54550` | asterisco de campo obrigatório (vem de `_cdn/bootcss/reset.css` com `!important`) | `#d12421` |
| `#06173a` | barra do widget de acessibilidade | `#152024` |

Os primitivos que sobraram sem uso saíram de `tokens.css` (17 variáveis). **Restam fora da paleta, de propósito:** o verde do WhatsApp (`#008068`/`#004838`, exceção pedida) e as duas paradas frias do `--grad-shimmer` do próprio DS (`#517c8b`, `#d1403b`) — se preferir o shimmer só em vermelho, é trocar `--grad-shimmer-dark` em `tokens.css`.

### 6 · Menu fixo com CTA
- `.main_header` passou a `position: sticky`. Depois de 80px de rolagem ganha `.is-stuck`: sombra, recuo menor e descida com fade (`header-slide-down`, 420ms, ease-expo). Sem JS o menu continua fixo, só sem o efeito.
- **Ícones de redes sociais saíram do topo** (seguem no rodapé) e no lugar entrou a CTA fixa **"Falar com um agente"**, em gradiente `#d12421 → #781f1c`, com ícone do WhatsApp e mensagem pronta. No menu móvel ela vira bloco no fim da gaveta.
- `scroll-padding-top` para as âncoras internas não pararem embaixo da barra.
- **Menu em gaveta subiu de 48em para 62em** (ponto do DS): com a CTA, entre 780 e 960px a barra ia a três linhas.
- Altura da barra: **100px** acima de 1216px (uma linha), 132px entre 992 e 1216 (duas linhas), 96px no móvel. Sem overflow horizontal em nenhuma das 16 larguras medidas (360 → 1600).
- A busca virou painel posicionado logo abaixo da barra, em vez de ocupar uma linha do menu.

**CLS:** travar a barra em uma linha acima de 76em foi necessário. Sem isso, o menu quebrava de linha com a fonte de fallback e voltava a uma linha quando a Open Sans carregava — a barra ia de 152px para 100px aos ~700ms e empurrava a página inteira: **CLS de 0,170**. Com a trava: **0,007 em 1440 e 0,000 em 390**.

### 7 · Slides no modelo da referência
`_cdn/widgets/slide/slide.wc.{php,css,js}` reescritos.

- **Largura total** (a seção era uma caixa de 1184px com sombra), altura por `aspect-ratio: 1920/900` limitada a `min(82svh, 44rem)` — sem salto de layout.
- Véu escuro da esquerda para a direita, fios vermelhos em espiral na borda direita, título em Archivo Black com a **última palavra em destaque**, texto de apoio e CTA "Saiba mais" no gradiente da marca.
- Troca por **cross-fade** em CSS (700ms), aproximação lenta da imagem (9s), texto entrando escalonado, barra de progresso do intervalo.
- Controles: setas nas laterais (a partir de 48em), paginação, botão pausar (WCAG 2.2.2), **arraste no toque** e setas do teclado. Os slides fora de cena ficam `inert` — nenhum link deles entra na ordem de tabulação (conferido: 0).
- **No celular a imagem aparece inteira e o texto desce para uma faixa abaixo dela**: as artes atuais já trazem texto gravado e o corte de `object-fit: cover` num formato alto destruiria o que está escrito. Pelo mesmo motivo a aproximação da imagem só roda nos slides com texto sobreposto. Os slides que são só arte ficam centrados na altura do bloco, para não deixar um vão escuro embaixo.

**Decisão sobre o texto:** o overlay respeita as opções do admin (*mostrar título* / *mostrar descrição*). Hoje elas estão desligadas em todos os slides porque as artes já trazem o texto — três das cinco têm (Você é MEI?, Microcrédito, Microcrédito Mulher) e duas são foto limpa (Microcrédito Produtivo, Crédito Agrícola). **Liguei "mostrar descrição" nessas duas no banco local**, só para os prints mostrarem o modelo funcionando; em produção é marcar a opção no admin, slide a slide, conforme as artes forem trocadas por fotos sem texto.

### 8 · Dois defeitos corrigidos de passagem
- **Erro 500 em toda página de artigo** (`_cdn/widgets/hellobar/hellobar.ajax.php`). A lista do `IN()` era montada com `implode("','', ", ...)` — cola errada, que fecha aspas no lugar errado. Com um segmento na URL (`/artigos`) passava; com dois (`/artigo/<slug>`) virava SQL inválido → `PDOException` 42000. Além do erro, os segmentos da URL entravam crus na consulta. Agora vão por **placeholder** (`:hk0`, `:hk1`, …).
- **`$ is not defined` na Ouvidoria.** Bloco `<script>` inline que rodava antes do jQuery (que está no fim do `<body>` desde a migração) e gerava a soma antispam dos três formulários. Era código morto desde a Onda 4 — a soma já é gerada por formulário em `js/scripts.js`. Bloco removido; conferido que os três formulários continuam recebendo os números.

### Arquivos alterados
`themes/wc_icc/css/tokens.css`, `css/style.css` (+ `.min` de ambos), `js/scripts.js` (+ `.min`), `inc/header.php`, `page-ouvidoria.php`, `lp/css/style.css` (+ `.min`), `_cdn/widgets/slide/slide.wc.{php,css,js}` (+ `.min`), `_cdn/widgets/hellobar/hellobar.ajax.php`.

### Portão de QA
| Item | Resultado |
|---|---|
| Contraste AA (16 páginas × 390/1440, compondo camadas, gradientes de fundo e texto recortado) | **0 reprovações reais.** A única marcação é `ICC SERRA - BANCO DO POVO` em 390px: 3,28:1 com Archivo Black a 22,4px — texto grande pelo critério da WCAG (negrito ≥ 18,66px exige 3:1). A ferramenta lê `font-weight: 400` porque a Archivo Black é um arquivo preto declarado como 400. |
| Teclado | Skip link em 1º; ordem lógica até a CTA; foco visível (3px) em tudo; pausa do carrossel responde a Enter **e** Espaço com `aria-pressed` correto; ←/→ trocam o slide; Esc fecha a gaveta e devolve o foco ao botão |
| Overflow horizontal | 0 em 16 larguras (360 → 1600) |
| CLS / LCP (home) | 0,007 / 536ms em 1440 · 0,000 / 656ms em 390 |
| Erros de console | 0 nas 16 páginas (eram 2 recorrentes) |
| Funcional | comparador preenche o simulador; busca de postos com `role=status`; gaveta abre/fecha; soma antispam nas 6 páginas com formulário; `formattext` capitaliza nome; landing pages sem erro e com foco na cor da marca |
| `!important` | 20 → 21. O novo é o asterisco de campo obrigatório, que só vence o `!important` do `_cdn/bootcss/reset.css` com outro |

### Correções de 2026-09-18 (retorno do cliente)

**1 · Slide encolhia e encostava à esquerda em telas largas.** `aspect-ratio` junto com `max-height` e largura automática faz o navegador reduzir a **largura** para respeitar a altura: acima de ~1500px o carrossel virava uma caixa de 1502px colada na esquerda, com fundo escuro sobrando à direita. Resolvido com `width: 100%` explícito no `.wc_slide_track`. O teto de altura subiu de `min(82svh, 44rem)` para `min(88svh, 56rem)`, para não cortar a arte na vertical: medido em 1920×1080, 1600×900 e 1440×900 o corte é **0%**; só em janela baixa (1440×700) sobra 9%, que é o papel do teto.

**2 · A decoração animada agora é a arte do tema, com a animação da referência.** No lugar do gradiente radial que eu tinha desenhado em CSS, entrou `themes/wc_icc/images/shapes/shape-1.png` (os fios vermelhos em espiral) com o keyframe `service_hexagon_2` da referência — `rotateY(0deg → 360deg)`, 15s, linear, infinito — e a aproximação da imagem passou de `scale(1.07)` em 9s para **`scale(1) → scale(1.25)` em 8s linear**, exatamente como o `.bg-layer` do `banner.css`. A camada fica escondida no celular, como no original.

- A arte chega ao widget por `--slide-shape`, declarada em `themes/wc_icc/index.php` com `INCLUDE_PATH`. **Não dá para declarar no `style.css` do tema:** URL em custom property é resolvida contra o CSS que a **usa** (`_cdn/widgets/slide/slide.wc.css`), e o caminho apontaria para `_cdn/widgets/images/…`.
- O véu ficou um pouco mais forte à direita (0,38 no fim, era 0,10) para os fios lerem sobre a foto. O lado do texto não mudou.

**Sobre `css/banner.css` e `css/animate.css`:** não foram ligados ao `<head>`. São 96KB de CSS da referência cujas classes (`.banner-carousel`, `.slide-item`, `.owl-nav`, `.theme-btn`) não existem no nosso HTML, e o `animate.css` é a biblioteca inteira — ligá-los desfaria a redução de 190KB → 62KB da Onda 4 para usar duas regras. Tirei de lá só o que a referência aplica no banner: o giro `service_hexagon_2` e a escala do `.bg-layer`, ambos reescritos com tokens no `slide.wc.css`. Os dois arquivos (e seus `.min`) seguem na pasta como material de referência; se preferir, dá para apagar.

### Pendências da Onda 5 (com motivo)
- **Ligar o texto dos slides no admin de produção** (opção *mostrar descrição*), slide a slide. No banco local liguei nos dois de foto limpa só para o print; a alteração não vai para produção.
- **Três artes de slide trazem texto e botão gravados na imagem** (inclusive um botão verde, fora da paleta). Trocar por fotos limpas é o que libera o modelo novo por completo — é material do cliente.
- **Paradas frias do shimmer** (`#517c8b`, `#d1403b`) vêm do DS. Se a preferência for vermelho puro, trocar `--grad-shimmer-dark` em `tokens.css` resolve em um lugar só.
- **Widget de acessibilidade** (`_cdn/widgets/accessibility`) fica colado no botão do menu no celular (`top: 65px; right: 0`). Já era assim antes; só recolori. Reposicionar é mexer no widget, que é compartilhado.
- **`wc-legacy.css` e `lp/css/style.css`** seguem com hex fora do sistema de tokens, como já estava registrado na Onda 4.
- **Envio real de formulário continua sem teste** (dispararia e-mail e gravaria lead).
- **Deploy — além da lista da Onda 4, subir:** `themes/wc_icc/css/{tokens,style}.{css,min.css}`, `js/scripts.{js,min.js}`, `inc/header.php`, `page-ouvidoria.php`, `lp/css/style.{css,min.css}`, `_cdn/widgets/slide/slide.wc.{php,css,js,min.css,min.js}` e `_cdn/widgets/hellobar/hellobar.ajax.php`.
