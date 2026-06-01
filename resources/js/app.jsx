import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';
// import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'; // Removed this import
import { createRoot } from 'react-dom/client';
import { ThemeProvider } from '@/Support/ThemeProvider';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });
        return pages[`./Pages/${name}.jsx`];
    },
    setup({ el, App, props }) {
        const root = createRoot(el);
        const theme = props.initialPage.props.themeMode || 'dark';

        root.render(
            <ThemeProvider initialMode={theme}>
                <App {...props} />
            </ThemeProvider>
        );
    },
    progress: {
        color: '#6366f1',
    },
});
