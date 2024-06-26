| :exclamation:  This is a public repository |
|--------------------------------------------|

# Feedland Blogroll for WordPress

## What is this?

This is a WordPress plugin which will display your FeedLand Blogroll on your WordPress site!

For more information on what these blogrolls are, please visit: https://blogroll.social/

## How to Use

1. [Download the latest release](https://github.com/a8cteam51/feedland-blogroll/releases/latest/download/feedland-blogroll.zip)
2. On your WordPress site, navigate to **Plugins > Add New Plugin > Upload Plugin** and upload the zip file you just downloaded. Activate it.
3. Adjust the plugin settings at **Settings > FeedLand Blogroll**
4. Add the `[feedland-blogroll]` shortcode anywhere on your site to display the blogroll.

### Adding the Shortcode

Whether selecting a widget or a block, you will choose "Shortcode", then copy/paste this into the input:
```PHP
[feedland-blogroll]
```

Where you put the shortcode will depend on which theme you are using. 
- In a classic theme, adding the shortcode to the sidebar widget would be a good spot. 
- If you're using a block theme (like _twenty twenty-four_), then editing the site templates and adding the shortcode to the 'Single with Sidebar' template works great.

You can also output the shortcode in your php templates like this:
```PHP
echo do_shortcode( '[feedland-blogroll]' );
```
## Screenshots
![SCR-20240328-jvrd](https://github.com/a8cteam51/feedland-blogroll/assets/2067992/e794e178-ab66-43af-971e-eff86ff66257)

![SCR-20240418-jces](https://github.com/a8cteam51/feedland-blogroll/assets/2067992/e6fa9afd-bd88-42af-9225-02b2d66101e2)


## Disclaimer
This public plugin is under active development. Please use at your own discretion and test thoroughly before adding to a production site.
