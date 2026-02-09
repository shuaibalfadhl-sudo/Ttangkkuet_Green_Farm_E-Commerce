<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Logo;
use App\Models\ContactInfo;
use App\Models\SocialLinks;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Listeners\UpdateLastLogin;

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
        Event::listen(
            Login::class, // The event we are listening for (successful login)
            [UpdateLastLogin::class, 'handle'] // The listener that runs the logic
        );
        View::composer('layouts.admin', function ($view) {
        $mail = Contact::count();
        $view->with('mail', $mail);
    });
        view()->composer('*', function ($view) {
        $view->with('socialLinks', SocialLinks::first());
        });
        View::composer('*', function ($view) {
        $contactInfo = ContactInfo::first(); // or whatever your logic is
        $view->with('contactInfo', $contactInfo);
        });
        // share categories with all views so pages like login/layouts that expect $categories won't error
        view()->share('categories', Category::orderBy('name')->get());

        View::composer('layouts.app', function ($view) {
            $categories = Category::orderBy('name')->get();
            $view->with('categories', $categories);
        });
        view()->composer('*', function ($view) {
        $view->with('logo', Logo::first());
        });
         View::composer('layouts.admin', function ($view) {
        $unreadCount = 0;

        if (Auth::check()) {
            $unreadCount = Message::unread()
                ->where('receiver_id', Auth::id()) // admin as receiver
                ->count();
        }

        $view->with('unreadCount', $unreadCount);
    });
    }
}
