## Laravel 5 FullContact API

* In your .env file set FullContact API key in FULLCONTACT_API_KEY
* [`php artisan vendor:publish --provider="Akaramires\FullContact\FullContactServiceProvider"`]
* In your `app/config/app.php` add provider:
	* `'Akaramires\FullContact\FullContactServiceProvider'`
* In your `app/config/app.php` add alias:
	*  `'FullContact' => Akaramires\FullContact\FullContactFacade::class,`


### Usage:
Lookup by email:
	* `$person = FullContact::lookupByEmail( 'shawn@mantelope.io' );`

Lookup by person:
	* `$person = FullContact::lookupByPhone( '123-456-7890' );`

Lookup by Twitter: 
	* `$person = FullContact::lookupByTwitter( 'MantelopeMusk' );`

Lookup by Facebook:
	& `$person = FullContact::lookupByFacebook( 'mike-corkum' );`

