<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Log;
use App\Services\YoloService;

class OcrService
{
    protected $yoloService;

    public function __construct(YoloService $yoloService)
    {
        $this->yoloService = $yoloService;
    }

    public function extractTextFromImage($imagePath, $useYolo = true)
    {
        try {
            $text = '';
            
            if ($useYolo) {
                // Détecter d'abord les zones de date avec YOLO
                $detections = $this->yoloService->detectDates($imagePath);
                
                if (!empty($detections)) {
                    // Trier par score de confiance (du plus élevé au plus bas)
                    usort($detections, function($a, $b) {
                        return $b['confidence'] <=> $a['confidence'];
                    });
                    
                    // Prendre la détection la plus fiable
                    $bestDetection = $detections[0];
                    
                    // Extraire la région de l'image
                    $croppedImagePath = $this->yoloService->extractRegion($imagePath, $bestDetection);
                    
                    // Appliquer l'OCR uniquement sur la région extraite
                    $text = (new TesseractOCR($croppedImagePath))
                        ->lang('fra', 'eng')
                        ->run();
                    
                    // Nettoyer le fichier temporaire
                    if (file_exists($croppedImagePath)) {
                        unlink($croppedImagePath);
                    }
                }
            }
            
            // Si YOLO n'a rien trouvé ou si on ne l'utilise pas, faire une reconnaissance sur toute l'image
            if (empty($text)) {
                $text = (new TesseractOCR($imagePath))
                    ->lang('fra', 'eng')
                    ->run();
            }
            
            return $text;
            
        } catch (\Exception $e) {
            Log::error('Erreur OCR: ' . $e->getMessage());
            return null;
        }
    }

    public function extractDateFromText($text)
    {
        // Cette fonction peut être améliorée avec des expressions régulières plus précises
        $datePatterns = [
            '/\b(0[1-9]|[12][0-9]|3[01])[\/\-\.](0[1-9]|1[0-2])[\/\-\.](\d{2,4})\b/', // DD/MM/YYYY
            '/\b(\d{2,4})[\/\-\.](0[1-9]|1[0-2])[\/\-\.](0[1-9]|[12][0-9]|3[01])\b/', // YYYY/MM/DD
            '/\b(0[1-9]|[12][0-9]|3[01])\s+([a-zA-Z]+)\s+(\d{4})\b/', // 01 Janvier 2023
        ];

        foreach ($datePatterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return $matches[0];
            }
        }

        return null;
    }
}
