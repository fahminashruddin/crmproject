// src/lib/data.ts
import { User, Order, NavigationItem } from "./types"
import { LayoutDashboard, ShoppingCart, CreditCard, Palette, Factory, Users, BarChart3, Settings } from "lucide-react"

// --- Mock Data: Users ---
export const mockUsers: User[] = [
    { id: "u1", name: "Admin Utama", email: "admin@percetakan.com", password: "admin123", role: "admin", isActive: true, createdAt: "2023-01-01" },
    { id: "u2", name: "Rina Desainer", email: "design@percetakan.com", password: "design123", role: "design", isActive: true, createdAt: "2023-01-05" },
    { id: "u3", name: "Budi Produksi", email: "production@percetakan.com", password: "production123", role: "production", isActive: true, createdAt: "2023-01-10" },
    { id: "u4", name: "Sari Manajer", email: "manager@percetakan.com", password: "manager123", role: "management", isActive: true, createdAt: "2023-01-15" },
]

// --- Mock Data: Orders ---
export const mockOrders: Order[] = [
    { id: "O1001", customerName: "PT Sentosa Jaya", serviceType: "Banner", quantity: 5, price: 500000, totalCost: 2500000, orderStatus: "completed", paymentStatus: "verified", designStatus: "approved", productionStatus: "packaging", dueDate: "2025-12-30", assignedUserId: "u3", createdAt: "2025-11-20" },
    { id: "O1002", customerName: "Kopi Nusantara", serviceType: "Sticker", quantity: 1000, price: 500, totalCost: 500000, orderStatus: "production", paymentStatus: "verified", designStatus: "approved", productionStatus: "printing", dueDate: "2025-12-15", assignedUserId: "u3", createdAt: "2025-11-25" },
    { id: "O1003", customerName: "Universitas XYZ", serviceType: "Brosur", quantity: 500, price: 2000, totalCost: 1000000, orderStatus: "design", paymentStatus: "pending", designStatus: "in_progress", productionStatus: "queue", dueDate: "2025-12-28", assignedUserId: "u2", createdAt: "2025-11-26" },
    { id: "O1004", customerName: "Toko Baju Fesyen", serviceType: "Hang Tag", quantity: 2000, price: 200, totalCost: 400000, orderStatus: "pending", paymentStatus: "pending", designStatus: "queue", productionStatus: "queue", dueDate: "2025-12-18", assignedUserId: "u1", createdAt: "2025-11-27" },
    { id: "O1005", customerName: "Raja Komputer", serviceType: "Kartu Nama", quantity: 10, price: 15000, totalCost: 150000, orderStatus: "design", paymentStatus: "verified", designStatus: "review", productionStatus: "queue", dueDate: "2025-12-10", assignedUserId: "u2", createdAt: "2025-11-28" },
]

// --- Helper Data: Navigation Items ---
export const navigationItems: NavigationItem[] = [
    // Admin
    { id: "dashboard", label: "Dashboard", icon: LayoutDashboard, roles: ["admin", "design", "production", "management"] },
    { id: "orderManagement", label: "Manajemen Pesanan", icon: ShoppingCart, roles: ["admin", "management"] },
    { id: "paymentManagement", label: "Manajemen Pembayaran", icon: CreditCard, roles: ["admin", "management"] },
    { id: "userManagement", label: "Manajemen Pengguna", icon: Users, roles: ["admin"] },
    // Design
    { id: "designManagement", label: "Desain & Approval", icon: Palette, roles: ["design"] },
    // Production
    { id: "productionManagement", label: "Manajemen Produksi", icon: Factory, roles: ["production"] },
    // Management
    { id: "reports", label: "Laporan & Analitik", icon: BarChart3, roles: ["management"] },
    { id: "settings", label: "Pengaturan", icon: Settings, roles: ["admin", "management"] },
]

// --- Helper Function: Role Color ---
export const getRoleColor = (role: string) => {
    switch (role) {
        case "admin": return "bg-red-500 hover:bg-red-600";
        case "design": return "bg-indigo-500 hover:bg-indigo-600";
        case "production": return "bg-yellow-600 hover:bg-yellow-700";
        case "management": return "bg-green-600 hover:bg-green-700";
        default: return "bg-gray-500 hover:bg-gray-600";
    }
}