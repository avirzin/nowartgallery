# Now Art Gallery Child Theme

Child theme for Now Art Gallery, built on the Kiosko parent theme.

## Installation

1. Ensure the Kiosko parent theme is installed and activated
2. Upload this theme folder to `/wp-content/themes/`
3. Go to Appearance → Themes in WordPress admin
4. Activate "Now Art Gallery Child"

## Features

- Custom product meta fields for artwork (dimensions, artist name, limited edition)
- Enhanced product gallery display
- Custom styling for art gallery needs
- WooCommerce template overrides (ready for customization)

## Customization

### Adding Custom Styles

Edit `assets/css/custom.css` to add your custom styles.

### Adding Custom JavaScript

Edit `assets/js/custom.js` to add your custom JavaScript.

### Overriding WooCommerce Templates

Copy WooCommerce template files from `woocommerce/` folder in parent theme to `templates/woocommerce/` in this child theme, then customize as needed.

## Structure

```
nowartgallery-child/
├── style.css              # Child theme header (required)
├── functions.php          # Theme functions and hooks
├── assets/
│   ├── css/
│   │   └── custom.css     # Custom styles
│   └── js/
│       └── custom.js      # Custom JavaScript
├── templates/
│   └── woocommerce/       # WooCommerce template overrides
└── README.md              # This file
```

## Support

For issues or questions, refer to the main project documentation.
