# Sutra Theme & Branding System

## Overview
Complete theme and branding system for Sutra SaaS platform with support for:
- **Dark/Light Mode**: Toggle between dark and light theme modes
- **Consistent Color Palette**: Brand colors used across the entire application
- **Shop Branding**: Custom logos, banners, and watermarks for each shop
- **Default Assets**: Admin and shop default branding when custom assets are not available

---

## Color Palette

### Primary Colors (Indigo)
```
Primary:       #6366f1
Primary Dark:  #4f46e5
Primary Light: #818cf8
```

### Secondary Colors (Purple)
```
Secondary:       #a855f7
Secondary Dark:  #9333ea
Secondary Light: #c084fc
```

### Accent Colors (Pink)
```
Accent:       #ec4899
Accent Dark:  #db2777
Accent Light: #f472b6
```

### Background Colors
**Dark Mode:**
- Background: #0f172a (slate-950)
- Background Secondary: #1e293b (slate-800)

**Light Mode:**
- Background: #ffffff
- Background Secondary: #f1f5f9 (slate-100)

---

## Backend Usage

### Theme Helper (PHP)
Located in: `app/Support/Theme.php`

#### Get Color Palette
```php
use App\Support\Theme;

// Get dark mode palette
$darkPalette = Theme::getPalette('dark');

// Get light mode palette
$lightPalette = Theme::getPalette('light');

// Example of palette structure
$darkPalette['primary'];        // #6366f1
$darkPalette['text_primary'];   // #ffffff
$darkPalette['background'];     // #0f172a
```

#### Get Admin Branding
```php
$adminBranding = Theme::getAdminBranding();
// Returns: ['logo', 'banner', 'watermark', 'favicon']
```

#### Get Shop Defaults
```php
$shopDefaults = Theme::getShopDefaults();
// Returns default shop branding paths
```

### Shop Branding Helper
Located in: `app/Support/ShopBranding.php`

#### Usage
```php
use App\Support\ShopBranding;

$shop = Shop::find(1);
$branding = new ShopBranding($shop);

// Get individual assets
$logo = $branding->getLogo();      // Path or default
$banner = $branding->getBanner();  // Path or default
$watermark = $branding->getWatermark(); // Path or default

// Get all as array
$all = $branding->getAll();

// Get as JSON-ready array
$json = $branding->toArray();
```

### Theme Middleware
Located in: `app/Http/Middleware/SetThemeMode.php`

Automatically shares theme data with all views:
- `$themeMode`: Current theme mode ('dark' or 'light')
- `$themePalette`: Array of colors for current mode
- `$adminBranding`: Admin branding assets
- `$shopDefaults`: Default shop branding

---

## Frontend Usage

### ThemeProvider (React)
Located in: `resources/js/Support/ThemeProvider.jsx`

#### Wrap Your App
```jsx
import { ThemeProvider } from '@/Support/ThemeProvider';

function App() {
    return (
        <ThemeProvider initialMode="dark">
            {/* Your app components */}
        </ThemeProvider>
    );
}
```

#### Use Theme Hook
```jsx
import { useTheme } from '@/Support/ThemeProvider';

function MyComponent() {
    const { mode, toggleTheme } = useTheme();
    
    return (
        <button onClick={toggleTheme}>
            Current mode: {mode}
        </button>
    );
}
```

#### Theme Toggle Button
```jsx
import { ThemeToggle } from '@/Support/ThemeProvider';

export default function Header() {
    return (
        <header>
            <ThemeToggle /> {/* Automatic dark/light toggle */}
        </header>
    );
}
```

### Conditional Styling Based on Theme
```jsx
import { useTheme } from '@/Support/ThemeProvider';

function Card() {
    const { mode } = useTheme();
    const isDark = mode === 'dark';
    
    return (
        <div className={isDark ? 'bg-slate-900 text-white' : 'bg-white text-slate-900'}>
            {/* Content */}
        </div>
    );
}
```

### Shop Branding (React)
Located in: `resources/js/Support/BrandingProvider.jsx`

#### Use Shop Logo
```jsx
import { ShopLogo } from '@/Support/BrandingProvider';

function Header() {
    return <ShopLogo className="w-12 h-12" />;
}
```

#### Use Shop Banner
```jsx
import { ShopBanner } from '@/Support/BrandingProvider';

function Banner() {
    return <ShopBanner className="w-full h-64" />;
}
```

#### Get Branding Directly
```jsx
import { useShopBranding, useAdminBranding } from '@/Support/BrandingProvider';

function MyComponent() {
    const shopBranding = useShopBranding();
    const adminBranding = useAdminBranding();
    
    return (
        <img src={shopBranding.logo} alt="Shop" />
    );
}
```

---

## Tailwind CSS Integration

### Brand Color Variables in Tailwind
```javascript
// tailwind.config.js includes these colors:

colors: {
    'sutra-primary': '#6366f1',
    'sutra-primary-dark': '#4f46e5',
    'sutra-primary-light': '#818cf8',
    'sutra-secondary': '#a855f7',
    'sutra-secondary-dark': '#9333ea',
    'sutra-secondary-light': '#c084fc',
    'sutra-accent': '#ec4899',
    'sutra-accent-dark': '#db2777',
    'sutra-accent-light': '#f472b6',
}
```

### Using in Tailwind
```jsx
// Use brand colors
<button className="bg-sutra-primary text-white">Click Me</button>
<div className="border-sutra-accent">Accent Border</div>
<span className="text-sutra-primary-light">Light Text</span>
```

---

## Theme Routes

### Toggle Theme
```
POST /theme/toggle
```
Switches between dark and light mode.

### Set Theme Explicitly
```
POST /theme/set
Body: { "mode": "dark" } or { "mode": "light" }
```

---

## Database Columns for Shop Branding

Add these columns to `shops` table if not present:
```sql
ALTER TABLE shops ADD COLUMN logo_path VARCHAR(255) NULLABLE;
ALTER TABLE shops ADD COLUMN banner_path VARCHAR(255) NULLABLE;
ALTER TABLE shops ADD COLUMN watermark_path VARCHAR(255) NULLABLE;
```

---

## File Structure
```
app/
├── Support/
│   ├── Theme.php              # Color palette & defaults
│   ├── ShopBranding.php       # Shop branding helper
│   └── ...
├── Http/
│   ├── Controllers/
│   │   └── ThemeController.php
│   └── Middleware/
│       └── SetThemeMode.php
└── ...

resources/js/
├── Support/
│   ├── ThemeProvider.jsx      # React theme context
│   └── BrandingProvider.jsx   # React branding hooks
└── ...

public/images/
├── logo.png                   # Admin logo
├── banner.png                 # Admin banner
├── watermark.png              # Admin watermark
└── (optional) shop-default-*.png  # If you want separate shop defaults, add these and update Theme::getShopDefaults()
```

---

## Best Practices

1. **Always provide fallbacks**: Use defaults when custom branding is not available
2. **Conditional rendering**: Check `mode` in hooks before applying styles
3. **Responsive design**: Test both dark and light modes
4. **Accessibility**: Ensure sufficient contrast in both modes
5. **Admin settings**: Consider adding UI to manage shop branding in admin panel
6. **Mobile support**: Test theme toggle on mobile devices

---

## Examples

### Complete Welcome Page with Theme Support
See: `resources/js/Pages/Welcome.jsx`

### Using Theme in Admin Pages
```jsx
import { useTheme } from '@/Support/ThemeProvider';

export default function AdminDashboard() {
    const { mode } = useTheme();
    const isDark = mode === 'dark';
    
    return (
        <div className={isDark ? 'bg-slate-950' : 'bg-white'}>
            <h1 className={isDark ? 'text-white' : 'text-slate-900'}>
                Dashboard
            </h1>
        </div>
    );
}
```

### Using Shop Branding in POS
```jsx
import { ShopLogo, useShopBranding } from '@/Support/BrandingProvider';

export default function PosHeader() {
    const branding = useShopBranding();
    
    return (
        <header className="flex items-center gap-4">
            <img src={branding.logo} alt="Shop" className="w-10 h-10" />
            <h1>Point of Sale</h1>
        </header>
    );
}
```

---

## Troubleshooting

### Theme not toggling?
- Ensure `ThemeProvider` wraps the app in `app.jsx`
- Check that `SetThemeMode` middleware is registered in `bootstrap/app.php`

### Shop branding not showing?
- Verify database columns exist for `logo_path`, `banner_path`, `watermark_path`
- Check that image files exist in `public/images/`
- Use `ShopBranding::toArray()` for proper asset URLs

### Colors not applying?
- Clear Tailwind CSS cache: `npm run build`
- Verify color values in `tailwind.config.js`
- Check for conflicting CSS rules

---

## Future Enhancements

- [ ] Custom theme builder in admin panel
- [ ] Per-module color overrides
- [ ] Store theme preference in database (per user/shop)
- [ ] Color accessibility checker
- [ ] Theme import/export functionality
