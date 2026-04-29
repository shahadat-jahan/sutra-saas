import React, { createContext, useContext, useState, useEffect } from 'react';
import { Moon, Sun } from 'lucide-react';
import { usePage } from '@inertiajs/react';

const ThemeContext = createContext();

export function ThemeProvider({ children, initialMode = 'dark' }) {
    const [mode, setMode] = useState(initialMode);

    useEffect(() => {
        // Update HTML class and localStorage
        if (mode === 'dark') {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light');
        } else {
            document.documentElement.classList.add('light');
            document.documentElement.classList.remove('dark');
        }
        localStorage.setItem('themeMode', mode);
    }, [mode]);

    const toggleTheme = () => {
        setMode(prev => prev === 'dark' ? 'light' : 'dark');
    };

    return (
        <ThemeContext.Provider value={{ mode, toggleTheme }}>
            {children}
        </ThemeContext.Provider>
    );
}

export function useTheme() {
    const context = useContext(ThemeContext);
    if (!context) {
        throw new Error('useTheme must be used within ThemeProvider');
    }
    return context;
}

export function ThemeToggle() {
    const { mode, toggleTheme } = useTheme();

    const handleClick = async () => {
        toggleTheme();

        // Also update server session
        try {
            await fetch(route('theme.toggle'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });
        } catch (error) {
            console.error('Failed to toggle theme:', error);
        }
    };

    return (
        <button
            onClick={handleClick}
            className={`p-2 rounded-lg transition-all ${
                mode === 'dark'
                    ? 'bg-white/10 text-yellow-400 hover:bg-white/20'
                    : 'bg-slate-200 text-slate-700 hover:bg-slate-300'
            }`}
            title={mode === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode'}
            aria-label="Toggle theme"
        >
            {mode === 'dark' ? (
                <Sun className="w-5 h-5" />
            ) : (
                <Moon className="w-5 h-5" />
            )}
        </button>
    );
}

