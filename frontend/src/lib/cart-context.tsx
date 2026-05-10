'use client';

import React, { createContext, useContext, useState, useCallback, useMemo } from 'react';
import { Barang } from './types';

export interface CartItem {
  kode_barang: string;
  nama: string;
  harga: number;
  qty: number;
}

interface CartContextType {
  items: CartItem[];
  addItem: (barang: Barang) => void;
  removeItem: (kode: string) => void;
  updateQty: (kode: string, qty: number) => void;
  clearCart: () => void;
  totalItems: number;
  totalPrice: number;
}

const CartContext = createContext<CartContextType | undefined>(undefined);

export function CartProvider({ children }: { children: React.ReactNode }) {
  const [items, setItems] = useState<CartItem[]>([]);

  const addItem = useCallback((barang: Barang) => {
    setItems(prev => {
      const existing = prev.find(i => i.kode_barang === barang.kode);
      if (existing) {
        return prev.map(i =>
          i.kode_barang === barang.kode ? { ...i, qty: i.qty + 1 } : i
        );
      }
      return [...prev, { kode_barang: barang.kode, nama: barang.nama, harga: barang.harga, qty: 1 }];
    });
  }, []);

  const removeItem = useCallback((kode: string) => {
    setItems(prev => prev.filter(i => i.kode_barang !== kode));
  }, []);

  const updateQty = useCallback((kode: string, qty: number) => {
    if (qty <= 0) {
      setItems(prev => prev.filter(i => i.kode_barang !== kode));
      return;
    }
    setItems(prev => prev.map(i => i.kode_barang === kode ? { ...i, qty } : i));
  }, []);

  const clearCart = useCallback(() => setItems([]), []);

  const totalItems = useMemo(() => items.reduce((sum, i) => sum + i.qty, 0), [items]);
  const totalPrice = useMemo(() => items.reduce((sum, i) => sum + i.harga * i.qty, 0), [items]);

  return (
    <CartContext.Provider value={{ items, addItem, removeItem, updateQty, clearCart, totalItems, totalPrice }}>
      {children}
    </CartContext.Provider>
  );
}

export function useCart() {
  const ctx = useContext(CartContext);
  if (!ctx) throw new Error('useCart must be used within CartProvider');
  return ctx;
}
