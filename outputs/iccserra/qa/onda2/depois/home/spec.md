# Spec visual — ICC SERRA - Banco do Povo

> URL: https://localhost/iccserra/ · capturado em 2026-09-17 · larguras: 390, 1440px
> Extração automática (za-web-design). **Leia como evidência, não como decisão.** Valide nos prints em `shots/`.

## 1. Leitura rápida
- **Stack detectada:** jquery, workcontrol
- **Tokens já existentes no :root:** 0 (2 folhas cross-origin não lidas)
- **Cor primária provável:** `#06173a` com texto `#ffffff` → contraste **17.63:1** 
- **Tipografia:** display `Open Sans` · corpo `Open Sans` · razão média da escala ≈ **1.217**
- **Grade de espaço:** sem base clara (9% múltiplos de 8 · 48% de 4)
- **Container:** 1123px · **Header:** 100px, static, bg #ffffff
- **Overflow horizontal no mobile (390px):** não

## 2. Cor
### Fundos (peso = área)
| cor | peso |
|---|---|
| `#f4f4f1` | 3697.1 |
| `#152024` | 3668.2 |
| `#303030` | 2557.6 |
| `#ffffff` | 2361 |
| `#3f0425` | 1190.1 |
| `#84171d` | 1190.1 |
| `#e6e6e6` | 973.5 |
| `#000000b3` | 110.9 |
| `#d12421` | 97 |
| `#008068` | 55.1 |

### Texto (peso = caracteres)
| cor | peso |
|---|---|
| `#ffffff` | 2418 |
| `#555555` | 1796 |
| `#000000` | 884 |
| `#152024` | 639 |
| `#006699` | 621 |
| `#cccccc` | 568 |
| `#84171d` | 514 |
| `#b81f1c` | 426 |

### Bordas
| cor | peso |
|---|---|
| `#0000001a` | 843.5 |
| `#f1f1f1` | 646.9 |
| `#ffffff29` | 373.3 |
| `#d12421` | 288 |
| `#84171d` | 215.6 |
| `#00000047` | 133.1 |

### Gradientes
- `radial-gradient(circle at 100% 50%, rgba(209, 36, 33, 0.14), rgba(0, 0, 0, 0) 60%), none`

## 3. Tipografia
- Famílias (por caracteres): `Open Sans` (8010), `Archivo Black` (447)
- Fontes carregadas: Open Sans 300 normal; Open Sans 400 normal; Open Sans 600 normal; Open Sans 700 normal; Archivo Black 400 normal; icomoon 400 normal; icc-icons 400 normal
- Google Fonts: https://fonts.googleapis.com/css?family=Open%20Sans:300,400,600,700 https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap
- Pesos: 400, 700, 600, 800, 300, 900 · line-heights: normal, 1.20, 1.40, 1.15, 1.50, 1.34

| tag | família · tamanho · peso · line-height · tracking · transform |
|---|---|
| a | `Open Sans | 16px | 400 | normal | normal | none` |
| h1 | `Open Sans | 32px | 700 | normal | normal | none` |
| p | `Open Sans | 16px | 400 | normal | normal | none` |
| h2 | `Archivo Black | 32px | 400 | 1.15 | -0.64px | none` |
| h3 | `Open Sans | 18.72px | 700 | normal | normal | none` |

Escala de tamanhos: 10px → 13px → 16px → 19px → 22px → 24px → 32px

## 4. Espaço, forma, profundidade, movimento
- Espaçamentos mais usados: 20px, 10px, 12px, 11.2188px, 8px, 11.0625px, 15px, 50px, 5px, 30px, 40px, 25px
- Gaps (flex/grid): 9.6px, 8px
- Raios: 4px×105, 999px×12
- Sombras:
  - `rgba(0, 0, 0, 0.2) 0px 0px 5px 1px inset` ×8
  - `rgba(0, 0, 0, 0.16) 0px 24px 64px -19.2px` ×6
  - `rgba(209, 36, 33, 0.22) 0px 0px 38.4px 0px` ×4
  - `rgba(0, 0, 0, 0.1) 0px 1px 0px 0px` ×1
  - `rgb(209, 36, 33) 0px -3px 0px 0px inset` ×1
  - `rgba(0, 0, 0, 0.3) 0px 2px 6px 0px, rgba(0, 0, 0, 0.2) 0px 6px 16px 0px` ×1
- Transições: 0.3s, 0.15s, 0.5s · easing: cubic-bezier(0.16, linear
- Breakpoints (media queries): max 768px, max 544px, max 1280px, min 544px, min 768px, max 1024px, max 960px, min 1024px, max 1056px, max 928px, max 375px
- z-index em uso: 97, 1000, 9999

## 5. Componentes
### Botões (assinaturas mais comuns)
- ×6 — `bg #00000000 · color #000000 · radius 0px · pad 0px 0px · fs 13.3333px · fw 400 · tt none · border 0px #000000 · h 44px`
- ×5 — `bg #06173a · color #ffffff · radius 0px · pad 6px 10px · fs 19.2px · fw 600 · tt uppercase · border 2px #06173a · h 16px`
- ×2 — `bg #d12421 · color #ffffff · radius 4px · pad 13.25px 53.0312px · fs 22.4px · fw 800 · tt uppercase · border 1px #00000000 · h 55px`
- ×1 — `bg #00000000 · color #84171d · radius 0px · pad 8px 8px · fs 19.52px · fw 400 · tt uppercase · border 0px #84171d · h 44px`
- ×1 — `bg #d12421 · color #ffffff · radius 4px · pad 15px 30px · fs 19.2px · fw 800 · tt uppercase · border 1px #00000000 · h 57px`
- ×1 — `bg #008068 · color #ffffff · radius 4px · pad 28.0781px 112.312px · fs 22.4px · fw 800 · tt uppercase · border 1px #008068 · h 89px`

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
- textos com contraste abaixo de AA: **18**
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
- h3: Microcrédito Produtivo
- h3: Microcrédito Mulher
- h3: Reforma e Ampliação
- h3: Crédito Agrícola
- h2: Últimas novidades da ICC SERRA!
- h2: Quais são as principais linhas de crédito?
- h3: MICROCRÉDITO PRODUTIVO:
- h3: REFORMA & AMPLIAÇÃO:
- h3: CRÉDITO AGRÍCOLA:
- h3: TROCA DE CHEQUE:
- h2: Alguns de nossos Parceiros
- h2: Torne-se um parceiro ICC SERRA - BANCO DO POVO
- h2: DESCUBRA O QUE É MICROCRÉDITO PRODUTIVO
- h2: Quer saber mais? Então veja também!
- h3: O QUE É MAIS VANTAJOSO CONSTRUIR OU REFORMAR?
- h3: MICROCRÉDITO: FINANCIAMENTO PARA PEQUENOS NEGÓCIOS
- h3: 6 MOTIVOS PARA ADQUIRIR MICROCRÉDITO
- h3: COMO QUALIFICAR O CLIENTE IDEAL
- h3: PASSO A PASSO PARA CRIAR UM PROCESSO DE VENDAS
- h3: EMPREENDEDOR: APRENDA A CRIAR SEU PLANO DE NEGÓCIO
- h3: COMO SE TORNAR UM EMPREENDEDOR DE SUCESSO EM 2019?
- h3: O EMPREENDEDOR COMO AGENTE DE INOVAÇÃO
- h2: E-BOOK METAS
- h2: Postos de Atendimento BANCO DO POVO
Instituição Comunitária de Crédito
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
