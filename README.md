# Feedland Blogroll for WordPress

## What is this?

This is a WordPress plugin which will display your FeedLand Blogroll on your WordPress site!

For more information on what these blogrolls are, please visit: https://blogroll.social/

## How to Use

1. Download the latest release: https://github.com/a8cteam51/feedland-blogroll/releases
2. On your WordPress site, navigate to **Plugins > Add New Plugin > Upload Plugin** and upload the zip file you just downloaded. Activate it.
3. Add the `[feedland-blogroll]` shortcode anywhere on your site.

### Using the Shortcode

Where you put the shortcode will depend on which theme you are using. In a classic theme, adding the shortcode to the sidebar widget would be a good spot. If you're using a block theme (like _twenty twenty-four_), then editing the site templates and adding the shortcode to the 'Single with Sidebar' template works great.

You can also output the shortcode in your php templates like this:
```PHP
echo do_shortcode( '[feedland-blogroll]' );
```
## Screenshot
<img width="702" alt="SCR-20240328-jvrd" src="https://github.com/a8cteam51/feedland-blogroll/assets/2067992/e794e178-ab66-43af-971e-eff86ff66257">

