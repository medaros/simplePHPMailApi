![alt text](https://raw.githubusercontent.com/medaros/simplePHPMailApi/master/simplephpmailapi_logo.png)
## simplePHPMailApi
A simple api that checks contact form parameters and sends a mail using php built-in mail function

## How to install
To start using this api need to do is extract the .zip on your server.

## How it works
This API handle only data sent using POST requests only
It checks if all parametres of the inccoming post request match with the array **$request** elements :

```php
// required and authorized parameteres
$required = array("firstName", "lastName", "phone", "email", "message");
```

You can add more if you want.
Then it does some basic security using php **htmlspecialchars()** function.
After that it verifies the data sent using a series of **if** conditions.

```php
// verifications
if($postKey == "firstName" && strlen($post) < 20 && strlen($post) > 2) {
    $valid["firstName"] = "valid";
}
elseif($postKey == "lastName" && strlen($post) < 20 && strlen($post) > 2) {
    $valid["lastName"] = "valid";
}
elseif($postKey == "message" && strlen($post) > 50 && strlen($post) < 500) {
    $valid["message"] = "valid";   
}
elseif($postKey == "phone" && strlen($post) == 10 && is_numeric($post)) {
    $valid["phone"] = "valid";
}
elseif($postKey == "email" && strlen($post) > 10 && strlen($post) < 50 && preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $post)) {
    $valid["email"] = "valid"; 
}
else {
    // si ca matche pas avec les patterns de chaque if - if the current input does not match with it corresponding regex
    $valid[$postKey] = "notvalid";
}
```

Finally ! it sends the email :

```php
// email title
if(mail("arosmed3@gmail.com", "mywebiste : email from $_POST[firstName] $_POST[lastName]", $message, $headers)) {
    echo json_encode(array("status" => "sent"));
}
else {
    echo json_encode(array("status" => "error"));
}
```

### Usage

#### Angular 2+

1.Create a service that will perform POST requests with contact form data to our API

```typescript
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class MailService {
  constructor() { }
}
```

2.
