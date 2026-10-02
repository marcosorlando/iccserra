# Spec visual — Pesquisa por credito em ICC SERRA

> URL: https://localhost/iccserra/pesquisa/credito · capturado em 2026-09-17 · larguras: 390, 1440px
> Extração automática (za-web-design). **Leia como evidência, não como decisão.** Valide nos prints em `shots/`.

## 1. Leitura rápida
- **Stack detectada:** jquery, workcontrol
- **Tokens já existentes no :root:** 0 (2 folhas cross-origin não lidas)
- **Cor primária provável:** `#06173a` com texto `#ffffff` → contraste **17.63:1** 
- **Tipografia:** display `Open Sans` · corpo `Open Sans` · razão média da escala ≈ **1.243**
- **Grade de espaço:** sem base clara (13% múltiplos de 8 · 47% de 4)
- **Container:** 1184px · **Header:** 100px, static, bg #ffffff
- **Overflow horizontal no mobile (390px):** não

## 2. Cor
### Fundos (peso = área)
| cor | peso |
|---|---|
| `#ffffff` | 6235.8 |
| `#152024` | 1818.2 |
| `#eeeeee` | 1296 |
| `#f4f4f1` | 1296 |
| `#e6e6e6` | 865.2 |
| `#303030` | 113.8 |
| `#fbfbfb` | 72.5 |
| `#d12421` | 68.5 |
| `#008068` | 3.1 |
| `#00b494` | 2.4 |

### Texto (peso = caracteres)
| cor | peso |
|---|---|
| `#555555` | 1075 |
| `#000000` | 910 |
| `#152024` | 798 |
| `#d12421` | 462 |
| `#cccccc` | 409 |
| `#006699` | 192 |
| `#b81f1c` | 185 |
| `#ffffff` | 125 |

### Bordas
| cor | peso |
|---|---|
| `#0000001a` | 1406.3 |
| `#ffffff29` | 394.8 |
| `#d12421` | 288 |
| `#ffffff` | 226.6 |
| `#eeeeee` | 167.9 |
| `#00000047` | 162.1 |

### Gradientes
- `radial-gradient(circle at 100% 50%, rgba(209, 36, 33, 0.14), rgba(0, 0, 0, 0) 60%), none`
- `linear-gradient(90deg, rgb(209, 36, 33), rgb(21, 32, 36))`

## 3. Tipografia
- Famílias (por caracteres): `Open Sans` (4380), `Archivo Black` (58)
- Fontes carregadas: Open Sans 300 normal; Open Sans 400 normal; Open Sans 600 normal; Open Sans 700 normal; Archivo Black 400 normal; icomoon 400 normal; icc-icons 400 normal
- Google Fonts: https://fonts.googleapis.com/css?family=Open%20Sans:300,400,600,700 https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap
- Pesos: 400, 700, 600, 800, 300, 900 · line-heights: normal, 1.20, 1.00, 1.30, 1.15

| tag | família · tamanho · peso · line-height · tracking · transform |
|---|---|
| a | `Open Sans | 19.2px | 600 | 1.00 | normal | none` |
| h1 | `Open Sans | 32px | 700 | normal | normal | none` |
| p | `Open Sans | 12.8px | 400 | normal | normal | none` |
| h2 | `Open Sans | 19.2px | 700 | normal | normal | uppercase` |
| label | `Open Sans | 16px | 700 | normal | normal | none` |
| h3 | `Open Sans | 18.72px | 700 | normal | normal | none` |
| button | `Open Sans | 14px | 800 | normal | 0.84px | uppercase` |

Escala de tamanhos: 11px → 13px → 16px → 19px → 22px → 32px

## 4. Espaço, forma, profundidade, movimento
- Espaçamentos mais usados: 10px, 20px, 12px, 8px, 6px, 15px, 11.8281px, 30px, 5px, 16px, 14.4px, 25px
- Gaps (flex/grid): 9.6px, 12px, 8px
- Raios: 4px×59, 999px×11, 50%×5, 3px×1
- Sombras:
  - `rgba(209, 36, 33, 0.22) 0px 0px 38.4px 0px` ×3
  - `rgba(0, 0, 0, 0.1) 0px 1px 0px 0px` ×1
  - `rgba(0, 0, 0, 0.3) 0px 2px 6px 0px, rgba(0, 0, 0, 0.2) 0px 6px 16px 0px` ×1
- Transições: 0.15s, 0.3s, 0.5s · easing: cubic-bezier(0.16, linear
- Breakpoints (media queries): max 768px, max 544px, max 960px, max 640px, min 544px, min 768px, max 1024px, min 1024px, max 1280px, max 1056px, max 928px, max 375px
- z-index em uso: 97, 1000, 9999

## 5. Componentes
### Botões (assinaturas mais comuns)
- ×5 — `bg #06173a · color #ffffff · radius 0px · pad 6px 10px · fs 19.2px · fw 600 · tt uppercase · border 2px #06173a · h 16px`
- ×1 — `bg #00000000 · color #84171d · radius 0px · pad 8px 8px · fs 19.52px · fw 400 · tt uppercase · border 0px #84171d · h 44px`
- ×1 — `bg #00000000 · color #ffffff · radius 4px · pad 8px 8px · fs 17.6px · fw 800 · tt uppercase · border 1px #00000000 · h 46px`
- ×1 — `bg #d12421 · color #ffffff · radius 4px · pad 14px 56.0156px · fs 22.4px · fw 800 · tt uppercase · border 1px #00000000 · h 57px`
- ×1 — `bg #00000000 · color #152024 · radius 4px · pad 14.4px 28.8px · fs 14px · fw 800 · tt uppercase · border 1px #00000047 · h 50px`

### Campos de formulário
- ×2 — `bg #ffffff · border 1px #858585 · radius 4px · pad 11.2px 14.4px · h 46px · fs 16px`

### Links no texto
- `#d12421 · none · fw 600`
- `#cccccc · none · fw 300`
- `#006699 · none · fw 400`
- `#555555 · none · fw 600`

## 6. Estrutura e acessibilidade (alertas rápidos)
- h1 na página: **19** · landmarks: header×8, nav×3, main×1, aside×1, footer×1
- imagens sem alt: **0** · botões sem nome: **1** · campos sem label: **1**
- textos com contraste abaixo de AA: **1**
  - `p.site_title` "Banco do Povo" #000000 sobre #152024 = 1.26:1 (16px)

<details><summary>Outline de headings</summary>

- h1: ICC SERRA - Banco do Povo
- h1: Microcrédito Mulher
- h1: MICROCRÉDITO: FINANCIAMENTO PARA PEQUENOS NEGÓCIOS
- h1: 6 MOTIVOS PARA ADQUIRIR MICROCRÉDITO
- h1: IMPACTOS DO MICROCRÉDITO PARA O EMPREENDEDOR
- h1: 4 VANTAGENS DO MICROCRÉDITO COMO UMA INOVAÇÃO
- h1: PRECISA DE CRÉDITO PARA INVESTIR NO SEU NEGÓCIO?
- h1: MICROCRÉDITO: A SOLUÇÃO PARA SEU NEGÓCIO CRESCER
- h1: INOVAÇÕES NO MICROCRÉDITO PARA POPULAÇÃO DE BAIXA RENDA
- h1: CRÉDITO PARA PEQUENAS EMPRESAS
- h1: MICROCRÉDITO COMO INSTRUMENTO SOCIAL E ECONÔMICO
- h1: ICC SERRA - BANCO DO POVO
NO FACEBOOK
- h1: VER CATEGORIAS
- h1: MAIS VISTOS
- h1: Microcrédito Produtivo
- h1: A importância do microcrédito para o desenvolvimento econômico e socia
- h1: Troca de cheques
- h1: Crédito Agrícola
- h1: Crédito para Reforma ou ampliação
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
- h2: ÚLTIMAS DO SITE!
- h3: Fluxo de Caixa apertado? Veja como usar Capital de Giro com segurança
- h3: Banco do Povo inspira Candiota com apoio real ao pequeno empreendedor
- h3: Microcrédito Mulher
- h3: O QUE É MAIS VANTAJOSO CONSTRUIR OU REFORMAR?
- h2: MAIS ACESSADOS!
- h3: Microcrédito Produtivo
- h3: A importância do microcrédito para o desenvolvimento econômico e socia
- h3: Troca de cheques
- h3: Crédito Agrícola
- h2: CONECTE-SE CONOSCO
</details>


## 7. Uso ético da referência
Tokens (cor, escala, ritmo, raio, sombra) e **padrões** de layout são inspiração legítima.
**Não copiar:** logotipo, fotos, ilustrações, ícones proprietários, textos e marca registrada. Ver `references/extracao-referencia.md`.
