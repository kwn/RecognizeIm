Recognize.im API
===============

Recognize.im provides API for Image Recognition. Before use you need to visit [http://www.recognize.im/](http://recognize.im/) and create an account. After sign in go to the user profile section and obtain: ```Your Client ID```, ```Your API Key``` and ```Your CLAPI Key```. 

Installation
------------

Add package to your composer.json

```
"kwn/recognizeim": "dev-master"
```

Run update command

```
php composer.phar update kwn/recognizeim
```

Done. Now you can use RecognizeIm classes.

Usage
-----

```php

    use RecognizeIm\Client\RestApi;
    use RecognizeIm\Client\SoapApi;
    use RecognizeIm\Configuration;
    use RecognizeIm\Model\Image;
    use RecognizeIm\RecognizeImApi;
    
    // [...]
    
    // create a configuration object first
    $configuration = new Configuration('CLIENT_ID', 'API_KEY', 'CLAPI_KEY');
    
    // depending on your needs you can use soap or rest api 
    $soapApi = new SoapApi($configuration);
    $restApi = new RestApi($configuration, new ImageVerificator());
    
    // or you can create an object that includes both apis (useful for dependency injection containers)
    $recognizeim = new RecognizeImApi($soapApi, $restApi);
    
    // now you can use soap api functions or rest api recognize method
    
    // create an image and call recognize method 
    $image = new Image('/home/kwnuk/Obrazy/red.jpg');
    $result = $recognizeim->getRestApiClient()->recognize($image, 'multi');
```

Authorization
-------------

You don't need to call method auth by yourself. Module object will authorize you when needed, you just need do provide valid credentials. You can get them from your [account tab](http://recognize.im/user/profile)
