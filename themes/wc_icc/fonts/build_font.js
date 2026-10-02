// Monta a fonte de icones: SVGs -> SVG font -> TTF -> WOFF/WOFF2, e emite
// o CSS com as classes. Os codepoints ficam na Private Use Area (U+E9xx).
const fs = require('fs');
const path = require('path');
const svg2ttf = require('svg2ttf');
const ttf2woff = require('ttf2woff');

const HERE = __dirname;
const SVG = path.join(HERE, 'svg');
const DIST = path.join(HERE, 'dist');

const FONT = 'icc-icons';
const PREFIX = 'icc-icon';
const VERSION = '1.0.0';

// ordem fixa: mudar um codepoint depois de publicado quebra paginas no ar
const ICONS = [
  ['coin', 0xe900, 'Limite / valor'],
  ['calendar', 0xe901, 'Prazos'],
  ['calculator', 0xe902, 'Taxa de juros'],
  ['user-tie', 0xe903, 'Avalista'],
  ['tags', 0xe904, 'TAC / taxa'],
  ['pie-chart', 0xe905, 'Comprometimento de renda'],
  ['facebook', 0xe906, 'Facebook'],
  ['linkedin', 0xe907, 'LinkedIn'],
  ['x-twitter', 0xe908, 'X (Twitter)'],
  ['whatsapp', 0xe909, 'WhatsApp'],
];

fs.mkdirSync(DIST, { recursive: true });

// svgicons2svgfont v15+ e ESM; este arquivo e CommonJS
(async () => {
const { SVGIcons2SVGFontStream: SVGIcons2SVGFont } = await import('svgicons2svgfont');
const ttf2woff2 = (await import('ttf2woff2')).default;

const stream = new SVGIcons2SVGFont({
  fontName: FONT,
  fontHeight: 1000,
  descent: 150,        // desce a caixa para o icone centrar na linha de texto
  normalize: false,    // os SVGs ja saem normalizados na mesma caixa optica
  centerHorizontally: false,
  log: () => {},
});

const chunks = [];
stream.on('data', (c) => chunks.push(c));
stream.on('end', () => {
  const svgFont = chunks.map(String).join('');
  fs.writeFileSync(path.join(DIST, `${FONT}.svg`), svgFont);

  const ttf = Buffer.from(svg2ttf(svgFont, { version: '1.0', description: 'ICC Serra icon font' }).buffer);
  fs.writeFileSync(path.join(DIST, `${FONT}.ttf`), ttf);
  fs.writeFileSync(path.join(DIST, `${FONT}.woff`), Buffer.from(ttf2woff(ttf).buffer));
  fs.writeFileSync(path.join(DIST, `${FONT}.woff2`), ttf2woff2(ttf));

  for (const f of ['svg', 'ttf', 'woff', 'woff2']) {
    const p = path.join(DIST, `${FONT}.${f}`);
    console.log(`${f.padEnd(5)} ${(fs.statSync(p).size / 1024).toFixed(1)} KB`);
  }
});

for (const [name, code] of ICONS) {
  const file = path.join(SVG, `${name}.svg`);
  if (!fs.existsSync(file)) throw new Error(`faltando ${file}`);
  const g = fs.createReadStream(file);
  g.metadata = { unicode: [String.fromCodePoint(code)], name };
  stream.write(g);
}
stream.end();
})();
