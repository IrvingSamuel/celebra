<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MigrateImagesToMinio extends Command
{
    protected $signature = 'images:migrate-to-minio {--dry-run : Apenas exibe o que seria feito sem executar}';
    protected $description = 'Faz upload de todas as imagens locais (public/images/) para o MinIO e atualiza o banco de dados';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $disk   = Storage::disk('minio');
        $publicPath = public_path('images');
        $uploaded = 0;
        $failed = 0;

        $this->info('Enviando arquivos para o MinIO...');

        foreach (['services', 'venues'] as $folder) {
            $dir = $publicPath . '/' . $folder;
            if (! is_dir($dir)) {
                $this->warn("  Diretório não encontrado: {$dir}");
                continue;
            }

            $files = glob("{$dir}/*.{jpg,jpeg,png,webp,gif}", GLOB_BRACE);
            $this->line("  Encontrados " . count($files) . " arquivos em public/images/{$folder}/");

            foreach ($files as $file) {
                $filename  = basename($file);
                $minioPath = "{$folder}/{$filename}";

                if ($dryRun) {
                    $this->line("  [dry-run] Upload: {$minioPath}");
                    $uploaded++;
                    continue;
                }

                try {
                    $stream = fopen($file, 'r');
                    $disk->put($minioPath, $stream, 'public');
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                    $this->line("  ✓ {$minioPath}");
                    $uploaded++;
                } catch (\Throwable $e) {
                    $this->error("  ✗ {$minioPath}: " . $e->getMessage());
                    $failed++;
                }
            }
        }

        $this->info("Upload concluído: {$uploaded} enviados, {$failed} falhas.");

        if ($dryRun) {
            $this->warn('Modo dry-run — banco de dados não foi alterado.');
            return self::SUCCESS;
        }

        // Atualizar tabela venues
        $this->info('Atualizando venues...');
        $venueCount = 0;

        DB::table('venues')->whereNotNull('image')->orderBy('id')->each(function ($venue) use (&$venueCount) {
            $updates = [];

            if ($venue->image && str_starts_with($venue->image, '/images/')) {
                $updates['image'] = preg_replace('#^/images/#', '', $venue->image);
            }

            if ($venue->gallery) {
                $gallery = json_decode($venue->gallery, true) ?? [];
                $newGallery = array_map(function ($img) {
                    if (str_starts_with($img, '/images/')) {
                        return preg_replace('#^/images/#', '', $img);
                    }
                    return $img;
                }, $gallery);
                $updates['gallery'] = json_encode($newGallery);
            }

            if (! empty($updates)) {
                DB::table('venues')->where('id', $venue->id)->update($updates);
                $venueCount++;
            }
        });

        $this->info("  {$venueCount} venues atualizados.");

        // Atualizar tabela services
        $this->info('Atualizando services...');
        $serviceCount = 0;

        DB::table('services')->whereNotNull('image')->orderBy('id')->each(function ($service) use (&$serviceCount) {
            if (str_starts_with($service->image, '/images/')) {
                $minioPath = preg_replace('#^/images/#', '', $service->image);
                DB::table('services')->where('id', $service->id)->update(['image' => $minioPath]);
                $serviceCount++;
            }
        });

        $this->info("  {$serviceCount} services atualizados.");

        $this->info('Migração concluída!');
        return self::SUCCESS;
    }
}
