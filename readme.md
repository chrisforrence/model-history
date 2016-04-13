Provides an easy way to track changes to Laravel models

## Great, what does this do?

The main "payload" of this package is a trait that you can give to your Eloquent models. When that model is created/updated/deleted, an entry is written to the database with 

* Which user updated the model,
* (On an update) what information changed on the model,
* A display-friendly message 

## How do I install it?

Run the following command:

    composer require cforrence/model-history

Next, add the service provider to your array in config/app.php:

    
    'providers' => [
        ...

        Cforrence\Providers\ModelHistoryServiceProvider::class,

    ],

Publish the assets (a database migration and configuration file):

    php artisan vendor:publish --provider="Cforrence\Providers\ModelHistoryServiceProvider"

Finally, run the database migration:

    php artisan migrate

That will set up a `model_history` table within your application.

## Ok, after all that, how do I actually **use** it?

In your model, just add `use Cforrence\Traits\MaintainHistory;` within your class definition:

    public class Post extends Eloquent {

        use \Cforrence\Traits\MaintainHistory;

        ...
    }

## How do I get all the changes for a particular model?

You can use the `history` method on the model:

    $post->history
