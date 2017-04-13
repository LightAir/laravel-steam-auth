# Steam authentication for lumen 5.4.*

This package is a Lumen 5.4.* service provider which provides support for Steam OpenID and is very easy to integrate with any project that requires Steam authentication.

## Installation Via Composer
Add this to your `composer.json` file, in the require object:

```bash
"lightair/lumen-auth-via-steam": "v5.4.0"
```

After that, run `composer install` to install the package.

In file bootstrap/app.php uncomment `````$app->withFacades()````` and add:

```php
$app->register(LightAir\LumenAuthViaSteam\SteamServiceProvider::class);
```

Lastly, publish the config file.



```
php artisan vendor:publish
```
## Usage example
In `config/steam-auth.php`
```php
return [

    /*
     * Redirect URL after login
     */
    'redirect_url' => '/login',
    /*
     *  API Key (http://steamcommunity.com/dev/apikey)
     */
    'api_key' => 'Your API Key',
    /*
     * Is using https?
     */
    'https' => false
];

```
In `routes/web.php`
```php
$app->get('/login',  'AuthController@login');
```
In `AuthController`
```php
namespace App\Http\Controllers;

use LightAir\LumenAuthViaSteam\SteamAuth;
use App\User;
use Auth;

class AuthController extends Controller
{
    /**
     * @var SteamAuth
     */
    private $steam;

    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    public function login()
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();
            if (!is_null($info)) {
                $user = User::where('steamid', $info->steamID64)->first();
                if (is_null($user)) {
                    $user = User::create([
                        'username' => $info->personaname,
                        'avatar'   => $info->avatarfull,
                        'steamid'  => $info->steamID64
                    ]);
                }
            	Auth::login($user, true);
            	return redirect('/'); // redirect to site
            }
        }
        return $this->steam->redirect(); // redirect to Steam login page
    }
}

```
