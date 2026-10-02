# icc-icons

Dez ícones do ICC Serra Banco do Povo, em **dois conjuntos com os mesmos nomes**:

| | Formato | Onde usar | Peso |
|---|---|---|---|
| **Sólido** | fonte de ícones, `.icc-icon-*` | cards de condições, aplicações grandes — o peso cheio é o que dá o efeito do layout original | 2,5 KB WOFF2 (cacheado) |
| **Linha** | sprite SVG, `#icc-i-*` | interface: menus, botões, listas, formulários | 5,3 KB inline por página |

O sólido reproduz os ícones dos cards de condições. O linha segue a mesma
linguagem do set utilitário do design system: grade 24×24, stroke 1,6,
`currentColor`.

## Uso

```html
<!-- SOLIDO (fonte). O CSS ja entra pelo index.php -->
<i class="icc-icon-limite" aria-hidden="true"></i>

<!-- LINHA (sprite). O partial ja entra logo apos o <body> no index.php -->
<svg class="icc-ic"><use href="#icc-i-limite"/></svg>
```

Cor vem de `color`; tamanho, de `font-size` (sólido) ou das classes
`.icc-ic-sm/lg/2x/3x/4x` (linha). Ícone que carrega significado sozinho — um
link de rede social, por exemplo — precisa de rótulo textual em `.sr-only`.

## Nomes

Cada ícone tem o nome da forma e, quando faz sentido, um alias semântico. Os
dois valem nos dois conjuntos.

| Forma | Alias | Codepoint |
|---|---|---|
| `coin` | `limite` | U+E900 |
| `calendar` | `prazo` | U+E901 |
| `calculator` | `taxa` | U+E902 |
| `user-tie` | `avalista` | U+E903 |
| `tags` | `tac` | U+E904 |
| `pie-chart` | `renda` | U+E905 |
| `facebook` | — | U+E906 |
| `linkedin` | — | U+E907 |
| `x-twitter` | `x` | U+E908 |
| `whatsapp` | — | U+E909 |

## Arquivos

| Caminho | Papel |
|---|---|
| `icc-icons.woff2` / `.woff` / `.ttf` | a fonte, nessa ordem de preferência |
| `svg/*.svg` | **arte-fonte sólida** — origem da fonte |
| `svg-line/*.svg` | **arte-fonte em linha** — origem do sprite |
| `../icons/line/`, `../icons/solid/` | os mesmos SVGs soltos, para uso avulso |
| `../icons/icc-icons-sprite.svg` | sprite em SVG puro |
| `../inc/icc-icons-sprite.php` | sprite como partial (é este que o site inclui) |
| `../css/icc-icons.css` + `.min.css` | CSS dos dois conjuntos |
| `../icc-icons.html` | folha de referência visual |

Scripts: `gen_icons.py` (desenha os 6 sólidos próprios), `scale_brand.py` +
`measure_bbox.js` (normalizam os 4 logos de marca sólidos), `line_icons.py`
(os 10 em linha), `build_font.js` (SVG → fonte), `build_sprite.py`,
`build_css.py`, `build_sheet.py`.

## Regras que valem ao mexer nisso

- **Nunca troque um codepoint já publicado.** A ordem em `ICONS` no
  `build_font.js` define os codepoints; mudá-la quebra qualquer página no ar.
  Ícone novo entra no fim, em `U+E90A` em diante.
- **Os prefixos são `icc-icon-` e `icc-ic`**, e não `icon-`, porque o core já
  carrega o icomoon (`_cdn/bootcss/fonticon.min.css`) usando `.icon-`.
- **O sprite é inline, não `<use href="arquivo.svg#id">`.** A referência
  externa depende de same-origin e falha calada em navegador antigo.
- **Os sólidos ficam fora do sprite** de propósito: são 17 KB de path contra
  3,7 KB dos de linha, e a fonte já os entrega cacheados.
- **O CSS usa a escape `\eXXX`, nunca o caractere literal.** O WC não manda
  charset no `Content-Type` do CSS; o literal depende disso e quebra calado.
- Os 6 sólidos próprios são geometria calculada (offset de traço e booleanas
  via shapely), não curvas desenhadas à mão. Ajuste os números em
  `gen_icons.py` e regere; não edite o `d=` do SVG.

## Regerar

Precisa de Node e de um Python com `shapely`:

```sh
npm install svgicons2svgfont svg2ttf ttf2woff ttf2woff2 sharp
pip install shapely
```

```sh
python3 gen_icons.py     # 6 solidos proprios -> svg/
node measure_bbox.js     # mede o bbox dos logos de marca (le brand/)
python3 scale_brand.py   # normaliza os 4 logos solidos -> svg/
node build_font.js       # svg/ -> dist/icc-icons.{ttf,woff,woff2}, copie para ca
python3 line_icons.py    # 10 em linha -> svg-line/
python3 build_sprite.py  # svg-line/ -> ../inc/icc-icons-sprite.php + ../icons/
python3 build_css.py     # -> ../css/icc-icons.css e .min.css
python3 build_sheet.py   # -> ../icc-icons.html
```

`scale_brand.py` e `measure_bbox.js` leem uma pasta `brand/` com os SVGs
originais dos logos, não versionada. Mexendo só nos ícones próprios, rode
apenas `gen_icons.py` / `line_icons.py` e os builds seguintes.

## Marcas registradas

Facebook, LinkedIn, X e WhatsApp são marcas de seus detentores. Os sólidos
vieram do [Simple Icons](https://simpleicons.org) (CC0) e do
[Bootstrap Icons](https://icons.getbootstrap.com) (MIT, o LinkedIn, que o
Simple Icons não distribui mais); os de linha, do
[Feather](https://feathericons.com) (MIT), com X e WhatsApp desenhados aqui.
Use-os só para identificar os perfis do ICC Serra nessas redes — não como
elemento decorativo, e sem modificá-los.
