# Phinx2SQL

[![View last release](https://img.shields.io/badge/version-1.1.1-informational.svg)](https://github.com/edgaralexanderfr/phinx2sql/releases/latest)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](https://opensource.org/licenses/MIT)

## INSTALACIÓN

1. [Descargar la última versión adjunta](https://github.com/edgaralexanderfr/phinx2sql/releases/latest)
2. Extraer el archivo en una nueva carpeta (debería llamarse _edgaralexanderfr_).
3. Mover _edgaralexanderfr_ a la carpeta del repo de alegra _alegra/vendor_
4. Abrir _alegra/vendor/edgaralexanderfr/phinx2sql/bin_ y copiar el ejecutable _phinx2sql_ adentro de la carpeta _alegra/vendor/bin_

### Usando Composer

`composer require edgaralexanderfr/phinx2sql`

No se debe usar en alegra.

## USO

`comando [ruta a la carpeta de migraciones] [argumentos]`

### Opciones

```bash
  -h, --help        Mostrar ayuda
  -m                Especificar el ID de la migración o parte del nombre del archivo
  -g, --up, --down  `-g up` para sacar el "Migrate Up" oo `-g down` para sacar el "Migrate Down"
```

### Ejemplos

`./vendor/bin/phinx2sql -m 20190802002200`

#### Para sacar solo el UP:

`./vendor/bin/phinx2sql -m 20190802002200 --up`

o

`./vendor/bin/phinx2sql -m 20190802002200 -g up`

#### Para sacar solo el DOWN:

`./vendor/bin/phinx2sql -m 20190802002200 --down`

o

`./vendor/bin/phinx2sql -m 20190802002200 -g down`

#### Para especificar la ruta de la carpeta de migraciones:

`./vendor/bin/phinx2sql ./data/migrations/ -m 20190802002200`

#### Para la ayuda:

`./vendor/bin/phinx2sql`

o

`./vendor/bin/phinx2sql -h`

o

`./vendor/bin/phinx2sql --help`

## LICENCIA

[The MIT License](https://opensource.org/licenses/MIT)
