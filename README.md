Picture extend
==============================================================

This extension contains extends some small tooling for visual stuff.
**It has no longer anything to do with EXT:picture of b13.** All settings regarding EXT:picture
need to be done yourself and are no longer (beginning with with v3.0.0) handled by this extension.

## Lightbox
This extension uses [baguetteBox](https://github.com/feimosi/baguetteBox.js) as lightbox script.
In `Resources/Partials` is an example shown how it can be integrated. In the example `ssch/typo3-encore`
is used to integrate the CSS and JS on the needed page.

* You have to add the node_module for lightbox e.g. with `yarn add baguettebox.js`
* To use it with webpack encore include the following in your `webpack.config.js` (path might need to be adapted depending on your setup):

``` js
    .addEntry('lightbox', './public/typo3conf/ext/picture_extend/Resources/Private/JavaScript/lightbox.js')
```
Now a gallery with activated `image_zoom` will have a lightbox.

## InlineSvgViewHelper
A Fluid ViewHelper which allows you to integrate an SVG inline to minimize request.
I would not recommend this if your SVG is quite huge because that blows up your HTML which
can hav bad impact on the rendering of the page.
