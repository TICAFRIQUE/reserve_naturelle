<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BuildCss extends Command
{
    protected $signature = 'css:build 
                            {--no-minify : Ne pas minifier le CSS}
                            {--source=style.css : Fichier source}';

    protected $description = 'Fusionne et minifie tous les fichiers CSS en un seul';

    protected $cssDir;
    protected $processedFiles = [];

    public function handle()
    {
        $this->cssDir = public_path('css');
        $source       = $this->cssDir . '/' . $this->option('source');
        $output       = $this->cssDir . '/app.min.css';
        $minify       = !$this->option('no-minify');

        if (!file_exists($source)) {
            $this->error("❌ Fichier source introuvable : $source");
            return 1;
        }

        $this->info("🔍 Lecture de : " . basename($source));
        $this->newLine();

        // Fusionner récursivement
        $css = $this->processFile($source);

        $sizeBefore = strlen($css);
        $this->info("📦 CSS fusionné : " . $this->formatBytes($sizeBefore));
        $this->info("📁 Fichiers traités : " . count($this->processedFiles));

        // Minifier
        if ($minify) {
            $css = $this->minify($css);
            $sizeAfter = strlen($css);
            $gain = round((1 - $sizeAfter / $sizeBefore) * 100, 1);
            $this->info("⚡ CSS minifié : " . $this->formatBytes($sizeAfter) . " (-$gain%)");
        }

        // Sauvegarder
        file_put_contents($output, $css);
        $this->newLine();
        $this->info("✅ Fichier généré : public/css/app.min.css");
        $this->info("📊 Taille finale : " . $this->formatBytes(filesize($output)));

        return 0;
    }

    /**
     * Traite un fichier CSS récursivement (résout les @import)
     */
    private function processFile(string $file): string
    {
        $realPath = realpath($file);

        // Éviter les boucles infinies
        if (in_array($realPath, $this->processedFiles)) {
            return '';
        }

        $this->processedFiles[] = $realPath;

        $content = file_get_contents($file);
        $baseDir = dirname($file);

        // Regex pour capturer les @import
        $content = preg_replace_callback(
            '/@import\s+url\([\'"]?([^\'")]+)[\'"]?\)\s*;/i',
            function ($matches) use ($baseDir) {
                $importPath = $matches[1];

                // Ignorer les imports externes (http/https)
                if (preg_match('#^https?://#', $importPath)) {
                    return $matches[0];
                }

                // Construire le chemin complet
                $fullPath = $baseDir . '/' . $importPath;

                // Si le fichier n'existe pas, essayer avec le dossier css/
                if (!file_exists($fullPath)) {
                    $fullPath = $this->cssDir . '/' . $importPath;
                }

                if (!file_exists($fullPath)) {
                    $this->warn("⚠️  Import introuvable : $importPath");
                    return "/* ⚠️ Import introuvable : $importPath */";
                }

                return "/* ========== " . basename($importPath) . " ========== */\n"
                    . $this->processFile($fullPath)
                    . "\n";
            },
            $content
        );

        return $content;
    }

    /**
     * Minifie le CSS
     */
    private function minify(string $css): string
    {
        // Supprimer les commentaires
        $css = preg_replace('#/\*.*?\*/#s', '', $css);

        // Supprimer les espaces multiples
        $css = preg_replace('/\s+/', ' ', $css);

        // Supprimer les espaces autour des symboles
        $css = preg_replace('/\s*([{}:;,>+~])\s*/', '$1', $css);

        // Supprimer le dernier ; avant }
        $css = preg_replace('/;}/', '}', $css);

        // Supprimer les espaces en début/fin
        $css = trim($css);

        return $css;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) return $bytes . ' o';
        if ($bytes < 1024 * 1024) return round($bytes / 1024, 1) . ' Ko';
        return round($bytes / 1024 / 1024, 2) . ' Mo';
    }
}