# LiquidChurch Functionality #
**Contributors:**      Justin Sternberg  
**Donate link:**       http://www.liquidchurch.com/  
**Tags:**  
**Requires at least:** 4.4  
**Tested up to:**      4.4  
**Stable tag:**        0.0.0  
**License:**           GPLv2  
**License URI:**       http://www.gnu.org/licenses/gpl-2.0.html  

## Description ##

Adds custom functionality for use on http://www.liquidchurch.com

To add 'Resources' output to your theme, you can use the shortcode, or use `do_action`:

For example, to output all of the resource types, you could put the following in your single template file:

```php
<?php do_action( 'sermon_resources', array(
	'resource_type'      => array( 'files', 'urls' ),
	'resource_file_type' => array( 'image', 'video', 'audio', 'pdf', 'zip', 'other' ),
	'resource_post_id'   => get_the_id(),
) ); ?>
```

## Installation ##

### Manual Installation ###

1. Upload the entire `/liquidchurch-functionality` directory to the `/wp-content/plugins/` directory.
2. Activate LiquidChurch Functionality through the 'Plugins' menu in WordPress.

## Frequently Asked Questions ##


## Screenshots ##


## Changelog ##

### 0.0.0 ###
* First release

## Upgrade Notice ##

### 0.0.0 ###
First Release
