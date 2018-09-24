# Paste Content plugin
Use this plugin if you want to paste own predetermined simple content (snippets) or HTML blocks to the CMS or plugins.

## Advantages
* __It works with build-in CMS__ (Pages, Partials and Layout).
* __It works with any plugins__ ([News & Newsletter](http://octobercms.com/plugin/indikator-news), [Content Plus](http://octobercms.com/plugin/indikator-content), etc.).
* __Support multilanguage content__ Works with [Rainlab Translate](http://octobercms.com/plugin/rainlab-translate) plugin.
* __Easy to paste snippets__ with the build-in WYSIWYG editor.

## Works with CMS
### Usage steps
1. Add a new content on the __Paste Content__ page.
1. Use the __paste__ Twig function.
1. Add the __type and code__ of content.

### Example
__Predetermined content__

Code: ads

Text: ADVERTISEMENT HERE

__HTML code__
```
<p>Lorem ipsum dolor sit amet, nibh aute et sodales at arcu, urna libero, euismod pharetra vestibulum tristique praesent. Ligula integer natoque ut praesent sapien, ligula placerat nisl neque id commodi, quis dictum sit erat ante mollis nascetur.</p>

{{ paste('text', 'ads') }}
```

__The result__

Lorem ipsum dolor sit amet, nibh aute et sodales at arcu, urna libero, euismod pharetra vestibulum tristique praesent. Ligula integer natoque ut praesent sapien, ligula placerat nisl neque id commodi, quis dictum sit erat ante mollis nascetur.

ADVERTISEMENT HERE

### Twig function options
Name | Description
----------- | -----------
First parameter | The type of content: _text_ or _code_
Second parameter | The code of content or the ID of content

## Works with plugins
### Usage steps
1. Add a new content on the __Paste Content__ page.
1. __Paste the code__ of content to the blog post or other text.
1. Use the __paste__ Twig filter on the front-end pages.

### Example
__Predetermined content__

Code: ads

Text: ADVERTISEMENT HERE

__Blog post__

Lorem ipsum dolor sit amet, nibh aute et sodales at arcu, urna libero, euismod pharetra vestibulum tristique praesent. Ligula integer natoque ut praesent sapien, ligula placerat nisl neque id commodi, quis dictum sit erat ante mollis nascetur.

{{ ads }}

__HTML code__
```
{{ post.content_html|paste }}
```

__The result__

Lorem ipsum dolor sit amet, nibh aute et sodales at arcu, urna libero, euismod pharetra vestibulum tristique praesent. Ligula integer natoque ut praesent sapien, ligula placerat nisl neque id commodi, quis dictum sit erat ante mollis nascetur.

ADVERTISEMENT HERE

## Available languages
* en - English
* hu - Magyar

## Installation
1. Go to the __Settings > Updates & Plugins__ page in the Backend.
1. Click on the __Install plugins__ button.
1. Type the __Paste Content__ text in the search field.
