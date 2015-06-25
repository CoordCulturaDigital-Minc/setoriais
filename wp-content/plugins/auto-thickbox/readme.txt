=== Auto Thickbox ===
Contributors: Denis-de-Bernardy
Donate link: http://www.semiologic.com/partners/
Tags: lightbox, thickbox, shadowbox, gallery, semiologic
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: trunk

Automatically enables thickbox on thumbnail images (i.e. opens the images in a fancy pop-up).


== Description ==

The Auto Thickbox plugin for WordPress automatically enables thickbox on thumbnail images (i.e. opens the images in a fancy pop-up), through the use of WordPress' built-in thickbox library.

In the event you'd like to override this for an individual image, you can disable the behavior by adding the 'nothickbox' class to its anchor tag.


= Thickbox Galleries =

By default, the auto thickbox plugin will bind all images within a post into a single thickbox gallery. That is, next image and previous image links will appear so you can navigate from an image to the next.

The behavior is particularly interesting when you create galleries using WordPress' image uploader. Have the images link to the image file rather than the attachment's post, and you're done.

On occasion, you'll want to split a subset of images into a separate gallery. Auto Thickbox lets you do this as well: add an identical rel attribute to each anchor you'd like to group, and you're done.

(Note: To set the rel attribute using WordPress' image uploader, start by inserting the image into your post. Then, edit that image, browse its advanced settings, and set "Link Rel" in the Advanced Link Attributes.)

= Thickbox Anything =

Note that thickbox works on any link, not merely image links. To enable thickbox on an arbitrary link, set that link's class to thickbox.

= No thickbox =

In the event you want to disable thickbox on some links to images, assign it a nothickbox class.

= Hat Translators =

- German: hakre
- Portuguese/Brazil: Henrique Schiavo

= Help Me! =

The [Semiologic forum](http://forum.semiologic.com) is the best place to report issues. Please note, however, that while community members and I do our best to answer all queries, we're assisting you on a voluntary basis.

If you require more dedicated assistance, consider using [Semiologic Pro](http://www.getsemiologic.com).


== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


== Change Log ==

= 2.0.3 =

- Fix conflict with wp cron.
- Use esc_url() / Require WP 2.8.

= 2.0.2 =

- Actually load the text domain for i18n support...

= 2.0.1 =

- Restore the nothickbox functionality
- German and Brazilian Translation (requires WP 2.9 for the js part)
- Force a higher pcre.backtrack_limit and pcre.recursion_limit to avoid blank screens on large posts

= 2.0 =

- Full iFrame support
- Code enhancements and optimizations
- Localization support
