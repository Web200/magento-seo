# Seo

Magento 2 Module SEO

## Installation

```
$ composer require "web200/magento-seo"
```

## Features

* Add [Opengraph](https://ogp.me/) for Category / Product / Page
* Add Microdata as json for [Breadcrumbs](https://developers.google.com/search/docs/data-types/breadcrumb) / [Logo](https://developers.google.com/search/docs/data-types/logo) / [Product](https://developers.google.com/search/docs/data-types/product) / [Sitelink Searchbox](https://developers.google.com/search/docs/data-types/sitelinks-searchbox)
* Add [Twitter Card](https://developer.twitter.com/en/docs/twitter-for-websites/cards/guides/getting-started)
* Add [Hreflang tag](https://developers.google.com/search/docs/advanced/crawling/localized-versions) for Category / Product / Page / HomePage
* Add Html sitemap for Category
* Add canonical on category pagination tag : canonical / rel="prev" / rel="next"
* Add **NOINDEX, NOFOLLOW** on category page with params other than "p"
* Add product canonical in sitemap.xml
* Add specific Meta Robots to product page (**INDEX, FOLLOW / NOINDEX, FOLLOW / INDEX, NOFOLLOW / NOINDEX, NOFOLLOW**)
* Add specific Meta Robots to cms page (**INDEX, FOLLOW / NOINDEX, FOLLOW / INDEX, NOFOLLOW / NOINDEX, NOFOLLOW**)
* Add canonical only on one store (if product exist in others)

You can enable or disable all options on _Store > Configuration > General > Search Engine Optimization_
