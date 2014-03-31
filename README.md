#sharpd3toons

##D3 Character list to test D3 API
---
For personal testing, remember to...

* Modify **views/includes/css.twig** and **assets/style.css** as preferred
* Modify **.htaccess** as preferred, or delete and modify Apache Directory config
	* Currently using mod_dir's FallbackResource instead of mod_rewrite's RewriteEngine
* If testing on Heroku, remove **vendor/** from **.git_ignore**, or use a custom buildpack
	* I'm currently using [bergie/heroku-buildpack-php](https://github.com/bergie/heroku-buildpack-php.git)