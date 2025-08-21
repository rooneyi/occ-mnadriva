<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class YoloService
{
    protected $pythonPath = 'python';
    protected $yoloScriptPath;
    protected $modelPath;
    protected $confidenceThreshold = 0.5;

    public function __construct()
    {
        $this->yoloScriptPath = base_path('yolov5/detect.py');
        $this->modelPath = storage_path('app/models/yolo/date_detection.pt');
    }

    /**
     * Détecte les dates dans une image en utilisant YOLO
     * 
     * @param string $imagePath Chemin vers l'image à analyser
     * @return array|null Tableau des détections ou null en cas d'erreur
     */
    public function detectDates($imagePath)
    {
        if (!file_exists($this->yoloScriptPath)) {
            Log::error('Script YOLO introuvable : ' . $this->yoloScriptPath);
            return null;
        }

        if (!file_exists($this->modelPath)) {
            Log::error('Modèle YOLO introuvable : ' . $this->modelPath);
            return null;
        }

        $outputDir = storage_path('app/public/yolo_output');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $command = [
            $this->pythonPath,
            $this->yoloScriptPath,
            '--weights', $this->modelPath,
            '--source', $imagePath,
            '--conf', $this->confidenceThreshold,
            '--save-txt',
            '--save-conf',
            '--project', $outputDir,
            '--name', 'predictions',
            '--exist-ok',
            '--save-crop'
        ];

        try {
            $process = new Process($command);
            $process->setTimeout(300); // 5 minutes
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            return $this->parseYoloOutput($outputDir, $imagePath);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la détection YOLO : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Parse la sortie de YOLO pour extraire les détections
     */
    protected function parseYoloOutput($outputDir, $originalImagePath)
    {
        $predictionsDir = $outputDir . '/predictions/labels/';
        $imageName = pathinfo($originalImagePath, PATHINFO_FILENAME);
        $labelFile = $predictionsDir . $imageName . '.txt';

        if (!file_exists($labelFile)) {
            return []; // Aucune détection
        }

        $detections = [];
        $lines = file($labelFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            $parts = explode(' ', trim($line));
            if (count($parts) >= 6) {
                $detections[] = [
                    'class_id' => (int)$parts[0],
                    'confidence' => (float)$parts[5],
                    'x' => (float)$parts[1],
                    'y' => (float)$parts[2],
                    'width' => (float)$parts[3],
                    'height' => (float)$parts[4],
                ];
            }
        }

        return $detections;
    }

    /**
     * Extrait une région d'image basée sur les coordonnées YOLO
     */
    public function extractRegion($imagePath, $detection)
    {
        if (!extension_loaded('gd')) {
            throw new \Exception('L\'extension GD est requise pour le traitement d\'images.');
        }

        $image = imagecreatefromstring(file_get_contents($imagePath));
        if (!$image) {
            throw new \Exception('Impossible de charger l\'image : ' . $imagePath);
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // Convertir les coordonnées normalisées en pixels
        $x = ($detection['x'] - $detection['width'] / 2) * $width;
        $y = ($detection['y'] - $detection['height'] / 2) * $height;
        $w = $detection['width'] * $width;
        $h = $detection['height'] * $height;

        // Créer une nouvelle image pour la région extraite
        $cropped = imagecrop($image, [
            'x' => (int)$x,
            'y' => (int)$y,
            'width' => (int)$w,
            'height' => (int)$h
        ]);

        if (!$cropped) {
            imagedestroy($image);
            throw new \Exception('Échec de l\'extraction de la région');
        }

        // Sauvegarder l'image recadrée
        $outputPath = storage_path('app/temp/' . uniqid('crop_') . '.jpg');
        imagejpeg($cropped, $outputPath, 90);
        
        // Nettoyer
        imagedestroy($image);
        imagedestroy($cropped);

        return $outputPath;
    }
}
