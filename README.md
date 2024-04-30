# Sitegeist.CookieBouncer 
## Whitelist cookies before the Flowpack.FullPaheCache Middleware

### Authors & Sponsors

* Martin Ficzel - ficzel@sitegeist.de
* Melanie Wüst - wuest@sitegeist.de

*The development and the public-releases of this package is generously sponsored by our employer https://www.sitegeist.de.*

## Configuration

```yaml
Sitegeist:
  CookieBouncer:
    # List of cookies that are allowed
    allowPatterns: []

    # List of cookies to always be rejected. By suggest to reject all.
    # !!! The Neos-Session cookie is always allowed !!! 
    denyPatterns: ['*']
```

## Installation

Sitegeist.CookieBouncer is available via packagist. Just run `composer require sitegeist/cookiebouncer` to install it. We use semantic-versioning so every breaking change will increase the major-version number.

## Contribution

We will gladly accept contributions. Please send us pull requests.
