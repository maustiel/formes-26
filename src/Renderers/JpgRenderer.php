<?php

declare(strict_types=1);

namespace Shapes\Renderers;

use Shapes\Canvas;

/**
 * BONUS — La même image, en JPG. Nécessite l'extension GD.
 *
 * Constructeur attendu : `new JpgRenderer(Canvas $canvas, int $quality = 90)`.
 * Si GD manque, le constructeur lève `\RuntimeException`.
 *
 * Remarquez la signature : ce renderer n'hérite PAS de `SvgRenderer`, il en
 * POSSÈDE un. Un JPG n'« est pas » un SVG ; il se fabrique à partir d'un SVG.
 *
 * Indices :
 *   - `SVG\SVG::fromString($texteSvg)` (paquet meyfa/php-svg, déjà installé) ;
 *   - `->toRasterImage(int $width, int $height)` rend une image GD ;
 *   - `imagejpeg()` écrit dans la sortie standard : capturez-la avec
 *     `ob_start()` / `ob_get_clean()`.
 */
final class JpgRenderer implements Renderer
{
    private SvgRenderer $svgRenderer;

    private int $quality;

    public function __construct(Canvas $canvas, int $quality = 90)
    {
        if (!extension_loaded('gd')) {
            throw new \RuntimeException('L\'extension GD est requise pour le rendu JPG.');
        }

        if ($quality < 0 || $quality > 100) {
            throw new \InvalidArgumentException('La qualité doit être comprise entre 0 et 100.');
        }

        $this->svgRenderer = new SvgRenderer($canvas);
        $this->quality = $quality;
    }

    /** TODO : rendre les octets du JPG (du binaire, pas du texte lisible). */
    public function render(): string
    {
        $svgText = $this->svgRenderer->render();
        $svg = \SVG\SVG::fromString($svgText);

        preg_match('/<svg\b[^>]*\bwidth=["\']([0-9]+)(?:px)?["\']/i', $svgText, $widthMatch);
        preg_match('/<svg\b[^>]*\bheight=["\']([0-9]+)(?:px)?["\']/i', $svgText, $heightMatch);

        $width = isset($widthMatch[1]) ? max(1, (int) $widthMatch[1]) : 1;
        $height = isset($heightMatch[1]) ? max(1, (int) $heightMatch[1]) : 1;

        $image = $svg->toRasterImage($width, $height);

        ob_start();
        try {
            if (!imagejpeg($image, null, $this->quality)) {
                throw new \RuntimeException('Impossible de générer le JPG.');
            }

            return (string) ob_get_clean();
        } catch (\Throwable $exception) {
            ob_end_clean();
            throw $exception;
        } finally {
            if (is_object($image) && method_exists($image, 'destroy')) {
                $image->destroy();
            }
        }
    }

    public function save(string $path): void
    {
        if (file_put_contents($path, $this->render()) === false) {
            throw new \RuntimeException(sprintf('Impossible d\'écrire le fichier JPG « %s ».', $path));
        }
    }
}