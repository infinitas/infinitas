There are a number of configuration options for the Users plugin that can control everything from the password requirements to login access.

#### Password regex

To change the password requirements you can adjust or change the regex used in the Configs plugin. By default passwords should be between 4 and 8 chars, contain mixed case letters and numbers. This can be configured with any valid [regex](http://en.wikipedia.org/wiki/Regular_expression) by changing the value of `Website.password\_regex`.

You can also change the validation message to match the new regex by adjusting the `Website.password\_validation` config option.

#### Allow logins

By default users are allowed to login to the site. To disable login you can set the config option `Website.allow\_login` to false.

#### Allow registration

If you have a closed system where login is enabled but would like to manually create users you can disable registration. This option is good for sites such as `intranets` where the administrator will create and manage user accounts. To disable registration set `Website.allow_registration` to false. The default for this option is true.