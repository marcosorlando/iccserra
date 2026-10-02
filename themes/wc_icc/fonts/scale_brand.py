"""Normaliza os logos de marca para o mesmo grid 1000x1000 dos demais icones.

svgicons2svgfont ignora `transform` de grupo, entao a escala e a translacao
sao aplicadas numericamente sobre os numeros do path. O bbox real de cada
path e medido rasterizando o SVG (via measure_bbox.js), nao chutado.
"""
import json
import os
import re
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
OUT = os.path.join(HERE, 'svg')

BOX = 815
OPTICAL = {'facebook': 0.98, 'linkedin': 0.95, 'x-twitter': 0.92, 'whatsapp': 1.0}

# quantos numeros cada comando consome, e quais deles sao coordenadas x / y
SPEC = {
    'M': (2, [0], [1]), 'L': (2, [0], [1]), 'T': (2, [0], [1]),
    'H': (1, [0], []),  'V': (1, [], [0]),
    'C': (6, [0, 2, 4], [1, 3, 5]),
    'S': (4, [0, 2], [1, 3]), 'Q': (4, [0, 2], [1, 3]),
    'A': (7, [5], [6]),   # rx ry rot large sweep x y  -> tratado a parte
    'Z': (0, [], []),
}

NUM = re.compile(r'[-+]?(?:\d*\.\d+|\d+)(?:[eE][-+]?\d+)?')


class Reader:
    """Leitor de path ciente de arcos: os flags large-arc e sweep sao um
    unico digito e podem vir colados ao numero seguinte ('00-8.4' = 0, 0,
    -8.4). Um tokenizador generico de numeros lê '00' como um valor so e
    desalinha o resto do path."""

    def __init__(self, d):
        self.d, self.i = d, 0

    def skip(self):
        while self.i < len(self.d) and self.d[self.i] in ' ,\t\n\r':
            self.i += 1

    def eof(self):
        self.skip()
        return self.i >= len(self.d)

    def peek(self):
        self.skip()
        return self.d[self.i] if self.i < len(self.d) else ''

    def cmd(self):
        c = self.peek()
        if c and c in 'MmLlHhVvCcSsQqTtAaZz':
            self.i += 1
            return c
        return None

    def num(self):
        self.skip()
        m = NUM.match(self.d, self.i)
        if not m:
            raise ValueError(f'numero esperado em {self.i}: {self.d[self.i:self.i+12]!r}')
        self.i = m.end()
        return float(m.group())

    def flag(self):
        self.skip()
        c = self.d[self.i]
        if c not in '01':
            raise ValueError(f'flag 0/1 esperado em {self.i}: {c!r}')
        self.i += 1
        return int(c)


def fmt(v):
    return f'{round(v, 1):g}'


def transform_path(d, s, tx, ty):
    """Aplica x'=x*s+tx, y'=y*s+ty. Em comandos relativos a translacao nao
    entra (sao deltas); em arcos, rx/ry escalam e flags/rotacao nao."""
    r = Reader(d)
    out, cmd = [], None
    while not r.eof():
        c = r.cmd()
        if c is not None:
            cmd = c
            out.append(cmd)
            if cmd in 'Zz':
                cmd = None if cmd in 'Zz' else cmd
                continue
        elif cmd is None:
            raise ValueError('path comeca sem comando')
        else:
            out.append(cmd)          # repeticao implicita do ultimo comando
        up, rel = cmd.upper(), cmd.islower()
        ox, oy = (0, 0) if rel else (tx, ty)
        if up == 'A':
            rx, ry, rota = r.num() * s, r.num() * s, r.num()
            fa, fs = r.flag(), r.flag()
            x, y = r.num() * s + ox, r.num() * s + oy
            vals = [rx, ry, rota, fa, fs, x, y]
        else:
            n, xs, ys = SPEC[up]
            vals = [r.num() for _ in range(n)]
            for k in xs:
                vals[k] = vals[k] * s + ox
            for k in ys:
                vals[k] = vals[k] * s + oy
        out.append(' '.join(fmt(v) for v in vals))
        # depois de um M/m, coordenadas extras sao L/l
        if up == 'M':
            cmd = 'l' if rel else 'L'
    return ''.join(
        t if t and t[0].isalpha() else (' ' + t if t else '') for t in out
    ).strip()


def main():
    bboxes = json.load(open(os.path.join(HERE, 'bbox.json')))
    for name, info in bboxes.items():
        src = open(info['file']).read()
        paths = re.findall(r'<path[^>]*\bd="([^"]+)"', src)
        if not paths:
            sys.exit(f'sem path em {info["file"]}')
        x0, y0, w, h = info['x'], info['y'], info['w'], info['h']
        target = BOX * OPTICAL.get(name, 1.0)
        s = target / max(w, h)
        tx = 500 - (x0 + w / 2) * s
        ty = 500 - (y0 + h / 2) * s
        d = ' '.join(transform_path(p, s, tx, ty) for p in paths)
        svg = ('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000" '
               f'width="1000" height="1000"><path d="{d}"/></svg>')
        open(os.path.join(OUT, f'{name}.svg'), 'w').write(svg)
        print(f'{name:12} escala={s:.3f}  {len(paths)} path(s)')


if __name__ == '__main__':
    main()
