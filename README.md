![PHP:7.3](https://img.shields.io/static/v1?label=PHP&message=7.3&color=green)
![License:WTFPL](https://img.shields.io/static/v1?label=License&message=WTFPL&color=green)
![Tests:Passing](https://img.shields.io/static/v1?label=Tests&message=Passing&color=green)

# PHP SSHA Password

Library is a wrapper around PHPs password_() functions with support for SSHA. There was a certain need to decode
password hashes generated via OpenLDAP, so here we are...

# Extended Functions

 * password_hash()
 * password_verify()
 * password_get_info()
 * password_needs_rehash()

# Usage

```bash
composer require mrccnt/password-ssha
composer test
composer testdox
```
See [example.php](example.php) for detailed usage infos.

```bash
php example.php
```

    Generated Hash:     $2y$10$Ce60B.N4pS9WfYh0yA69UeyVjbeIfTRZjFCVgiyDVCP9MmmgU5vpi
    Test Password:      Ok
    Info Algo:          1
    Info Name:          bcrypt
    Info Options:       {"cost":10}
    Rehash bcrypt (10): false
    Rehash bcrypt (14): true
    Rehash ssha:             true
    
    Generated Hash:     {SSHA}syze7uEZa57iOmlfa5wbYmKB9+kyOTc4YmEzYzJkZjM=
    Test Password:      Ok
    Info Algo:          333
    Info Name:          ssha
    Info Options:       []
    Rehash bcrypt (10): true
    Rehash bcrypt (14): true
    Rehash ssha:        false