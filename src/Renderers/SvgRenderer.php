<?php

declare(strict_types=1);

namespace Shapes\Renderers;

use Shapes\Canvas;
use Shapes\Shape;
use Shapes\Circle;
use Shapes\Line;
use Shapes\Point;
use Shapes\Polygon;
use Shapes\Rectangle;

/**
 * ÉTAPE 4 — Transforme un Canvas en document SVG, c'est-à-dire en texte.
 *
 * Le renderer reçoit le canvas dans son constructeur : il ne le fabrique pas,
 * il le regarde. `new SvgRenderer(Canvas $canvas)`.
 *
 * Le document attendu, ligne par ligne :
 *
 *   <?xml version="1.0" encoding="UTF-8"?>
 *   <svg xmlns="http://www.w3.org/2000/svg" width="500" height="500" viewBox="0 0 500 500">
 *     <rect x="0" y="0" width="500" height="500" fill="#FFFFFF" />
 *     … une balise par forme, dans l'ordre d'ajout, indentée de deux espaces …
 *   </svg>
 *
 * Les balises par forme :
 *   Line      <line x1="1" y1="1" x2="1" y2="500" stroke="#0000FF" stroke-width="1" />
 *   Circle    <circle cx="250" cy="250" r="150" fill="#00FF00" />
 *   Rectangle <rect x="50" y="50" width="250" height="400" fill="#FF0000" />
 *   Polygon   <polygon points="1,1 1,500 500,500" fill="#FF0000" />
 */
final class SvgRenderer implements Renderer
{
    public function __construct(
    private readonly Canvas $canvas)
    {
    }

    public function render(): string
    {
        $width = $this->number($this->canvas->width);
        $height = $this->number($this->canvas->height);
        $lines = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            sprintf(
                '<svg xmlns="http://www.w3.org/2000/svg" width="%s" height="%s" viewBox="0 0 %s %s">',
                $width,
                $height,
                $width,
                $height,
            ),
            sprintf(
                '  <rect x="0" y="0" width="%s" height="%s" fill="%s" />',
                $width,
                $height,
                $this->canvas->background,
            ),
        ];

        foreach ($this->canvas->shapes as $shape) {
            $lines[] = '  ' . $this->renderShape($shape);
        }

        $lines[] = '</svg>';

        return implode(PHP_EOL, $lines) . PHP_EOL;
    }

    /** TODO : créer le dossier s'il n'existe pas, puis `file_put_contents()`. */
    public function save(string $path): void
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($path, $this->render());
    }

    /**
     * TODO : une forme, une balise.
     *
     * Indice : `match (true) { $shape instanceof Line => …, … }` évite une pile
     * de `if`. Le cas `default` doit lever `\InvalidArgumentException` : un
     * renderer qui ne connaît pas une forme doit le dire, pas dessiner du vide.
     */
    private function renderShape(Shape $shape): string
    {
        return match (true) {
            $shape instanceof Line => sprintf(
                '<line x1="%s" y1="%s" x2="%s" y2="%s" stroke="%s" stroke-width="1" />',
                $this->number($shape->start->x),
                $this->number($shape->start->y),
                $this->number($shape->end->x),
                $this->number($shape->end->y),
                $shape->color,
            ),

            $shape instanceof Circle => sprintf(
                '<circle cx="%s" cy="%s" r="%s" fill="%s" />',
                $this->number($shape->center->x),
                $this->number($shape->center->y),
                $this->number($shape->radius),
                $shape->color,
            ),

            $shape instanceof Rectangle => sprintf(
                '<rect x="%s" y="%s" width="%s" height="%s" fill="%s" />',
                $this->number($shape->origin->x),
                $this->number($shape->origin->y),
                $this->number($shape->width),
                $this->number($shape->height),
                $shape->color,
            ),

            $shape instanceof Polygon => sprintf(
                '<polygon points="%s" fill="%s" />',
                implode(' ', array_map(
                    fn ($point): string => $this->number($point->x) . ',' . $this->number($point->y),
                    $shape->points,
                )),
                $shape->color,
            ),

            default => throw new \InvalidArgumentException('Forme inconnue du renderer : ' . $shape::class,),
        };
    }

    /**
     * TODO : 500.0 doit s'écrire « 500 » dans le SVG, pas « 500.00 »,
     * et 12.5 doit s'écrire « 12.5 ».
     *
     * Indice : `number_format()`, puis `rtrim()` deux fois.
     */
    private function number(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }
}