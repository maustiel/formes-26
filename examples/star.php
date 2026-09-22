<?php

declare(strict_types=1);

/**
 * Le rendu final de l'atelier : une étoile jaune sur fond bleu nuit.
 *
 * Lancez-le avec :
 *     php examples/star.php
 *
 * Il écrit examples/star.svg — ouvrez le fichier dans un navigateur.
 */

require __DIR__.'/../vendor/autoload.php';

use Shapes\Canvas;
use Shapes\Circle;
use Shapes\Point;
use Shapes\Polygon;
use Shapes\Renderers\SvgRenderer;

$canvas = new Canvas(500, 500, '#ff6dc5');



// L'étoile : dix sommets, alternance pointe / creux.
$canvas->add(new Polygon([
    new Point(250, 0),
    new Point(300, 200),
    new Point(500, 200),
    new Point(350, 300),
    new Point(400, 500),
    new Point(250, 350),
    new Point(100, 500),
    new Point(150, 300),
    new Point(0, 200),
    new Point(200, 200),
], '#FFD23F'));

// oeil gauche
$canvas->add(new Circle(new Point(200, 250), 10, '#ffffff'));

// oeil droit
$canvas->add(new Circle(new Point(300, 250), 10, '#ffffff'));



// 1. Le cercle qui forme la bouche (ex: noir ou rouge)
$canvas->add(new Circle(new Point(250, 250), 20, '#fff9f7'));

// 2. Le cercle de "masquage" 
$canvas->add(new Circle(new Point(250, 245), 20, '#FFD23F'));


$renderer = new SvgRenderer($canvas);
$path = __DIR__.'/star.svg';
$renderer->save($path);

printf("Étoile écrite dans %s (%d formes, aire totale : %.0f px²).%s",
    $path,
    count($canvas->shapes),
    $canvas->totalArea(),
    PHP_EOL,
);
