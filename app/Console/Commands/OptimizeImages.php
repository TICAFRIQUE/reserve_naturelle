<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;
use Intervention\Image\Encoders\WebpEncoder;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize 
                            {--folder=img : Dossier dans public/ à optimiser}
                            {--width=800 : Largeur maximale}
                            {--quality=75 : Qualité WebP (0-100)}
                            {--backup : Sauvegarder les originaux}';

    protected $description = 'Optimise toutes les images : redimensionne et compresse en WebP';

    public function handle()
    {
        $folder   = $this->option('folder');
        $maxWidth = (int) $this->option('width');
        $quality  = (int) $this->option('quality');
        $backup   = $this->option('backup');

        $path = public_path($folder);

        if (!is_dir($path)) {
            $this->error("❌ Dossier introuvable : $path");
            return 1;
        }

        // Créer le dossier de backup si demandé
        $backupPath = null;
        if ($backup) {
            $backupPath = public_path($folder . '_backup_' . date('Y-m-d_H-i-s'));
            mkdir($backupPath, 0755, true);
            $this->info("📦 Backup dans : $backupPath");
        }

        // Créer le manager Intervention Image (v4)
        $manager = new ImageManager(new Driver());

        // Récupérer toutes les images
        $files = glob($path . '/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE);

        if (empty($files)) {
            $this->warn("⚠️  Aucune image trouvée dans $path");
            return 0;
        }

        $this->info("🔍 " . count($files) . " image(s) trouvée(s)");
        $this->newLine();

        $totalBefore = 0;
        $totalAfter  = 0;
        $bar         = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $file) {
            $filename = basename($file);

            // Nom en kebab-case
            $name = pathinfo($file, PATHINFO_FILENAME);
            $name = $this->slugify($name);

            $sizeBefore = filesize($file);
            $totalBefore += $sizeBefore;

            // Backup si demandé
            if ($backup && $backupPath) {
                copy($file, $backupPath . '/' . $filename);
            }

            try {
                // ✅ v4 : decode() au lieu de read()
                $image = $manager->decode(file_get_contents($file));

                // Redimensionner si trop large (garde le ratio)
                if ($image->width() > $maxWidth) {
                    $image->scale(width: $maxWidth);
                }

                // Nouveau nom en WebP
                $newFilename = $name . '.webp';
                $newFile     = $path . '/' . $newFilename;

                // ✅ v4 : encoder explicitement en WebP avec qualité
                $encoded = $image->encode(new WebpEncoder(quality: $quality));
                $encoded->save($newFile);

                // Supprimer l'ancien fichier si différent
                if ($file !== $newFile && file_exists($file)) {
                    unlink($file);
                }

                $sizeAfter = filesize($newFile);
                $totalAfter += $sizeAfter;

            } catch (\Exception $e) {
                $this->error("\n❌ Erreur sur $filename : " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Résumé
        $gain    = $totalBefore - $totalAfter;
        $percent = $totalBefore > 0 ? round(($gain / $totalBefore) * 100, 1) : 0;

        $this->table(
            ['Métrique', 'Avant', 'Après', 'Gain'],
            [
                [
                    'Total',
                    $this->formatBytes($totalBefore),
                    $this->formatBytes($totalAfter),
                    "-$percent%"
                ],
            ]
        );

        $this->info("✅ Terminé !");
        if ($backup) {
            $this->info("📦 Backup disponible : $backupPath");
        }

        return 0;
    }

    private function slugify(string $text): string
    {
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII', $text);
        $text = preg_replace('/[\s_]+/', '-', $text);
        $text = preg_replace('/[^A-Za-z0-9\-]/', '', $text);
        $text = strtolower($text);
        $text = preg_replace('/-+/', '-', $text);
        return trim($text, '-');
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes < 1024) return $bytes . ' o';
        if ($bytes < 1024 * 1024) return round($bytes / 1024, 1) . ' Ko';
        return round($bytes / 1024 / 1024, 2) . ' Mo';
    }
}