// src/features/design/DesignDashboard.tsx
import { Separator } from "@/components/ui/separator";
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import { Palette, CheckCircle, Clock } from "lucide-react";
import { Order, User } from "@/lib/types";

interface DesignDashboardProps {
    orders: Order[];
    users: User[];
}

// Logika khusus untuk Dashboard Desain
const getDesignStats = (orders: Order[]) => {
    return {
        totalDesign: orders.filter(o => o.orderStatus === 'design' || o.designStatus === 'in_progress' || o.designStatus === 'review').length,
        pendingApproval: orders.filter(o => o.designStatus === 'review').length,
        inProgress: orders.filter(o => o.designStatus === 'in_progress').length,
    }
}

export const DesignDashboard: React.FC<DesignDashboardProps> = ({ orders, users }) => {
    const stats = getDesignStats(orders);

    return (
        <div className="space-y-6">
            {/* JUDUL DAN DESKRIPSI BARU */}
            <h2 className="text-3xl font-bold tracking-tight">Dashboard Tim Desain 🎨</h2> 
            <p className="text-muted-foreground">Kelola proses desain dan *approval* untuk semua pesanan.</p>
            
            <Separator />

            {/* Area untuk Statistik Desain */}
            <div className="grid gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle className="text-sm font-medium">Desain Dalam Proses</CardTitle>
                        <Palette className="h-4 w-4 text-indigo-500" />
                    </CardHeader>
                    <CardContent>
                        <div className="text-2xl font-bold">{stats.totalDesign} Pesanan</div>
                        <p className="text-xs text-muted-foreground">Total yang sedang ditangani</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle className="text-sm font-medium">Menunggu Approval</CardTitle>
                        <Clock className="h-4 w-4 text-yellow-600" />
                    </CardHeader>
                    <CardContent>
                        <div className="text-2xl font-bold">{stats.pendingApproval} Pesanan</div>
                        <p className="text-xs text-muted-foreground">Perlu persetujuan pelanggan</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle className="text-sm font-medium">Desain Selesai (Bulan Ini)</CardTitle>
                        <CheckCircle className="h-4 w-4 text-green-500" />
                    </CardHeader>
                    <CardContent>
                        <div className="text-2xl font-bold">8 Proyek</div>
                        <p className="text-xs text-muted-foreground">Siap dipindahkan ke Produksi</p>
                    </CardContent>
                </Card>
            </div>
        </div>
    );
}