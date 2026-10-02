"""Gera icc-icons.css e icc-icons.min.css a partir da mesma fonte de verdade.

Cobre os dois sistemas:
  - fonte de icones (solido)  -> classes .icc-icon-*
  - sprite SVG (linha)        -> classe  .icc-ic + tamanhos
"""
import os

HERE = os.path.dirname(os.path.abspath(__file__))
DEST = os.path.join(os.path.dirname(HERE), 'css')

ICONS = [
    ('coin', 'limite', 0xe900, 'Limite / valor'),
    ('calendar', 'prazo', 0xe901, 'Prazos'),
    ('calculator', 'taxa', 0xe902, 'Taxa de juros'),
    ('user-tie', 'avalista', 0xe903, 'Avalista'),
    ('tags', 'tac', 0xe904, 'TAC / taxa de abertura'),
    ('pie-chart', 'renda', 0xe905, 'Comprometimento de renda'),
    ('facebook', None, 0xe906, 'Facebook'),
    ('linkedin', None, 0xe907, 'LinkedIn'),
    ('x-twitter', 'x', 0xe908, 'X (Twitter)'),
    ('whatsapp', None, 0xe909, 'WhatsApp'),
]

BANNER = """/*!
 * icc-icons v1.1.0 - icones do ICC Serra Banco do Povo
 *
 * Dois conjuntos, mesmos nomes:
 *   SOLIDO (fonte)  .icc-icon-limite      cards de condicoes, numeros grandes
 *   LINHA  (sprite) #icc-i-limite         interface: menus, botoes, listas
 *
 * Solido:  <i class="icc-icon-limite" aria-hidden="true"></i>
 * Linha:   <svg class="icc-ic"><use href="#icc-i-limite"/></svg>
 *          (exige o partial inc/icc-icons-sprite.php incluido na pagina)
 *
 * O prefixo e `icc-icon-`/`icc-ic` para nao colidir com o icomoon do core
 * (`_cdn/bootcss/fonticon.min.css`), que usa `.icon-`.
 */
"""

FACE = ('@font-face{font-family:"icc-icons";'
        'src:url(../fonts/icc-icons.woff2) format("woff2"),'
        'url(../fonts/icc-icons.woff) format("woff"),'
        'url(../fonts/icc-icons.ttf) format("truetype");'
        'font-weight:normal;font-style:normal;font-display:block}')

BASE = ('[class^="icc-icon-"],[class*=" icc-icon-"]{font-family:"icc-icons"!important;'
        'speak:never;font-style:normal;font-weight:normal;font-variant:normal;'
        'text-transform:none;line-height:1;display:inline-block;vertical-align:middle;'
        '-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}')

SIZES_FONT = ('.icc-icon-lg{font-size:1.5em}.icc-icon-2x{font-size:2em}'
              '.icc-icon-3x{font-size:3em}.icc-icon-4x{font-size:4em}')

# o sprite ja traz stroke/fill; aqui so o box e o tamanho
SPRITE = ('.icc-ic{width:1.5rem;height:1.5rem;display:inline-block;'
          'vertical-align:middle;flex:none}'
          '.icc-ic-sm{width:1rem;height:1rem}.icc-ic-lg{width:2rem;height:2rem}'
          '.icc-ic-2x{width:3rem;height:3rem}.icc-ic-3x{width:4rem;height:4rem}'
          '.icc-ic-4x{width:5rem;height:5rem}')


def glyphs(pretty):
    rows = []
    for shape, alias, cp, desc in ICONS:
        sels = [f'.icc-icon-{shape}:before']
        if alias:
            sels.append(f'.icc-icon-{alias}:before')
        # a escape \\eXXX e obrigatoria: o caractere literal depende do charset
        # com que o CSS for servido, e o WC nao manda charset no Content-Type
        code = '{content:"\\' + format(cp, 'x') + '"}'
        if pretty:
            rows.append(', '.join(sels) + ' { content: "\\' + format(cp, 'x')
                        + '"; }  /* ' + desc + ' */')
        else:
            rows.append(','.join(sels) + code)
    return rows


def main():
    pretty = (BANNER + '\n' + FACE.replace('};', '};\n') + '\n\n' + BASE + '\n\n'
              + SIZES_FONT + '\n\n' + SPRITE + '\n\n/* glifos */\n'
              + '\n'.join(glyphs(True)) + '\n')
    mini = (BANNER.replace('\n', '').replace('  ', ' ') + '\n'
            + FACE + BASE + SIZES_FONT + SPRITE + ''.join(glyphs(False)) + '\n')

    open(os.path.join(DEST, 'icc-icons.css'), 'w').write(pretty)
    open(os.path.join(DEST, 'icc-icons.min.css'), 'w').write(mini)

    for f in ('icc-icons.css', 'icc-icons.min.css'):
        raw = open(os.path.join(DEST, f), 'rb').read()
        assert b'\xee\xa4' not in raw, f'{f}: caractere PUA literal!'
        assert raw.count(b'\\e9') == 10, f'{f}: {raw.count(rb"\e9")} escapes'
        print(f'{f:22} {len(raw):5} bytes  10 escapes ok')


if __name__ == '__main__':
    main()
