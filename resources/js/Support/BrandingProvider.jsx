import React, { createContext, useContext } from 'react';
import { usePage } from '@inertiajs/react';

const BrandingContext = createContext();

export function BrandingProvider({ children, branding }) {
    return (
        <BrandingContext.Provider value={branding}>
            {children}
        </BrandingContext.Provider>
    );
}

export function useShopBranding() {
    const context = useContext(BrandingContext);
    if (!context) {
        // Fallback to page props if context not available
        const { props } = usePage();
        return {
            logo: '/images/logo.png',
            banner: '/images/banner.png',
            watermark: '/images/watermark.png',
            ...props.shopBranding,
        };
    }
    return context;
}

/**
 * Hook to get admin branding
 */
export function useAdminBranding() {
    const { adminBranding } = usePage().props;
    return adminBranding || {
        logo: '/images/logo.png',
        banner: '/images/banner.png',
        watermark: '/images/watermark.png',
        favicon: '/favicon.ico',
    };
}

/**
 * Component to display shop logo
 */
export function ShopLogo({ className = '', alt = 'Shop Logo' }) {
    const branding = useShopBranding();
    return <img src={branding.logo} alt={alt} className={className} />;
}

/**
 * Component to display shop banner
 */
export function ShopBanner({ className = '', alt = 'Shop Banner' }) {
    const branding = useShopBranding();
    return <img src={branding.banner} alt={alt} className={className} />;
}

/**
 * Component to display watermark background
 */
export function ShopWatermark({ children, className = '' }) {
    const branding = useShopBranding();
    return (
        <div
            className={className}
            style={{
                backgroundImage: `url(${branding.watermark})`,
                backgroundRepeat: 'no-repeat',
                backgroundPosition: 'center',
                backgroundSize: 'contain',
                backgroundAttachment: 'fixed',
                opacity: 0.05,
            }}
        >
            {children}
        </div>
    );
}
