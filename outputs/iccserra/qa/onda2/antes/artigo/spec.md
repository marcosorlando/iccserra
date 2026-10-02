# Spec visual — Microcrédito Produtivo - ICC SERRA

> URL: https://localhost/iccserra/artigo/microcredito-produtivo · capturado em 2026-09-17 · larguras: 390, 1440px
> Extração automática (za-web-design). **Leia como evidência, não como decisão.** Valide nos prints em `shots/`.

## 1. Leitura rápida
- **Stack detectada:** jquery, workcontrol
- **Tokens já existentes no :root:** 0 (1 folhas cross-origin não lidas)
- **Cor primária provável:** `#06173a` com texto `#ffffff` → contraste **17.63:1** 
- **Tipografia:** display `Open Sans` · corpo `Open Sans` · razão média da escala ≈ **1.167**
- **Grade de espaço:** sem base clara (11% múltiplos de 8 · 48% de 4)
- **Container:** 1123px · **Header:** 109px, static, bg #e6e6e6
- **Overflow horizontal no mobile (390px):** não

## 2. Cor
### Fundos (peso = área)
| cor | peso |
|---|---|
| `#e6e6e6` | 2353.1 |
| `#ffffff` | 1889.2 |
| `#eeeeee` | 1305.3 |
| `#152024` | 1296 |
| `#303030` | 629.5 |
| `#fbfbfb` | 306.1 |
| `#d12421` | 159.7 |
| `#fc0000` | 28.6 |
| `#5b74a9` | 4.1 |
| `#1eb1a5` | 4.1 |

### Texto (peso = caracteres)
| cor | peso |
|---|---|
| `#666666` | 2647 |
| `#555555` | 1224 |
| `#006699` | 621 |
| `#d12421` | 542 |
| `#b81f1c` | 426 |
| `#cccccc` | 409 |
| `#888888` | 283 |
| `#ffffff` | 239 |

### Bordas
| cor | peso |
|---|---|
| `#dedede` | 636.8 |
| `#d12421` | 394.8 |
| `#eeeeee` | 315.4 |
| `#888888` | 271.5 |
| `#ffffff` | 213.6 |
| `#84171d` | 178.4 |

## 3. Tipografia
- Famílias (por caracteres): `Open Sans` (7034)
- Fontes carregadas: Open Sans 300 normal; Open Sans 400 normal; Open Sans 600 normal; Open Sans 700 normal; icomoon 400 normal
- Google Fonts: https://fonts.googleapis.com/css?family=Open%20Sans:300,400,600,700
- Pesos: 300, 400, 700, 600, 900 · line-heights: normal, 1.65, 1.50, 1.00, 1.20, 1.40

| tag | família · tamanho · peso · line-height · tracking · transform |
|---|---|
| a | `Open Sans | 16px | 400 | normal | normal | none` |
| h1 | `Open Sans | 19.2px | 700 | normal | normal | uppercase` |
| p | `Open Sans | 16.8px | 300 | 1.65 | 0.672px | none` |
| h2 | `Open Sans | 19.2px | 300 | normal | normal | none` |
| h3 | `Open Sans | 18.72px | 700 | normal | normal | none` |
| li | `Open Sans | 16.8px | 300 | 1.50 | 0.504px | none` |
| h4 | `Open Sans | 28.8px | 300 | normal | normal | none` |

Escala de tamanhos: 11px → 13px → 15px → 17px → 19px → 22px → 29px → 32px

## 4. Espaço, forma, profundidade, movimento
- Espaçamentos mais usados: 10px, 12px, 20px, 8px, 5px, 11.0625px, 30px, 15px, 11.2188px, 25px, 6px, 100px
- Gaps (flex/grid): —
- Raios: 12px×25, 5px×10, 20px×8, 3px×2, 25px×1, 999px×1
- Sombras:
  - `rgba(0, 0, 0, 0.2) 0px 0px 5px 1px inset` ×8
  - `rgba(0, 0, 0, 0.3) 0px 2px 6px 0px, rgba(0, 0, 0, 0.2) 0px 6px 16px 0px` ×1
- Transições: 0.3s · easing: ease
- Breakpoints (media queries): max 768px, max 544px, max 1280px, min 544px, min 768px, max 1024px, max 960px, min 1024px, max 1056px, max 928px, max 375px
- z-index em uso: 97, 1000, 9999

## 5. Componentes
### Botões (assinaturas mais comuns)
- ×5 — `bg #06173a · color #ffffff · radius 0px · pad 6px 10px · fs 19.2px · fw 600 · tt uppercase · border 2px #06173a · h 16px`
- ×2 — `bg #d12421 · color #ffffff · radius 3px · pad 15px 30px · fs 20.16px · fw 700 · tt uppercase · border 0px #ffffff · h 68px`
- ×1 — `bg #00000000 · color #84171d · radius 0px · pad 8px 8px · fs 19.52px · fw 400 · tt uppercase · border 0px #84171d · h 43px`
- ×1 — `bg #f45563 · color #ffffff · radius 0px · pad 8px 8px · fs 17.6px · fw 400 · tt none · border 0px #ffffff · h 40px`
- ×1 — `bg #d12421 · color #ffffff · radius 0px · pad 13.25px 53.0312px · fs 22.4px · fw 700 · tt uppercase · border 0px #ffffff · h 58px`

### Campos de formulário
- ×1 — `bg #ffffff · border 0px #000000 · radius 0px · pad 10px 10px · h 38px · fs 13.3333px`

### Links no texto
- `#006699 · none · fw 400`
- `#cccccc · none · fw 300`
- `#d12421 · none · fw 300`
- `#888888 · none · fw 600`

## 6. Estrutura e acessibilidade (alertas rápidos)
- h1 na página: **16** · landmarks: header×10, nav×3, main×1, aside×1, footer×1
- imagens sem alt: **0** · botões sem nome: **1** · campos sem label: **1**
- textos com contraste abaixo de AA: **20**
  - `p.site_title` "Banco do Povo" #000000 sobre #152024 = 1.26:1 (16px)
  - `a` "» Blog" #888888 sobre #fbfbfb = 3.43:1 (11.2px)
  - `a` "»» Agricultura" #888888 sobre #fbfbfb = 3.43:1 (11.2px)
  - `a` "»» Casos de Sucesso" #888888 sobre #fbfbfb = 3.43:1 (11.2px)
  - `a` "»» Controle Financeiro" #888888 sobre #fbfbfb = 3.43:1 (11.2px)
  - `a` "»» Microcrédito" #888888 sobre #fbfbfb = 3.43:1 (11.2px)
  - `a` "»» Microempreendedor" #888888 sobre #fbfbfb = 3.43:1 (11.2px)
  - `a` "»» Reforma" #888888 sobre #fbfbfb = 3.43:1 (11.2px)
  - `a` "» Linhas de Crédito" #888888 sobre #fbfbfb = 3.43:1 (11.2px)
  - `a` "Microcrédito Produtivo" #888888 sobre #ffffff = 3.54:1 (14px)

<details><summary>Outline de headings</summary>

- h1: ICC SERRA - Banco do Povo
- h1: Microcrédito Produtivo
- h2: Empréstimo de microcrédito produtivo é destinado para quem precisa de 
- h3: O que é Microcrédito?
- h3: Quem pode obter microcrédito?
- h3: Quais são as condições do crédito?
- h4: Limite:
- h4: Prazos:
- h4: Taxa de juros:
- h4: Garantias:
- h4: Taxas:
- h3: Simulação
- h3: Quais são os documentos necessário?
- h4: Solicitante do empréstimo:
- h4: Avalista:
- h4: Como solicitar o empréstimo de microcrédito?
- h1: DEIXE SEU COMENTÁRIO AQUI:
- h1: ICC SERRA - BANCO DO POVO
NO FACEBOOK
- h1: VER CATEGORIAS
- h1: MAIS VISTOS
- h1: Microcrédito Produtivo
- h1: A importância do microcrédito para o desenvolvimento econômico e socia
- h1: Troca de cheques
- h1: Crédito Agrícola
- h1: Crédito para Reforma ou ampliação
- h1: VEJA TAMBÉM!
- h1: Banco do Povo inspira Candiota com apoio real ao pequeno empreendedor
- h1: MICROCRÉDITO: FINANCIAMENTO PARA PEQUENOS NEGÓCIOS
- h1: 6 MOTIVOS PARA ADQUIRIR MICROCRÉDITO
- h1: COMO QUALIFICAR O CLIENTE IDEAL
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
</details>


## 7. Uso ético da referência
Tokens (cor, escala, ritmo, raio, sombra) e **padrões** de layout são inspiração legítima.
**Não copiar:** logotipo, fotos, ilustrações, ícones proprietários, textos e marca registrada. Ver `references/extracao-referencia.md`.
