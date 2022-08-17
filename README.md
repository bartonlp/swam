# www.swam.us

This is the **Southwest Aquatic Master's** web site. Located at **Pierce College, Woodland Hills, California**

This site is hosted by **LampHost** (lamphost.com).

This site uses PHP 5 and we do not have root access. The version of *SiteClass* used here does not have some of the new PHP 7 features
like ?? or assining an array. Because we do not have root access we can't add apache modules, specifically 'header', and we have to
use
```
    'putenv("SITELOAD=/var/www/zupons.net/vendor/bartonlp/site-class/includes");' 
```
to set the path to SiteClass. Also because the apache
can't write to user 'johnzupon' and we can't do a chmod to set the group to www-data we have to use 
```
    'ini_set("error_log", "/tmp/PHP_ERROR.log");'
```
to set the error log in /tmp which is the only place that anyone can write to.

This site uses a special version of *SiteClass* (https://github.com/bartonlp/site-class):
```
    composer require bartonlp/site-class:dev-zupons
```

## Contact Barton Phillips: [bartonphillips@gmail.com](mailto:bartonphillips@gmail.com)
## Contact John Zupon: [john@zupons.net](mailto:john@zupons.net)



