# Changelog

## Version 1.5.1

- Fixed the release date on the product page being altered by machine translation (e.g. GTranslate showed a wrong date in DE/FR); the date is now wrapped in `<time class="notranslate" translate="no">`
- Added `nt-preorder-date` class to the release date paragraph

## Version 1.5.0

- Added admin warning on the product edit screen when preorder is checked but the release date has already passed
- Added admin notice on the product list with the number of expired preorders and a filter showing them (`?nt_preorder_expired=1`)
- Added `Product::is_preorder_active()` and `Product::is_preorder_expired()` helpers (single source of truth for the theme)
- Added explanation of the release date behaviour to the preorder checkbox and release date field
- Fixed release date comparison using the server's UTC date instead of the site timezone
- Fixed malformed release dates being compared as text (which could hide the preorder text); invalid dates are no longer saved and the value is sanitized

## Version 1.4.0

- Added preorder date
- Added validation for preorder date (for displaying preorder text)

## Version 1.3.0

- Added support for post search
- Added setting for possible preorder text in search results

## Version 1.2.2

- Fixed bug with the preorder text not showing in cart and checkout page

## Version 1.2.1

- Added default value for the preorder text and position

## Version 1.2

- Added handling preorder option in the import/export

## Version 1.1

- Added option for the preorder text
- Added option for the preorder text position

## Version 1.0

- Added option for the product
- Added display of preorder text after the product title