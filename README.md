# Comfy
Open Source PHP Framework
<hr />
## Download ##

Get started right now, download [here](https://github.com/EmanuelOprea/Comfy/archive/master.zip).
<hr />
## Intro ##
Comfy is an app development framework made in PHP. 
It is made following the MVC Programming Design Pattern.
MVC stands for [Model - View - Controller](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller).
This is a very early stage, so any feedback would be much appreciated !
<hr />
## WebServer Requirements ##

Can be installed on `Apache` or `IIS`.
Features both `.htaccess` and `web.config` to suit both scenarios.
Currently Comfy needs PHP >= 5.5 to run.
<hr />
### Config ###

Initial file to review is `index.php`.
Configuration starts from your host's `Environment Variable` or `System Variable`.
The key must be named `CMFY_ENV` and must have one of the following values:

1. `local`
2. `testing`
3. `production`

By default if this is not found, `local` is set.
<hr />
### Databases ###

Databases are configured by default within `application/config/config.php` file relative to your instalation folder.
The framework supports multiple database connections.
Also currently only `Microsoft SQL Server` and `MySQL` are supported.
<hr />
### Helpers ###

**Comfy** comes to your aid with some helpers in case you need which you can load by using `$this->loadHelper('helpername')` command.
Currently the following are available:

**1. AJAX Helper** - _pagination_ , _filter_ , _order_ , _sort_

**2. Email Helper** - _send emails easily_

**3. LDAP Helper** - _authenticate users with LDAP protocol_

**4. Session Helper** - _custom session handler_

**5. Upload Helper** - _upload files_
<hr />
### Plugins ###

**Comfy** also supports `plugins` which are basically 3rd party libraries and as an example it comes with PHPExcel directly.
<hr />
### Changelog ###

A changelog is coming as well.
<hr />
### License ###

Please read the license agreement [here](https://github.com/EmanuelOprea/Comfy/blob/master/LICENSE).
<hr />
### Documentation ###

Currently this is a very early stage of development and time is very little. All you see on this page is a starting point.

In time documentation will come, stay tuned.

Feedback and issues are also extremely valuable as constructive criticism allows me to move the project forward.
<hr />
# Enjoy! #