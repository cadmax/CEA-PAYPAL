<h1 align="center">CEA PAYPAL</h1>

## Sobre o pacote

Este pacote foi desenvolvido com o objetivo de abstrair todo desenvolvimento de uma comunicaçao entre a aplicação e o paypal.

### Setup:
````composer require cea/paypal````

- Caso queira copiar o projeto sem instalar o composer, no composer.json do seu projeto, copie a pasta `packages` e cole na raiz do seu projeto, depois no arquivo composer.json, deixe-o assim:

````
"autoload": {
        "psr-4": {
            "App\\": "app/",
            ....
            "Cea\\Paypal\\": "packages/cea/paypal/src/"
        }
    }
````

- Em seguida, volte para o seu terminal e execute o seguinte comando:

```` 
composer dump-autoload
php artisan config:clear
php artisan cache:clear
php artisan config:cache
 ````

- Vá para o arquivo config/app.php e adicione provedores CEA e aliases da seguinte maneira:
````
'providers' => [
    ….
    cea\paypal\PaypalServiceProvider
]
````

- rode o publish

````
php artisan vendor:publish --provider="cea\paypal\PaypalServiceProvider"
```


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
