Foundation Skeleton
===================
Wordpress base theme built on the Foundation Responsive Framework.

## Configuration
Columns and widget locations are controlled in a config.ini file.  A child-theme can override the parent themes config.ini file options.  A child theme does not need to contain every config.ini option but only the ones it wants to change (ideal).

### PHP Config Override Function
When building out a theme and you want to create a custom page type that disables widget locations such as a homepage, you can use the following function to do so (not this cannot be used to enable widget locations as they will not get populated into the admin widget page).

```php
FoundationSkeleton::setConfig( 'zone', 'widget', 'option', value );
```

example:
```php
FoundationSkeleton::setConfig( 'content', 'widget_first', 'enabled', 0 );
FoundationSkeleton::setConfig( 'footer', 'widget_prefix1', 'enabled', 0 );
```

### Built-in Menu Locations
Top Menu: *This menu is positioned before the section-header zone*
Header Menu: *This menu is positioned between section-header and section-content zones*
Footer Menu: *This menu is positioned after the section-footer zone*


### Widgets
A widget location will not render if no widgets have been assigned to the location.
@TODO: create config.ini option to force render of a location even if no widgets have been assigned


### Menus
Menus can be disabled by setting the value to an empty string `top-menu = ''`


### Admin Menu
When Debugging is enabled an Admin menu is created called *Foundation Skeleton* where debug options can be turned on/off live (page reload will reset to default debug options).
