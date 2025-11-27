// src/lib/types.ts
import React from 'react';

// --- Types Dasar ---
export type UserRole = "admin" | "design" | "production" | "management"
export type OrderStatus = "pending" | "confirmed" | "design" | "production" | "completed" | "cancelled"
export type PaymentStatus = "pending" | "verified" | "failed"
export type DesignStatus = "queue" | "in_progress" | "review" | "approved" | "rejected"
export type ProductionStatus = "queue" | "printing" | "cutting" | "finishing" | "packaging"

// --- Interfaces ---
export interface User {
  id: string
  name: string
  email: string
  password: string // Harusnya hash
  role: UserRole
  isActive: boolean
  createdAt: string
}

export interface Order {
  id: string
  customerName: string
  serviceType: string
  quantity: number
  price: number
  totalCost: number
  orderStatus: OrderStatus
  paymentStatus: PaymentStatus
  designStatus: DesignStatus
  productionStatus: ProductionStatus
  dueDate: string
  assignedUserId: string
  createdAt: string
  updatedAt: string
}

export interface NavigationItem {
  id: string
  label: string
  icon: React.ElementType
  roles: UserRole[]
}