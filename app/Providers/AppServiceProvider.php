<?php

namespace App\Providers;

use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;
use Illuminate\Support\Facades\View;
use App\Models\Settings;
use App\Models\SettingsCont;
use App\Models\TermsPrivacy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        FacadesStorage::extend('sftp', function ($app, $config) {
            return new Filesystem(new SftpAdapter($config));
        });

        Paginator::useBootstrap();

        // Pula currency directive: @pula($amount) => "P1,500.00"
        \Illuminate\Support\Facades\Blade::directive('pula', function ($expression) {
            return "<?php echo \\App\\Support\\Money::pula($expression); ?>";
        });

        // Sharing settings with all views. Guarded so the app still boots on a
        // fresh install / during testing before the settings tables exist.
        try {
            if (Schema::hasTable('settings')) {
                $settings = Settings::where('id', '1')->first();
                $terms = Schema::hasTable('terms_privacies') ? TermsPrivacy::find(1) : null;
                $moreset = Schema::hasTable('settings_conts') ? SettingsCont::find(1) : null;

                View::share('settings', $settings);
                View::share('terms', $terms);
                View::share('moresettings', $moreset);
                View::share('mod', optional($settings)->modules);
            }
        } catch (\Throwable $e) {
            \Log::warning('Settings view-share skipped: ' . $e->getMessage());
        }
    }
}