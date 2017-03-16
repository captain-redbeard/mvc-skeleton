# mvc-skeleton
PHP based MVC skeleton.

---

### Requirements

* PHP >= 7.0.0
* Composer
* redbeards/crew

---

### Apache config
Ensure your root folder is set to the **public** folder.

```
RewriteEngine On

RewriteBase /

RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f

RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
```

---

### Nginx config
Ensure your root folder is set to the **public** folder.

```
location / {
    try_files $uri $uri/ @qr;
}

location @qr {
    rewrite ^/(.+)$ /index.php?url=$1 last;
}
```

---

### Routing
Routing is automatically handled via the **Router** class. The Router will split the URL by slashes and then work in the following order.

* check if **url[0]** is a valid controller
* check if **url[1]** is a valid and public method
* if **url[0]** is a valid controller, call controller
* if **url[1]** is a valid method and public, call method
* finally, any remaining **url** are interrupted as **GET** parameters
* if the above conditions are not met, then all of **url** is interrupted as **GET** parameters
* if there are **POST** parameters they are appended to the last **url**
* now supports sub folders e.g. Controllers/Members/MyController

##### Example 1
```
URL: http://example.com/home/index

home = controller
index = method
```

##### Example 2 equals Example 1 in short form
```
URL: http://example.com/

controller = default
method = default
```

##### Example 3
```
URL: http://example.com/home/index/example1/example2

home = controller
index = method
example1 = method variable 1
example1 = method variable 2
```

##### Example 4 equals Example 3 in short form
```
URL: http://example.com/example1/example2

example1 = no controller, defaults to default controller
example2 = no method, defaults to default method
example1 = method variable 1
example2 = method variable 2
```

---

### Example Controller
The following is an example controller to test the above Routing.


```
<?php
namespace Demo\Controllers;

class Home extends Controller
{
    public function __construct()
    {
        //Start session
        $this->startSession();
    }
    
    //Parameters are derived from the URL by exploding at slash
    public function index($param1 = null, $param2 = null)
    {
        //View page
        $this->view(
            [                                                       //Page(s) to load from Views
                'template/navbar',
                'home'
            ],
            [
                'page' => 'home',                                   //Page, used in Views/templates/header.php
                'page_title' => $this->config('site.name'),         //Page title, used in Views/templates/header.php
                'page_description' => 'site description',           //Page description, used in Views/templates/header.php
                'page_keywords' => 'redbeard, example',             //Page keywords, used in Views/templates/header.php
                'token' => $_SESSION['token'],                      //XSS token is automatically generated
                'param1' => $param1,                                //Example GET parameter 1
                'param2' => $param2                                 //Example GET parameter 2
            ],
            false                                                   //Hide templates (header/footer)
        );
    }
}


```

---

### Config
Config files are set in a PHP file returning an array. Sub arrays are accessed with a **.** for separation as seen below (up to four levels).
Config files can be created under the Config directory to be automatically loaded into the Config array.

##### Get config from controller
```
$this->config('app.timezone')
```

##### Set config from controller
```
$this->config('app.timezone', 'UTC');
```

##### Get config directly
```
Config::get('app.timezone');
```

##### Set config directly
```
Config::set('app.timezone', 'UTC');
```
