'use client';

import { Bell, LogOut, Menu, User } from 'lucide-react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';

export default function Header() {
  const router = useRouter();
  const [user, setUser] = useState<any>(null);
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const [showLogoutModal, setShowLogoutModal] = useState(false);

  useEffect(() => {
    // Kullanıcı bilgilerini localStorage veya sessionStorage'dan al
    const getUserFromStorage = () => {
      const fromLocal = localStorage.getItem('adminUser');
      const fromSession = sessionStorage.getItem('adminUser');
      return fromLocal || fromSession;
    };
    
    const userStr = getUserFromStorage();
    if (userStr) {
      try {
        const userData = JSON.parse(userStr);
        setUser(userData);
      } catch (error) {
        console.error('Kullanıcı bilgileri çözümlenemedi', error);
      }
    }
  }, []);

  const handleLogout = () => {
    // Modal'ı göster
    setShowLogoutModal(true);
    // Dropdown'ı kapat
    setDropdownOpen(false);
  };

  // Çıkış onayı
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
      
      // Ana sayfaya yönlendir
      window.location.href = '/'; // router.push yerine doğrudan location değiştiriyoruz
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

  // Çıkış iptal
  const cancelLogout = () => {
    setShowLogoutModal(false);
  };

  return (
    <header className="bg-white shadow-sm z-10">
      <div className="flex justify-between items-center px-4 py-3">
        <div className="flex items-center">
          <button 
            className="text-gray-500 focus:outline-none mr-4 md:hidden"
            aria-label="Menüyü Aç"
          >
            <Menu size={24} />
          </button>
          <h2 className="text-xl font-semibold text-gray-800">Çınaraltı Yönetim Paneli</h2>
        </div>
        
        <div className="flex items-center space-x-4">
          <button 
            className="text-gray-500 hover:text-gray-700 focus:outline-none"
            aria-label="Bildirimler"
          >
            <Bell size={20} />
          </button>
          
          <div className="relative">
            <button
              onClick={() => setDropdownOpen(!dropdownOpen)}
              className="flex items-center focus:outline-none"
              aria-label="Kullanıcı menüsü"
            >
              <div className="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                <User size={18} />
              </div>
              {user && (
                <span className="ml-2 text-sm font-medium hidden md:block">
                  {user.name}
                </span>
              )}
            </button>
            
            {dropdownOpen && (
              <div className="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                <div className="px-4 py-2 text-xs text-gray-500">
                  {user?.email}
                </div>
                <Link 
                  href="/dashboard/profile" 
                  className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                  onClick={() => setDropdownOpen(false)}
                >
                  Profilim
                </Link>
                <button
                  onClick={handleLogout}
                  className="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center"
                >
                  <LogOut size={16} className="mr-2" />
                  Çıkış Yap
                </button>
              </div>
            )}
          </div>
        </div>
      </div>
      
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
    </header>
  );
} 