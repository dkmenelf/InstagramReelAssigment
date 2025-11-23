<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Reel;  // 
use App\Jobs\UploadReelToFoundByApp;

class InstagramReelController extends Controller
    
{
    public function downloadReel(Request $request)
    {
        
        $request->validate([
            'user_id' => 'required|uuid',
            'url' => 'required|url'
        ]);

        $userId = $request->user_id;
        $reelUrl = $request->url;

        
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if ($user->instagram_token_expires_at && Carbon::now()->greaterThan($user->instagram_token_expires_at)) {
            return response()->json(['error' => 'Instagram token expired.'], 401);
        }

        // (Test için sabit ID)
        $mediaId = 'test_video_' . time();

        // TEST MODU: Örnek video URL'si
        $videoDownloadUrl = "https://www.w3schools.com/html/mov_bbb.mp4"; 

        // Videoyu İndirme ve Kaydetme
        try {
            $videoContent = Http::get($videoDownloadUrl)->body();
            $localPath = "reels/{$userId}/{$mediaId}.mp4";
            
            Storage::disk('local')->put($localPath, $videoContent);
            
            
            $reel = Reel::create([
                'user_id' => $userId,
                'reels_url' => $reelUrl,
                'status' => 'pending',
            ]);

            
        
            UploadReelToFoundByApp::dispatch($reel, $localPath);

            return response()->json([
                'status' => 'success',
                'local_path' => "/storage/" . $localPath
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to download video: ' . $e->getMessage()], 500);
        }
    }
   
    public function listReels()
    {

        $reels = Reel::latest()->get();

        return response()->json([
            'status' => 'success',
            'count' => $reels->count(),
            'data' => $reels
        ]);
    }
}