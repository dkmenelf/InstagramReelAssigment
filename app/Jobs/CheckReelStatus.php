<?php

namespace App\Jobs;

use App\Models\Reel;
use App\Services\FoundByAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log;

class CheckReelStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $reel;

    public function __construct(Reel $reel)
    {
        $this->reel = $reel;
    }

    public function handle(FoundByAppService $service)
    {

        
        
        $statusData = $service->checkStatus($this->reel->foundbyme_id);

        if (!$statusData) {
             $this->retryJob();
             return;
        }

        if (isset($statusData['status']) && $statusData['status'] === 'completed') {
            $this->reel->update([
                'status' => 'completed',
                'analized_at' => now(),
                'ai_description' => json_encode($statusData)
            ]);
            Log::info("Analiz TAMAMLANDI! Reel ID: " . $this->reel->id);
            return;
        }

        $this->reel->increment('check_count');

        
        if ($this->reel->check_count >= 10) {
            
            
            $this->reel->update(['status' => 'failed']);
            Log::error("Analiz ZAMAN AŞIMI. Reel ID: " . $this->reel->id);

            // SLACK BİLDİRİMİ 
            $this->sendSlackNotification("DİKKAT! Video analizi başarısız oldu. Reel ID: " . $this->reel->id);

        } else {
            $this->retryJob();
        }
    }

    protected function retryJob()
    {
        $this->release(60); 
    }

   
    protected function sendSlackNotification($message)
    {
    
        $webhookUrl = env('SLACK_WEBHOOK_URL', ''); 

        if ($webhookUrl) {
            try {
                Http::post($webhookUrl, [
                    'text' => "🚨 *FoundByApp Hatası* 🚨\n" . $message,
                ]);
                Log::info("Slack bildirimi gönderildi.");
            } catch (\Exception $e) {
                Log::error("Slack bildirimi gönderilemedi: " . $e->getMessage());
            }
        } else {
            
            Log::info("MOCK SLACK ALERT: (Webhook tanımlı değil) -> " . $message);
        }
    }
}