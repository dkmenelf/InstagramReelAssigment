<?php

namespace App\Jobs;

use App\Models\Reel;
use App\Services\FoundByAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Jobs\CheckReelStatus;

class UploadReelToFoundByApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reel;
    protected $filePath;

    public function __construct(Reel $reel, string $filePath)
    {
        $this->reel = $reel;
        $this->filePath = $filePath;
    }


    public function handle(FoundByAppService $service)
    {
        Log::info("Video yükleme kuyruğu başladı: " . $this->reel->id);

        $result = $service->uploadVideo($this->filePath);

        if ($result && isset($result['data']['id'])) {
            
            $this->reel->update([
                'foundbyme_id' => $result['data']['id'],
                'check_count' => 0 
            ]);
            
            Log::info("Video yüklendi, analiz takibi başlıyor. ID: " . $result['data']['id']);

            CheckReelStatus::dispatch($this->reel)->delay(now()->addSeconds(10)); // 10 sn sonra ilk kontrol

        } else {
            $this->reel->update(['status' => 'failed']);
            Log::error("Video yüklenemedi.");
        }
    }
}