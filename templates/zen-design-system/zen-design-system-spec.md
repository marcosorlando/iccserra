# ICC SERRA BANCO DO POVO · Design System

Fonte única da verdade do sistema visual da ICC SERRA BANCO DO POVO. Versão v1.0 · 2026. Site: iccserra.com.br

Use estes tokens ao gerar qualquer interface, peça ou componente da marca.

## 1. Cores

| Token | Hex | Uso |
|---|---|---|
| `--brand` (primary) | `#D12421` | Cor primária, CTAs, destaques, ícones |
| `--accent` | `#781F1C` | Cor de apoio. Fim do gradiente CTA, realces, hover de superfície |
| `--deep` | `#152024` | Fim escuro do gradiente, estados profundos |
| `--deep-hover` | `#1F3038` | Hover de CTA |
| `--bg` (dark) | `#000000` | Fundo do tema escuro |
| `--bg` (light) | `#F4F4F1` | Fundo do tema claro |

**Gradiente de marca (CTA):** `linear-gradient(90deg, #D12421, #152024)`. Versão 135° para ícones e bordas.

**Escala de texto (opacidades sobre o fundo):** t1 100% (títulos) · t2 86–88% (corpo forte) · t3 74–76% (corpo) · t4 70% (labels) · t5 62–64% (meta) · t6 32–34% (divisores).
Contraste mínimo: **4.5:1** para qualquer texto, inclusive labels e meta. Nunca use cinza claro em texto de corpo, e nunca aplique a cor primária pura em texto corrido sobre fundo claro — reserve-a para títulos, CTAs e destaques, ou use a versão escurecida do gradiente no corpo.

**Superfícies:** fills de 1.5% a 5% sobre o fundo; linhas de 4% a 10%. O tema claro espelha os mesmos papéis com preto translúcido.

## 2. Tipografia

Par tipográfico: **Archivo Black** para display/títulos e **Open Sans** para corpo, labels e UI. Nunca use a fonte de display em texto pequeno.

| Papel | Tamanho | Line-height | Peso | Tracking |
|---|---|---|---|---|
| Display | clamp(2.5rem, 6vw, 4.5rem) | 1.0 | 900 | −0.03em |
| H1 | clamp(2rem, 4.5vw, 3.5rem) | 1.05 | 900 | −0.025em |
| H2 | clamp(1.5rem, 3vw, 2.5rem) | 1.1 | 700 | −0.02em |
| H3 | 1.4rem | 1.2 | 700 | normal |
| H4 | 1.125rem | 1.3 | 700 | normal |
| Body L | 1rem | 1.8 | 400 | normal |
| Body M | 0.94rem | 1.75 | 400 | normal |
| Overline / label | 0.875rem | — | 600–700 | 0.18–0.25em, uppercase |

Corpo com no máximo 65–75 caracteres por linha. **Piso absoluto de 14px (0.875rem)** para qualquer texto da interface — nada abaixo disso, em nenhum contexto.

## 3. Layout e espaçamento

- Container máximo: **74rem**; padding lateral `clamp(1.25rem, 4vw, 2.75rem)`.
- Seções: padding vertical `clamp(4.5rem, 9vw, 8rem)`, separadas por linha de 1px.
- Raio padrão: **4px** (cards e botões); 9999px para chips e toggles.
- Grids fluidos: `repeat(auto-fit, minmax(Xrem, 1fr))` (X de 10 a 18 conforme a densidade).
- Breakpoints: **960px** (hero passa a 2 colunas) · **900px** (menu vira dropdown) · **640px** (compacta labels).
- Alvos de toque no mobile: mínimo **44×44px**.

## 4. Componentes

- **Botões:** uppercase, 0.72rem, peso 800, tracking 0.1em, padding 0.9rem 1.8rem. Variantes: `primary` (gradiente de marca), `outline`, `ghost`, `solid`. Hover: leve `translateY(-1px)` + sweep de cor subindo.
- **Cards:** fundo translúcido + borda de 1px + blur. Nunca aninhe cards.
- **Chips/badges:** pílula com borda em gradiente sutil; ponto pulsante para status "live".
- **Inputs:** fundo de fill 3%, borda 1px, foco na cor da marca. Todo input tem label associado.
- **Icon box:** 2.75rem, borda em gradiente 135°, ícone com traço na cor da marca.
- **Ícones:** conjunto line, traço 1.6px, grade 24×24.

## 5. Movimento

| Token | Duração | Uso | Curva |
|---|---|---|---|
| micro | 100–150ms | hover, foco | linear |
| ui | 250–350ms | botão, tooltip | ease-back |
| enter | 500–700ms | card, reveal | ease-apple `cubic-bezier(0.16,1,0.3,1)` |
| cinematic | 900–1400ms | hero, texto escalonado | ease-expo `cubic-bezier(0.19,1,0.22,1)` |
| ambient | 3–8s | pulse, float, shimmer | ease-in-out (loop) |

Sempre respeitar `prefers-reduced-motion: reduce`.

## 6. Marca

Dois temas convivem no mesmo arquivo: a logo **clara** aparece no tema escuro e a **escura** no tema claro. Nunca recolorir, distorcer, girar ou aplicar efeitos fora do documentado.

Assets disponíveis:
- `icc-box-black.png`
- `icc-box-white.png`
- `icc-favicon.png`
- `icc-horizontal-black.png`
- `icc-horizontal-white.png`
- `icc-iccserra.png`

**Cor de marca em texto:** nunca aplique `#D12421` cru como cor de texto. Use a versão ajustada por tema —
`#DD302D` sobre fundo escuro e `#D12421` sobre fundo claro — para manter 4.5:1.

## 7. Iconografia

Set proprietário (categorias), monocromático, invertido automaticamente conforme o tema:
- `avalista.svg`
- `facebook.svg`
- `limite.svg`
- `linkedin.svg`
- `prazos.svg`
- `renda-comprometida.svg`
- `tac.svg`
- `taxa-de-juros.svg`
- `whatsapp.svg`
- `x-twitter.svg`

Set utilitário de interface: line, stroke 1.6px, grade 24×24, SVG inline.
Tamanhos: 14px (inline), 16px (texto), 20px (card), 24px (padrão), 32px (hero).
