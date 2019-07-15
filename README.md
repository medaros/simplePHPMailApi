![alt text](https://raw.githubusercontent.com/medaros/simplePHPMailApi/master/simplephpmailapi_logo.png)
## simplePHPMailApi
A simple api that checks contact form parameters and sends a mail using php built-in mail function

## How to install
To start using this api need to extract the .zip on your server.  

## How it works
This API handle only data sent using POST requests only.  
It returns one object with one property and four possible values as :

| Request       | Response              |
| ------------- |:----------------------|
| POST          | status :  notvalid *(string)* // if one of parameters is missing or does not match with if verification
|               |           sent // if the email is sent
|               |           error // if there is a probleme with the mail() function

It checks if all parametres of the inccoming post request match with the array **$request** elements :  

```php
// required and authorized parameteres
$required = array("firstName", "lastName", "phone", "email", "message");
```

You can add more parameters if you wish.  
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
    // sends back an object {"status": "sent"}
    echo json_encode(array("status" => "sent"));
}
else {
    // sends back an object {"status": "error"}
    echo json_encode(array("status" => "error"));
}
```

### Usage
#### Angular 2+

1. Create a service that will perform POST requests with contact form data to our API  :

```typescript
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class MailService {
  constructor() { }
}
```

2. Import and inject HttpClient in constructor :

```typescript
import { HttpClient } from '@angular/common/http';

constructor(private http: HttpClient) { }
```

3. Create a function inside our class that we could call later to perform our request to the api :

```typescript

sendMail(data) {
 
}
  
```

4. Create a form and append its inputs :
```typescript

input = new FormData()

```

```typescript

sendMail(data) {

    this.input.append("firstName", data.firstName.value)
    this.input.append("lastName", data.lastName.value)
    this.input.append("email", data.email.value)
    this.input.append("phone", data.phone.value)
    this.input.append("message", data.message.value)

}

```

5. URL that specifies API folder location on our server :

```typescript
export class MailService {

  constructor(private http: HttpClient) { }
  
  input = new FormData()
  
  // another example https://mywebsite.com/myfolder/api_email
  URI_MAIL = "http://localhost/api_email/"
  
```

6. Perform the request and return the response :

```typescript
sendMail(data) {
 
    interface MailResponse {
      status: string;
    }

    this.input.append("firstName", data.firstName.value)
    this.input.append("lastName", data.lastName.value)
    this.input.append("email", data.email.value)
    this.input.append("phone", data.phone.value)
    this.input.append("message", data.message.value)

    return this.http.post<MailResponse>(this.URI_MAIL, this.input)
}
```

7. Finally import and inject our service in .ts of a component for example :

```typescript
import { MailService } from '../services/mail/mail.service';

export class FooterComponent implements OnInit {
    submitted = false;
    success = false;
    fail = false;
    
    constructor(private mailService: MailService) { }

    envoyerEmail() {
        this.submitted = true;

        // call sendMail function to perform http post request to send the mail
        this.mailService.sendMail(this.f).subscribe(res => {

            // si le mail est envoyé
            if(res.status == "sent") {
                // activer la div en vert qui afficher le message de succes
                this.success=true;
                this.fail=false;

            } else {
                // activer la div en rouge qui afficher le message d'erreur
                this.fail= true;
                this.success=false;

            }
        })
    } 
}
```
