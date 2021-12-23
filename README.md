Picute extend
==============================================================

This extension contains extends EXT:picture of b13 with the structure to use it properly on multiple pages with similar requirements.

**Hint**: This extension is under heavy development, I'm still learning a lot and I am not tagging anything (so there is only the master branch). So at the moment you cannot rely on it ;)

## Lightbox
This extension uses [baguetteBox](https://github.com/feimosi/baguetteBox.js) as lightbox script.
To use it with webpack encore include the following in your `webpack.config.js` (path might need to be adapted depending on your setup):

```
    .addEntry('lightbox', './public/typo3conf/ext/picture_extend/Resources/Private/JavaScript/lightbox.js')
```

Now a gallery with activated `image_zoom` will have a lightbox.

## Definition of output dimensions
**Usage:** Define how big the images shall be rendered. Defined for every cropVariant.

One step is needed:
The site package has a `EXT:the_site_package/Configuration/TypoScript/ContentElement/PageHero.typoscript` where the output dimensions are defined as follows:

```
lib.contentElement.settings.responsiveimages.contentelements {
  page_hero {
    width {
      lg = 1600
      md = 1200
      sm = 768
      xs = 576
    }
  }
}
```
