<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            \Illuminate\Support\Facades\Storage::extend('google', function ($app, $config) {
                $client = new \Google\Client;
                $client->setClientId($config['clientId']);
                $client->setClientSecret($config['clientSecret']);

                $accessToken = $client->fetchAccessTokenWithRefreshToken($config['refreshToken']);
                if (isset($accessToken['error'])) {
                    throw new \Exception('Google Drive Token Error: '.json_encode($accessToken));
                }

                $client->setAccessToken($accessToken);

                $service = new \Google\Service\Drive($client);
                $options = [];
                if (isset($config['options'])) {
                    $options = $config['options'];
                }

                // Fix: To use an ID as a root but still allow path-based subfolders,
                // we must pass the ID in 'sharedFolderId' and set the constructor root to null.
                if (isset($config['folderId']) && $config['folderId'] !== 'root') {
                    $options['sharedFolderId'] = $config['folderId'];
                    $root = null;
                } else {
                    $root = $config['folderId'] ?? 'root';
                }

                $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $root, $options);
                $driver = new \League\Flysystem\Filesystem($adapter);

                return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Drive Configuration Error: '.$e->getMessage());
        }
    }
}
