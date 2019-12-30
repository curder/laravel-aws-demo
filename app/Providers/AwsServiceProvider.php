<?php

namespace App\Providers;

use App\Services\S3Service;
use Illuminate\Support\ServiceProvider;

class AwsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // S3
        $this->app->bind(S3Service::class, function () {
            $s3 = new S3Service();
            $config = [
                'version' => config('aws.s3.version'),
                'region'  => config('aws.s3.region')
            ];
            $s3->setConfig($config);
            return $s3;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
