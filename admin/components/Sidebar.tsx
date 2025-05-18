'use client';

import Image from 'next/image';
import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';
import {
    FaBell,
    FaChartPie,
    FaCog,
    FaHome,
    FaList,
    FaSignOutAlt,
    FaTags,
    FaUsers
} from 'react-icons/fa';

// Sidebar genişliği için global durum
import { useSidebarState } from '@/lib/sidebarState';
// Bildirim context'ini kullan
import { useNotifications } from '@/lib/notificationContext';

export default function Sidebar() {
  const pathname = usePathname();
  const router = useRouter();
  const [showLogoutModal, setShowLogoutModal] = useState(false);
  const { isOpen, toggle } = useSidebarState();
  const [isMobile, setIsMobile] = useState(false);
  
  // Bildirim sayısını al
  const { unreadCount } = useNotifications();

  // Ekran boyutunu izlemek için
  useEffect(() => {
    const handleResize = () => {
      const mobile = typeof window !== 'undefined' && window.innerWidth < 768;
      setIsMobile(mobile);
      
      if (typeof window !== 'undefined' && window.innerWidth >= 768 && !isOpen) {
        toggle(true); // Orta ve büyük ekranlarda kenar çubuğunu otomatik olarak aç
      }
    };

    // Başlangıçta çalıştır
    handleResize();

    // Ekran boyutu değiştiğinde çalıştır
    if (typeof window !== 'undefined') {
      window.addEventListener('resize', handleResize);
      return () => window.removeEventListener('resize', handleResize);
    }
  }, [isOpen, toggle]);

  const menuItems = [
    { icon: <FaHome className="sidebar-icon" />, label: 'Anasayfa', href: '/dashboard' },
    { icon: <FaChartPie className="sidebar-icon" />, label: 'Bağış İstatistikleri', href: '/dashboard/statistics' },
    { icon: <FaList className="sidebar-icon" />, label: 'Bağışlar', href: '/dashboard/donations' },
    { icon: <FaTags className="sidebar-icon" />, label: 'Bağış Seçenekleri', href: '/dashboard/donation-types' },
    { icon: <FaUsers className="sidebar-icon" />, label: 'Kullanıcılar', href: '/dashboard/users' },
    { icon: <FaBell className="sidebar-icon" />, label: 'Bildirimler', href: '/dashboard/notifications' },
    { icon: <FaCog className="sidebar-icon" />, label: 'Ayarlar', href: '/dashboard/settings' },
    { icon: <FaSignOutAlt className="sidebar-icon" />, label: 'Çıkış Yap', href: '#', isLogout: true },
  ];

  const handleLogout = () => {
    setShowLogoutModal(true);
  };

  const confirmLogout = async () => {
    try {
      console.log('Çıkış yapılıyor...');
      // API ile çıkış yap (cookie temizlenir)
      await fetch('/api/auth/logout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        }
      });
      
      // localStorage ve sessionStorage'dan kullanıcı bilgilerini temizle
      localStorage.removeItem('adminToken');
      localStorage.removeItem('adminUser');
      sessionStorage.removeItem('adminToken');
      sessionStorage.removeItem('adminUser');
      
      // Cookie'den token'ı temizle (ilave önlem olarak client tarafında da)
      document.cookie = 'adminToken=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=Strict';
      
      console.log('Tüm oturum verileri temizlendi, giriş sayfasına yönlendiriliyor');
      
      // Modal'ı kapat
      setShowLogoutModal(false);
      
      // Ana sayfaya yönlendir - router.push yerine doğrudan location değiştiriyoruz
      window.location.href = '/';
    } catch (error) {
      console.error('Çıkış yapılırken hata oluştu:', error);
      
      // Hata olsa bile temizleme işlemlerini yap ve yönlendir
      localStorage.removeItem('adminToken');
      localStorage.removeItem('adminUser');
      sessionStorage.removeItem('adminToken');
      sessionStorage.removeItem('adminUser');
      document.cookie = 'adminToken=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT; SameSite=Strict';
      
      // Modal'ı kapat
      setShowLogoutModal(false);
      
      window.location.href = '/';
    }
  };

  const cancelLogout = () => {
    setShowLogoutModal(false);
  };

  // Mobil cihazlarda kenar çubuğu açıkken tıklamayla kapatma
  const handleOverlayClick = () => {
    toggle(false);
  };

  // Menü öğesinin tipini tanımla
  interface MenuItem {
    icon: React.ReactNode;
    label: string;
    href: string;
    isLogout?: boolean;
  }

  // Özel bildirim menü öğesi oluşturma fonksiyonu
  const renderMenuItem = (item: MenuItem, index: number) => {
    // Çıkış yap butonu ise farklı davranış göster
    if (item.isLogout) {
      return (
        <button 
          key={index}
          onClick={handleLogout}
          className="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200 w-full text-left pointer-events-auto"
        >
          <span className="mr-3 text-lg min-w-[1.25rem]">{item.icon}</span>
          <span>{item.label}</span>
        </button>
      );
    }
    
    // Bildirimlerin özel gösterimi
    if (item.href === '/dashboard/notifications') {
      return (
        <Link 
          key={index} 
          href={item.href}
          className={`flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200 pointer-events-auto ${pathname === item.href ? 'bg-primary text-white' : ''} relative`}
          onClick={() => isMobile && toggle(false)}
        >
          <span className="mr-3 text-lg min-w-[1.25rem]">{item.icon}</span>
          <span>{item.label}</span>
          {unreadCount > 0 && (
            <span className="absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center w-5 h-5 text-xs bg-primary text-white rounded-full">
              {unreadCount > 9 ? '9+' : unreadCount}
            </span>
          )}
        </Link>
      );
    }
    
    // Diğer menü öğeleri için normal gösterim
    return (
      <Link 
        key={index} 
        href={item.href}
        className={`flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200 pointer-events-auto ${pathname === item.href ? 'bg-primary text-white' : ''}`}
        onClick={() => isMobile && toggle(false)}
      >
        <span className="mr-3 text-lg min-w-[1.25rem]">{item.icon}</span>
        <span>{item.label}</span>
      </Link>
    );
  };

  return (
    <>
      {/* Mobil cihazlarda kenar çubuğu açıkken arka plan overlay'i */}
      {isOpen && isMobile && (
        <div 
          className="sidebar-overlay" 
          onClick={handleOverlayClick}
          aria-hidden="true"
        ></div>
      )}
      
      <aside className={`w-64 h-screen bg-dark text-white shadow-lg shrink-0 z-50 ${isOpen ? 'block' : 'hidden md:block'}`}>
        <div className="flex items-center justify-between h-20 px-4 border-b border-gray-700">
          <Link href="/dashboard" className="flex items-center pointer-events-auto">
            <Image 
              src="/assets/images/logo-dark.png" 
              alt="Çınaraltı Logo" 
              width={300} 
              height={80} 
              className="max-h-20 w-auto"
            />
          </Link>
          
          {/* Mobil cihazlarda görünecek kapatma butonu */}
          <button
            className="text-white md:hidden pointer-events-auto"
            onClick={() => toggle(false)}
            aria-label="Menüyü Kapat"
          >
            <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <nav className="flex flex-col mt-2 overflow-y-auto h-[calc(100vh-5rem)]">
          {menuItems.map((item, index) => renderMenuItem(item, index))}
        </nav>

        {/* Çıkış Onay Modalı */}
        {showLogoutModal && (
          <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div className="bg-white rounded-lg shadow-xl w-full max-w-sm">
              <div className="p-6">
                <h3 className="text-lg font-medium text-gray-900 mb-4">
                  Çıkış yapmak istiyor musunuz?
                </h3>
                <p className="text-sm text-gray-500 mb-4">
                  Oturumunuz sonlandırılacak ve giriş sayfasına yönlendirileceksiniz.
                </p>
                <div className="flex justify-end space-x-3">
                  <button
                    type="button"
                    className="btn bg-gray-800 text-white hover:bg-gray-900"
                    onClick={cancelLogout}
                  >
                    İptal
                  </button>
                  <button
                    type="button"
                    className="btn btn-primary"
                    onClick={confirmLogout}
                  >
                    Çıkış Yap
                  </button>
                </div>
              </div>
            </div>
          </div>
        )}
      </aside>
    </>
  );
} 