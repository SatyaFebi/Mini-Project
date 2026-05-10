'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';
import api from './api';
import axios from 'axios';

interface User {
    id: number;
    name: string;
    email: string;
}

interface AuthContextType {
    user: User | null;
    isAuthenticated: boolean;
    isLoading: boolean;
    login: (credentials: any) => Promise<void>;
    register: (data: any) => Promise<void>;
    logout: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: React.ReactNode }) {
    const [user, setUser] = useState<User | null>(null);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        api.get('/me')
            .then((res) => {
                setUser(res.data);
            })
            .catch(() => {
                setUser(null);
            })
            .finally(() => {
                setIsLoading(false);
            });
    }, []);

    const login = async (credentials: any) => {
        await axios.get(`${process.env.NEXT_PUBLIC_BASE_URL}/sanctum/csrf-cookie`, { withCredentials: true });
        const res = await api.post('/login', credentials);
        setUser(res.data.data.user);
    };

    const register = async (data: any) => {
        await axios.get(`${process.env.NEXT_PUBLIC_BASE_URL}/sanctum/csrf-cookie`, { withCredentials: true });
        const res = await api.post('/register', data);
        setUser(res.data.data.user);
    };

    const logout = async () => {
        try {
            await api.post('/logout');
        } finally {
            if (typeof window !== 'undefined') {
                setUser(null);
                window.location.href = '/auth/login';
            }
        }
    };

    return (
        <AuthContext.Provider value={{ user, isAuthenticated: !!user, isLoading, login, register, logout }}>
            {children}
        </AuthContext.Provider>
    );
}

export function useAuth() {
    const context = useContext(AuthContext);
    if (context === undefined) {
        throw new Error('useAuth must be used within an AuthProvider');
    }
    return context;
}
