# Spec visual — ICC SERRA - Banco do Povo

> URL: https://localhost/iccserra/ · capturado em 2026-09-17 · larguras: 390, 1440px
> Extração automática (za-web-design). **Leia como evidência, não como decisão.** Valide nos prints em `shots/`.

## 1. Leitura rápida
- **Stack detectada:** jquery, owlCarousel, workcontrol
- **Tokens já existentes no :root:** 0 (1 folhas cross-origin não lidas)
- **Cor primária provável:** `#06173a` com texto `#ffffff` → contraste **17.63:1** 
- **Tipografia:** display `Open Sans` · corpo `Open Sans` · razão média da escala ≈ **1.217**
- **Grade de espaço:** sem base clara (10% múltiplos de 8 · 49% de 4)
- **Container:** 1123px · **Header:** 109px, static, bg #e6e6e6
- **Overflow horizontal no mobile (390px):** não

## 2. Cor
### Fundos (peso = área)
| cor | peso |
|---|---|
| `#152024` | 2893.6 |
| `#303030` | 2502.1 |
| `#e6e6e6` | 2353.1 |
| `#84171d` | 1263.6 |
| `#3f0425` | 1222.4 |
| `#ffffff` | 124.7 |
| `#000000b3` | 110.1 |
| `#d12421` | 97 |
| `#008068` | 52.8 |
| `#fc0000` | 28.6 |

### Texto (peso = caracteres)
| cor | peso |
|---|---|
| `#ffffff` | 2428 |
| `#555555` | 2230 |
| `#000000` | 884 |
| `#006699` | 621 |
| `#cccccc` | 568 |
| `#84171d` | 514 |
| `#b81f1c` | 426 |
| `#333333` | 195 |

### Bordas
| cor | peso |
|---|---|
| `#f1f1f1` | 646.9 |
| `#dedede` | 636.8 |
| `#d12421` | 389.8 |
| `#84171d` | 355.4 |
| `#888888` | 271.5 |
| `#cccccc` | 190.7 |

## 3. Tipografia
- Famílias (por caracteres): `Open Sans` (8457)
- Fontes carregadas: Open Sans 300 normal; Open Sans 400 normal; Open Sans 600 normal; Open Sans 700 normal; icomoon 400 normal
- Google Fonts: https://fonts.googleapis.com/css?family=Open%20Sans:300,400,600,700
- Pesos: 400, 700, 600, 300, 900 · line-heights: normal, 1.50, 1.34, 0.94

| tag | família · tamanho · peso · line-height · tracking · transform |
|---|---|
| a | `Open Sans | 16px | 400 | normal | normal | none` |
| h1 | `Open Sans | 32px | 700 | normal | normal | none` |
| p | `Open Sans | 16px | 400 | normal | normal | none` |
| h2 | `Open Sans | 32px | 300 | normal | normal | uppercase` |
| h3 | `Open Sans | 18.72px | 700 | normal | normal | none` |

Escala de tamanhos: 10px → 13px → 16px → 19px → 22px → 24px → 32px

## 4. Espaço, forma, profundidade, movimento
- Espaçamentos mais usados: 20px, 10px, 12px, 8px, 11.2188px, 5px, 11.0625px, 15px, 50px, 30px, 40px, 25px
- Gaps (flex/grid): —
- Raios: 12px×25, 20px×8, 3px×1, 999px×1
- Sombras:
  - `rgba(0, 0, 0, 0.2) 0px 0px 5px 1px inset` ×8
  - `rgb(209, 36, 33) 0px -3px 0px 0px inset` ×1
  - `rgba(0, 0, 0, 0.3) 0px 2px 6px 0px, rgba(0, 0, 0, 0.2) 0px 6px 16px 0px` ×1
- Transições: 0.3s · easing: ease
- Breakpoints (media queries): max 768px, max 544px, min 544px, min 768px, max 1280px, max 1024px, max 960px, min 1024px, max 1056px, max 928px, max 480px, min 960px
- z-index em uso: 97, 1000, 9999

## 5. Componentes
### Botões (assinaturas mais comuns)
- ×5 — `bg #06173a · color #ffffff · radius 0px · pad 6px 10px · fs 19.2px · fw 600 · tt uppercase · border 2px #06173a · h 16px`
- ×1 — `bg #00000000 · color #84171d · radius 0px · pad 8px 8px · fs 19.52px · fw 400 · tt uppercase · border 0px #84171d · h 43px`
- ×1 — `bg #d12421 · color #ffffff · radius 0px · pad 2.5% 10% · fs 22.4px · fw 700 · tt uppercase · border 0px #ffffff · h 58px`
- ×1 — `bg #d12421 · color #ffffff · radius 3px · pad 15px 30px · fs 19.2px · fw 700 · tt uppercase · border 0px #ffffff · h 62px`
- ×1 — `bg #008068 · color #ffffff · radius 0px · pad 2.5% 10% · fs 22.4px · fw 700 · tt uppercase · border 1px #008068 · h 91px`
- ×1 — `bg #d12421 · color #ffffff · radius 0px · pad 13.25px 53.0312px · fs 22.4px · fw 700 · tt uppercase · border 0px #ffffff · h 58px`

### Campos de formulário
- —

### Links no texto
- `#006699 · none · fw 400`
- `#555555 · none · fw 400`
- `#cccccc · none · fw 300`
- `#ffffff · none · fw 400`

## 6. Estrutura e acessibilidade (alertas rápidos)
- h1 na página: **1** · landmarks: header×12, nav×2, main×1, aside×1, footer×1
- imagens sem alt: **0** · botões sem nome: **0** · campos sem label: **0**
- textos com contraste abaixo de AA: **19**
  - `a` "Fluxo de Caixa apertado? Veja como usar " #ffffff sobre #ffffff = 1:1 (32px)
  - `a.link.icon-eye` "LER MAIS!" #ffffff sobre #ffffff = 1:1 (14px)
  - `span.sr_only` ": Fluxo de Caixa apertado? Veja como usa" #ffffff sobre #ffffff = 1:1 (14px)
  - `a` "Banco do Povo inspira Candiota com apoio" #ffffff sobre #ffffff = 1:1 (22.4px)
  - `a.link.icon-eye` "LER MAIS!" #ffffff sobre #ffffff = 1:1 (9.8px)
  - `span.sr_only` ": Banco do Povo inspira Candiota com apo" #ffffff sobre #ffffff = 1:1 (9.8px)
  - `a` "Microcrédito Mulher" #ffffff sobre #ffffff = 1:1 (22.4px)
  - `a.link.icon-eye` "LER MAIS!" #ffffff sobre #ffffff = 1:1 (9.8px)
  - `span.sr_only` ": Microcrédito Mulher" #ffffff sobre #ffffff = 1:1 (9.8px)
  - `p.site_title` "Banco do Povo" #000000 sobre #152024 = 1.26:1 (16px)

<details><summary>Outline de headings</summary>

- h1: ICC SERRA - Banco do Povo
- h2: Acesse nosso simulador, e realize uma simulação de crédito agora mesmo
- h2: DESCUBRA A MODALIDADE DE CRÉDITO IDEAL PARA VOCÊ
- h3: MICROCRÉDITO PRODUTIVO
- h3: MICROCRÉDITO MULHER
- h3: REFORMA E AMPLIAÇÃO
- h3: CRÉDITO AGRÍCOLA
- h2: ÚLTIMAS NOVIDADES DA ICC SERRA!
- h2: QUAIS SÃO AS PRINCIPAIS LINHAS DE CRÉDITO?
- h3: MICROCRÉDITO PRODUTIVO:
- h3: REFORMA & AMPLIAÇÃO:
- h3: CRÉDITO AGRÍCOLA:
- h3: TROCA DE CHEQUE:
- h2: ALGUNS DE NOSSOS PARCEIROS
- h2: Torne-se um parceiro ICC SERRA - BANCO DO POVO
- h2: DESCUBRA O QUE É MICROCRÉDITO PRODUTIVO
- h2: QUER SABER MAIS? ENTÃO VEJA TAMBÉM!
- h3: O QUE É MAIS VANTAJOSO CONSTRUIR OU REFORMAR?
- h3: MICROCRÉDITO: FINANCIAMENTO PARA PEQUENOS NEGÓCIOS
- h3: 6 MOTIVOS PARA ADQUIRIR MICROCRÉDITO
- h3: COMO QUALIFICAR O CLIENTE IDEAL
- h3: PASSO A PASSO PARA CRIAR UM PROCESSO DE VENDAS
- h3: EMPREENDEDOR: APRENDA A CRIAR SEU PLANO DE NEGÓCIO
- h3: COMO SE TORNAR UM EMPREENDEDOR DE SUCESSO EM 2019?
- h3: O EMPREENDEDOR COMO AGENTE DE INOVAÇÃO
- h2: E-BOOK METAS
- h2: POSTOS DE ATENDIMENTO BANCO DO POVO
INSTITUIÇÃO COMUNITÁRIA DE CRÉDITO
- h3: CAXIAS DO SUL
- h3: FLORES DA CUNHA
- h3: FARROUPILHA
- h3: BENTO GONÇALVES
- h3: GARIBALDI
- h3: CARLOS BARBOSA
- h3: COTIPORÃ
- h3: SERAFINA CORRÊA
- h3: GUAPORÉ
- h3: NOVA PRATA
- h3: NOVA ARAÇA
- h3: VERANÓPOLIS
- h3: IBIRUBÁ E REGIÃO
</details>


## 7. Uso ético da referência
Tokens (cor, escala, ritmo, raio, sombra) e **padrões** de layout são inspiração legítima.
**Não copiar:** logotipo, fotos, ilustrações, ícones proprietários, textos e marca registrada. Ver `references/extracao-referencia.md`.
