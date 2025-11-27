// src/hooks/useAuthController.ts
import { useState, useMemo } from "react";
import { User } from "@/lib/types";
import { mockUsers, navigationItems } from "@/lib/data";

export const useAuthController = () => {
    const [currentUser, setCurrentUser] = useState<User | null>(null);
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [currentView, setCurrentView] = useState("dashboard");

    const handleLogin = (email: string, pass: string): boolean => {
        const user = mockUsers.find(u => u.email === email && u.password === pass);
        if (user) {
            setCurrentUser(user);
            setIsLoggedIn(true);
            setCurrentView("dashboard");
            return true;
        }
        return false;
    };

    const handleLogout = () => {
        setCurrentUser(null);
        setIsLoggedIn(false);
    };

    const filteredNavigation = useMemo(() => {
        if (!currentUser) return [];
        return navigationItems.filter(item => item.roles.includes(currentUser.role));
    }, [currentUser]);

    return {
        currentUser,
        isLoggedIn,
        currentView,
        filteredNavigation,
        handleLogin,
        handleLogout,
        setCurrentView
    };
};