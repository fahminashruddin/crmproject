// src/app/page.tsx
"use client"

import { useState } from "react"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Badge } from "@/components/ui/badge"
import { Alert, AlertDescription } from "@/components/ui/alert"
import { Menu, X, LogOut, Users, Eye, EyeOff } from "lucide-react"

// --- Import dari struktur MVC baru ---
import { useAuthController } from "@/hooks/useAuthController" 
import { mockUsers, mockOrders, getRoleColor } from "@/lib/data" // Model: Data Mock
import { AdminDashboard } from "@/features/admin/AdminDashboard" // View: Dashboard Admin
import { DesignDashboard } from "@/features/design/DesignDashboard" // View: Dashboard Desain BARU

// --- Komponen View Lain (Placeholder) ---
// Sebaiknya dipindahkan ke file komponen terpisah
const renderOrderManagement = () => { return <p>Manajemen Pesanan...</p>}
const renderPaymentManagement = () => { return <p>Manajemen Pembayaran...</p>}
const renderUserManagement = () => { return <p>Manajemen Pengguna...</p>}
const renderDesignManagement = () => { return <p>Manajemen Desain...</p>}
const renderProductionView = (view: string) => { return <p>Produksi View for {view}</p>}
const renderManagementView = (view: string) => { return <p>Manajemen View for {view}</p>}


const LoginPage: React.FC<{ onLogin: (email: string, pass: string) => boolean }> = ({ onLogin }) => {
    // [Kode LoginPage yang sama dari balasan sebelumnya]
    const [email, setEmail] = useState("admin@percetakan.com") // Demo default
    const [password, setPassword] = useState("admin123") // Demo default
    const [showPassword, setShowPassword] = useState(false)

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault()
        onLogin(email, password)
    }
    
    return (
        <div className="flex min-h-screen items-center justify-center bg-gray-100">
            <Card className="w-full max-w-md">
                <CardHeader>
                    <CardTitle className="text-2xl">PrintyHub CRM Login</CardTitle>
                    <CardDescription>
                        Masukkan kredensial Anda untuk mengakses sistem.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div className="space-y-2">
                            <Label htmlFor="email">Email</Label>
                            <Input id="email" type="email" placeholder="email@percetakan.com" required value={email} onChange={(e) => setEmail(e.target.value)} />
                        </div>
                        <div className="space-y-2">
                            <div className="flex items-center">
                                <Label htmlFor="password">Password</Label>
                            </div>
                            <div className="relative">
                                <Input id="password" type={showPassword ? "text" : "password"} required value={password} onChange={(e) => setPassword(e.target.value)} />
                                <Button type="button" variant="ghost" size="sm" className="absolute right-0 top-0 h-full px-3" onClick={() => setShowPassword(!showPassword)}>
                                    {showPassword ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                                </Button>
                            </div>
                        </div>
                        <Button type="submit" className="w-full">
                            Login
                        </Button>
                    </form>
                    <div className="mt-6 space-y-2 text-sm text-center">
                        <p className="font-semibold">Demo Credentials:</p>
                        <Alert>
                            <AlertDescription className="text-xs">
                                Admin: <b>admin@percetakan.com</b> / admin123 | Design: <b>design@percetakan.com</b> / design123 | Production: <b>production@percetakan.com</b> / production123 | Manager: <b>manager@percetakan.com</b> / manager123
                            </AlertDescription>
                        </Alert>
                    </div >
                </CardContent >
            </Card >
        </div >
    )
}


const MainAppLayout = () => {
    const { 
        currentUser, 
        currentView, 
        filteredNavigation, 
        handleLogout, 
        setCurrentView 
    } = useAuthController();
    
    const [sidebarOpen, setSidebarOpen] = useState(false)

    // View Selector: Menentukan View mana yang akan dirender berdasarkan peran dan currentView
    const renderCurrentView = () => {
        if (!currentUser) return null;

        switch (currentUser.role) {
            case "admin":
                return renderAdminView(currentView);
            case "design":
                return renderDesignView(currentView); // <-- Memanggil fungsi render Design View
            case "production":
                return renderProductionView(currentView);
            case "management":
                return renderManagementView(currentView);
            default:
                return <p className="text-xl font-semibold mt-4">Selamat datang di CRM Percetakan!</p>
        }
    }

    // View Selector untuk Admin
    const renderAdminView = (view: string) => {
        switch (view) {
            case "dashboard":
                return <AdminDashboard orders={mockOrders} users={mockUsers} />
            case "orderManagement":
                return renderOrderManagement();
            case "paymentManagement":
                return renderPaymentManagement();
            case "userManagement":
                return renderUserManagement();
            default:
                return <p>Pilih tampilan</p>
        }
    }

    // View Selector untuk Design (BARU)
    const renderDesignView = (view: string) => {
        switch (view) {
            case "dashboard":
                // MENGGUNAKAN KOMPONEN DASHBOARD DESAIN BARU DENGAN JUDUL BARU
                return <DesignDashboard orders={mockOrders} users={mockUsers} />;
            case "designManagement":
                return renderDesignManagement();
            default:
                return <p>Pilih tampilan</p>
        }
    }


    return (
        <div className="flex h-screen w-full flex-col">
            {/* Header / Navbar */}
            <header className="sticky top-0 z-50 flex h-16 items-center justify-between border-b bg-background px-4 md:px-6">
                <div className="flex items-center space-x-4">
                    <Button variant="ghost" size="icon" className="md:hidden" onClick={() => setSidebarOpen(true)}>
                        <Menu className="h-6 w-6" />
                        <span className="sr-only">Toggle Menu</span>
                    </Button>
                    <h1 className="text-xl font-bold">PrintyHub CRM</h1>
                </div>
                <div className="flex items-center space-x-4">
                    {currentUser && (
                        <Badge className={`px-3 py-1 ${getRoleColor(currentUser.role)}`}>
                            {currentUser.role.charAt(0).toUpperCase() + currentUser.role.slice(1)}
                        </Badge>
                    )}
                    <Button variant="outline" size="icon" className="rounded-full">
                        <Users className="h-5 w-5" />
                    </Button>
                    <Button variant="ghost" size="icon" onClick={handleLogout}>
                        <LogOut className="h-5 w-5" />
                        <span className="sr-only">Logout</span>
                    </Button>
                </div>
            </header>

            {/* Main Layout */}
            <div className="flex flex-1 overflow-hidden">
                {/* Sidebar */}
                <aside
                    className={`transform transition-transform duration-200 ease-in-out md:static md:w-64 ${sidebarOpen ? "translate-x-0" : "-translate-x-full"} fixed inset-y-0 left-0 z-50 w-64 border-r bg-background md:translate-x-0`}
                >
                    <div className="flex h-full flex-col">
                        <div className="flex-1 overflow-auto py-4">
                            <nav className="space-y-2 px-3">
                                {filteredNavigation.map((item) => {
                                    const Icon = item.icon
                                    return (
                                        <Button
                                            key={item.id}
                                            variant={currentView === item.id ? "default" : "ghost"}
                                            className="w-full justify-start"
                                            onClick={() => {
                                                setCurrentView(item.id)
                                                setSidebarOpen(false)
                                            }}
                                        >
                                            <Icon className="mr-2 h-4 w-4" />
                                            {item.label}
                                        </Button>
                                    )
                                })}
                            </nav>
                        </div>
                    </div>
                </aside>

                {/* Overlay for mobile */}
                {sidebarOpen && (
                    <div
                        className="fixed inset-0 z-40 bg-background/80 backdrop-blur-sm md:hidden"
                        onClick={() => setSidebarOpen(false)}
                    />
                )}

                {/* Main Content (FULL-WIDTH MODIFICATION) */}
                <main className="flex-1 overflow-auto bg-gray-50">
                    {/* HAPUS 'container mx-auto' untuk Full-Width, Tinggalkan padding p-8 */}
                    <div className="p-8">{renderCurrentView()}</div> 
                </main>
            </div>
        </div>
    )
}

// --- Komponen Root Page ---
export default function Page() {
    const { isLoggedIn, handleLogin } = useAuthController();
    return isLoggedIn ? <MainAppLayout /> : <LoginPage onLogin={handleLogin} />
}