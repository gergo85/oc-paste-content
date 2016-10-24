# Paste Content plugin
Use this plugin if you want to paste predetermined HTML content (snippets) to the blog post or other text.

## Advantages
* __It works with any plugin__ (e.g. Blog, News & Newsletter, Content Plus).
* __Easy to manage snippets__ on the Paste Content page.

## Usage steps
1. Add a new content on the __Settings > CMS > Paste Content__ page.
1. __Paste the code__ of content to the blog post or other text.
1. Use the __paste__ Twig filter on the front-end pages.

## Example
__Predetermined content__

Code: {{ads}}

Text: ADVERTISEMENT HERE

__Blog post__

Lorem ipsum dolor sit amet, nibh aute et sodales at arcu, urna libero, euismod pharetra vestibulum tristique praesent. Ligula integer natoque ut praesent sapien, ligula placerat nisl neque id commodi, quis dictum sit erat ante mollis nascetur.

{{ads}}

Fusce ac fermentum libero vel mollis arcu, velit ad amet aliquet. Tellus leo, vehicula odio pede, in elementum, donec aenean vulputate dui non, ultricies laoreet neque tortor massa. Fermentum volutpat cras vitae vel lacus.

__HTML code__
```
<div class="container">
	{{ post.content_html|paste }}
</div>
```

__The result__

Lorem ipsum dolor sit amet, nibh aute et sodales at arcu, urna libero, euismod pharetra vestibulum tristique praesent. Ligula integer natoque ut praesent sapien, ligula placerat nisl neque id commodi, quis dictum sit erat ante mollis nascetur.

ADVERTISEMENT HERE

Fusce ac fermentum libero vel mollis arcu, velit ad amet aliquet. Tellus leo, vehicula odio pede, in elementum, donec aenean vulputate dui non, ultricies laoreet neque tortor massa. Fermentum volutpat cras vitae vel lacus.

## Available languages
* en - English
* hu - Magyar

## Installation
1. Go to the __Settings > Updates & Plugins__ page in the Backend.
1. Click on the __Install plugins__ button.
1. Type the __Paste Content__ text in the search field.
