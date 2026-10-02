# Spec visual — ICC SERRA - Banco do Povo

> URL: https://localhost/iccserra/ · capturado em 2026-09-17 · larguras: 390, 1440px
> Extração automática (za-web-design). **Leia como evidência, não como decisão.** Valide nos prints em `shots/`.

## 1. Leitura rápida
- **Stack detectada:** jquery, workcontrol
- **Tokens já existentes no :root:** 0 (2 folhas cross-origin não lidas)
- **Cor primária provável:** `#06173a` com texto `#ffffff` → contraste **17.63:1** 
- **Tipografia:** display `Archivo Black` · corpo `Open Sans` · razão média da escala ≈ **1.294**
- **Grade de espaço:** base **4px** (32% múltiplos de 8 · 63% de 4)
- **Container:** 1184px · **Header:** 100px, static, bg #ffffff
- **Overflow horizontal no mobile (390px):** não

## 2. Cor
### Fundos (peso = área)
| cor | peso |
|---|---|
| `#f4f4f1` | 4263.5 |
| `#ffffff` | 3813.8 |
| `#152024` | 3070.2 |
| `#e6e6e6` | 865.2 |
| `#303030` | 657.1 |
| `#000000b3` | 116.2 |
| `#d12421` | 95.5 |
| `#008068` | 16 |
| `#00000080` | 15 |
| `#d1242124` | 6.9 |

### Texto (peso = caracteres)
| cor | peso |
|---|---|
| `#555555` | 2351 |
| `#152024` | 1341 |
| `#ffffff` | 897 |
| `#cccccc` | 638 |
| `#006699` | 208 |
| `#b81f1c` | 185 |
| `#000000` | 148 |
| `#333333` | 119 |

### Bordas
| cor | peso |
|---|---|
| `#0000001a` | 1471.6 |
| `#ffffff29` | 559 |
| `#d12421` | 171.8 |
| `#333333` | 118.4 |
| `#00000047` | 82.8 |
| `#858585` | 51.2 |

### Gradientes
- `radial-gradient(960px 480px at 85% 15%, rgba(209, 36, 33, 0.22), rgba(0, 0, 0, 0) 60%), radial-gradient(640px 480px at 0% 100%, rgba(209, 36, 33, 0.14), rgba(0, 0, 0, 0) 60%), none`
- `radial-gradient(circle at 100% 50%, rgba(209, 36, 33, 0.14), rgba(0, 0, 0, 0) 60%), none`
- `linear-gradient(90deg, rgb(209, 36, 33), rgb(21, 32, 36))`

## 3. Tipografia
- Famílias (por caracteres): `Open Sans` (5762), `Archivo Black` (462)
- Fontes carregadas: Open Sans 300 normal; Open Sans 400 normal; Open Sans 600 normal; Open Sans 700 normal; Archivo Black 400 normal; icomoon 400 normal; icc-icons 400 normal
- Google Fonts: https://fonts.googleapis.com/css?family=Open%20Sans:300,400,600,700 https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap
- Pesos: 400, 700, 800, 600, 300, 900 · line-heights: normal, 1.45, 1.20, 1.60, 1.70, 1.15

| tag | família · tamanho · peso · line-height · tracking · transform |
|---|---|
| a | `Open Sans | 22.4px | 300 | normal | normal | none` |
| p | `Open Sans | 14px | 400 | 1.60 | normal | none` |
| h1 | `Archivo Black | 56px | 400 | 1.05 | -1.4px | none` |
| h2 | `Archivo Black | 32px | 400 | 1.15 | -0.64px | none` |
| h3 | `Open Sans | 18.72px | 700 | normal | normal | none` |
| small | `Open Sans | 14px | 400 | 1.45 | normal | none` |
| label | `Open Sans | 16px | 700 | normal | normal | none` |
| button | `Open Sans | 14px | 800 | normal | 0.84px | uppercase` |

Escala de tamanhos: 10px → 12px → 14px → 16px → 18px → 22px → 32px → 56px

## 4. Espaço, forma, profundidade, movimento
- Espaçamentos mais usados: 12px, 10px, 24px, 8px, 20px, 16px, 11.8281px, 4px, 15px, 14.4px, 5px, 28.8px
- Gaps (flex/grid): 8px, 9.6px, 12px, 16px, 4px, 24px, 64px
- Raios: 4px×65, 999px×16
- Sombras:
  - `rgba(209, 36, 33, 0.22) 0px 0px 38.4px 0px` ×9
  - `rgba(0, 0, 0, 0.1) 0px 1px 0px 0px` ×1
  - `rgb(209, 36, 33) 0px -3px 0px 0px inset` ×1
  - `rgba(209, 36, 33, 0.22) 0px 0px 0px 4px` ×1
  - `rgba(0, 0, 0, 0.16) 0px 24px 64px -19.2px` ×1
  - `rgba(0, 0, 0, 0.3) 0px 2px 6px 0px, rgba(0, 0, 0, 0.2) 0px 6px 16px 0px` ×1
- Transições: 0.3s, 0.15s, 0.5s · easing: cubic-bezier(0.16, linear
- Breakpoints (media queries): max 768px, max 544px, max 960px, max 640px, min 544px, min 768px, max 1024px, min 1024px, max 1280px, max 1056px, max 928px, max 375px
- z-index em uso: 97, 1000, 9999

## 5. Componentes
### Botões (assinaturas mais comuns)
- ×6 — `bg #00000000 · color #000000 · radius 0px · pad 0px 0px · fs 13.3333px · fw 400 · tt none · border 0px #000000 · h 44px`
- ×5 — `bg #06173a · color #ffffff · radius 0px · pad 6px 10px · fs 19.2px · fw 600 · tt uppercase · border 2px #06173a · h 16px`
- ×4 — `bg #00000000 · color #ffffff · radius 4px · pad 14.4px 28.8px · fs 16px · fw 800 · tt uppercase · border 1px #00000000 · h 50px`
- ×1 — `bg #00000000 · color #84171d · radius 0px · pad 8px 8px · fs 19.52px · fw 400 · tt uppercase · border 0px #84171d · h 44px`
- ×1 — `bg #d12421 · color #ffffff · radius 4px · pad 14.4px 28.8px · fs 16px · fw 800 · tt uppercase · border 1px #00000000 · h 50px`
- ×1 — `bg #00000000 · color #ffffff · radius 4px · pad 14.4px 28.8px · fs 16px · fw 800 · tt uppercase · border 1px #00000000 · h 54px`
- ×1 — `bg #008068 · color #ffffff · radius 4px · pad 14.4px 28.8px · fs 16px · fw 800 · tt uppercase · border 1px #008068 · h 54px`
- ×1 — `bg #d12421 · color #ffffff · radius 4px · pad 15px 30px · fs 19.2px · fw 800 · tt uppercase · border 1px #00000000 · h 57px`

### Campos de formulário
- ×1 — `bg #ffffff · border 1px #858585 · radius 4px · pad 11.2px 14.4px · h 46px · fs 16px`

### Links no texto
- `#cccccc · none · fw 300`
- `#006699 · none · fw 400`
- `#555555 · none · fw 400`
- `#ffffff · none · fw 400`

## 6. Estrutura e acessibilidade (alertas rápidos)
- h1 na página: **1** · landmarks: header×12, nav×3, main×1, footer×5, aside×1
- imagens sem alt: **0** · botões sem nome: **0** · campos sem label: **0**
- textos com contraste abaixo de AA: **10**
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

- h1: Crédito para quem empreende no Rio Grande do Sul
- h2: Descubra a linha de crédito ideal para você
- h3: Microcrédito Produtivo
- h3: Microcrédito Mulher
- h3: Reforma e Ampliação
- h3: Crédito Agrícola
- h2: Como funciona
- h3: Simule online
- h3: Receba o agente de crédito
- h3: Análise e liberação
- h2: Últimas novidades da ICC SERRA!
- h2: Alguns de nossos Parceiros
- h2: Torne-se um parceiro ICC SERRA - BANCO DO POVO
- h2: Quer saber mais? Então veja também!
- h3: O QUE É MAIS VANTAJOSO CONSTRUIR OU REFORMAR?
- h3: MICROCRÉDITO: FINANCIAMENTO PARA PEQUENOS NEGÓCIOS
- h3: 6 MOTIVOS PARA ADQUIRIR MICROCRÉDITO
- h3: COMO QUALIFICAR O CLIENTE IDEAL
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
- h2: Transparência
</details>


## 7. Uso ético da referência
Tokens (cor, escala, ritmo, raio, sombra) e **padrões** de layout são inspiração legítima.
**Não copiar:** logotipo, fotos, ilustrações, ícones proprietários, textos e marca registrada. Ver `references/extracao-referencia.md`.
