<?php

namespace App\Console\Commands;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Console\Command;

class GoogleTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new Google Drive Refresh Token';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clientId = env('GOOGLE_DRIVE_CLIENT_ID');
        $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');

        if (! $clientId || ! $clientSecret) {
            $this->error('GOOGLE_DRIVE_CLIENT_ID and GOOGLE_DRIVE_CLIENT_SECRET must be set in .env');

            return 1;
        }

        $client = new Client;
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->addScope(Drive::DRIVE);

        $authUrl = $client->createAuthUrl();

        $this->info('1. Open the following URL in your browser and authorize the application:');
        $this->line($authUrl);

        $authCode = $this->ask('2. Enter the authorization code here');

        if (! $authCode) {
            $this->error('Authorization code is required');

            return 1;
        }

        try {
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            if (isset($accessToken['error'])) {
                $this->error('Error fetching access token: '.json_encode($accessToken));

                return 1;
            }

            $this->info('New Refresh Token:');
            $this->line($accessToken['refresh_token'] ?? 'No refresh token returned. Make sure to revoke access first if re-generating.');
            $this->info('Add this to your GOOGLE_DRIVE_REFRESH_TOKEN in .env');

        } catch (\Exception $e) {
            $this->error('Exception: '.$e->getMessage());

            return 1;
        }

        return 0;
    }
}
