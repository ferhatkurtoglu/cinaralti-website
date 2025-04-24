'use client';

import { createContext, useContext, useState, useEffect, ReactNode } from 'react';

// Sidebar durumu için tip tanımlaması
type SidebarContextType = {
  isOpen: boolean;
  toggle: (value?: boolean) => void;
};

// Varsayılan değerler
const defaultContext: SidebarContextType = {
  isOpen: true,
  toggle: () => {},
};

// Context oluşturma
const SidebarContext = createContext<SidebarContextType>(defaultContext);

// Provider bileşeni
export function SidebarProvider({ children }: { children: ReactNode }) {
  const [isOpen, setIsOpen] = useState(true);

  // Ekran boyutuna göre ilk açılışta sidebar durumunu ayarla
  useEffect(() => {
    const checkScreenSize = () => {
      if (typeof window !== 'undefined') {
        setIsOpen(window.innerWidth >= 768);
      }
    };

    // İlk yüklemede çalıştır
    checkScreenSize();

    // Ekran boyutu değiştiğinde
    window.addEventListener('resize', checkScreenSize);
    
    return () => {
      window.removeEventListener('resize', checkScreenSize);
    };
  }, []);

  // Toggle fonksiyonu - parametre verilmezse mevcut durumu tersine çevirir
  const toggle = (value?: boolean) => {
    setIsOpen(value !== undefined ? value : !isOpen);
  };

  return (
    <SidebarContext.Provider value={{ isOpen, toggle }}>
      {children}
    </SidebarContext.Provider>
  );
}

// Custom hook
export function useSidebarState() {
  const context = useContext(SidebarContext);
  if (context === undefined) {
    throw new Error('useSidebarState hook must be used within a SidebarProvider');
  }
  return context;
} 