<?php

namespace App\Traits;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client as OClient;

trait Discord
{
    public function SendMessageToDiscord($module, $func, $msg, $type = 'default') {

        $user = Auth::user();
        $user_info = '';
        if ($user) {
            $user_info = $user->id;
        }

        $now = Carbon::now()->timezone('Asia/Jakarta')->format('d-m-Y H:i:s');

        $message = ':yellow_circle: BE';
        $message .= "```";
        $message .= "date: " . $now . "\n";
        $message .= "user: " . $user_info . "\n";
        $message .= "module: " . $module . "\n";
        $message .= "func: " . $func . "\n";
        $message .= "message: " . $msg . "\n";
        $message .= "```";

        try {
            $client = new OClient();

            $url = "https://discord.com/api/webhooks/1285202244848979979/a8bB6TXCRQu5tkwEFaS6-LenTQomBBMAKXl79QXCAkKDpPAXbZMdkgKtgxDN8ERbGGDJ";
            if ($type == 'cron_log') {
                $url = 'https://discord.com/api/webhooks/1285204921506992188/vPphDqWFhKxyPkkaBzTsvhudUWujL_3v1l5Ol_-o3esAOxQovBdRLMcJyjE7SCKIuRZi';
            }

            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'content' => $message,
                ],
            ]);
        } catch (\Exception $e) {
            
        }

        return true;

    }
}