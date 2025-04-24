'use client';

import Sidebar from '@/components/Sidebar';
import Topbar from '@/components/Topbar';
import { NotificationProvider } from '@/lib/notificationContext';
import { SidebarProvider, useSidebarState } from '@/lib/sidebarState';
import { useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';

// İçerik bileşeni - sidebar durumunu kullanır
function DashboardContent({ children }: { children: React.ReactNode }) {
  const router = useRouter();
  const [isAuthorized, setIsAuthorized] = useState(false);
  const [isLoading, setIsLoading] = useState(true);
  const { isOpen } = useSidebarState();
  
  useEffect(() => {
    // Token kontrolü - hem localStorage hem de sessionStorage kontrol edilir
    const getToken = () => {
      return localStorage.getItem('adminToken') || sessionStorage.getItem('adminToken');
    };
    
    const getUser = () => {
      return localStorage.getItem('adminUser') || sessionStorage.getItem('adminUser');
    };
    
    const token = getToken();
    const user = getUser();
    
    console.log('Dashboard layout - token kontrolü:', !!token);
    
    if (!token || !user) {
      // Token veya kullanıcı yoksa login sayfasına yönlendir
      console.log('Token veya kullanıcı bulunamadı, giriş sayfasına yönlendiriliyor');
      window.location.href = '/'; // router.push yerine
      return;
    }
    
    // Client-side token doğrulaması
    try {
      // Token'i decode et - burada tam bir JWT doğrulaması yapamıyoruz, 
      // ama en azından geçerli bir JWT formatında olup olmadığını ve süresinin dolup dolmadığını kontrol edebiliriz
      const tokenParts = token.split('.');
      if (tokenParts.length !== 3) {
        throw new Error('Geçersiz token formatı');
      }
      
      // Token payload'ı JSON olarak decode et
      const payload = JSON.parse(atob(tokenParts[1].replace(/-/g, '+').replace(/_/g, '/')));
      console.log('Token payload:', payload);
      
      // Süre kontrolü
      const now = Math.floor(Date.now() / 1000);
      if (payload.exp && payload.exp < now) {
        throw new Error('Token süresi dolmuş');
      }
      
      // Token doğrulaması başarılı
      console.log('Client-side token kontrolü başarılı, dashboard erişimi veriliyor');
      setIsAuthorized(true);
      setIsLoading(false);
    } catch (error) {
      console.error('Token doğrulama hatası:', error);
      // Token geçersiz, giriş sayfasına yönlendir
      window.location.href = '/';
    }
  }, []);
  
  if (isLoading) {
    return (
      <div className="flex h-screen w-screen items-center justify-center">
        <div className="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-primary"></div>
      </div>
    );
  }
  
  if (!isAuthorized) {
    return null; // Router yönlendirmesi gerçekleşirken geçici olarak hiçbir şey gösterme
  }
  
  return (
    <div className="flex h-screen overflow-hidden">
      <Sidebar />
      <div className="flex-1 flex flex-col overflow-hidden bg-gray-100">
        <Topbar />
        <main className="p-4 md:p-6 overflow-y-auto h-[calc(100vh-4rem)]">
          {children}
        </main>
      </div>
    </div>
  );
}

// Ana layout bileşeni - SidebarProvider ve NotificationProvider ile sarmalıyor
export default function DashboardLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <SidebarProvider>
      <NotificationProvider>
        <DashboardContent>
          {children}
        </DashboardContent>
      </NotificationProvider>
    </SidebarProvider>
  );
} 