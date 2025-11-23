<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FoundByAppService
{
   
    protected $baseUrl = 'https://fbma.kaankilic.com/api';
    protected $token = 'mock-token-expired-bypass';

    public function uploadVideo($localPath)
    {
        
        $pathCandidates = [
            storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . $localPath),
            storage_path('app' . DIRECTORY_SEPARATOR . $localPath),
            Storage::disk('local')->path($localPath)
        ];

        $fullPath = null;
        foreach ($pathCandidates as $path) {
            if (file_exists($path)) {
                $fullPath = $path;
                break;
            }
        }

        
        if (!$fullPath) {
            Log::error("Dosya bulunamadı: " . $localPath);
            return null;
        }

        Log::info("MOCK SERVICE: Dosya diskte bulundu. Token süresi dolduğu için API bypass ediliyor.");

        // MOCK CEVAP
        return [
            'status' => 'success',
            'data' => [
                'id' => 'mock-id-' . uniqid(), 
                'type' => 'organic',
                'status' => 'pending'
            ]
        ];
    }

    public function checkStatus($foundByMeId)
    {
        Log::info("MOCK SERVICE: Analiz durumu sorgulandı. Cevap: Completed.");

        return [
            'status' => 'completed',
            'data' => [
                'viral_score' => rand(70, 99),
                'summary' => 'Token suresi doldugu icin analiz simule edilmistir.'
            ]
        ];
    }
}