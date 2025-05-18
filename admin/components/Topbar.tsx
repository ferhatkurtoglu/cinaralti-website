'use client';

import { useNotifications } from '@/lib/notificationContext';
import { useSidebarState } from '@/lib/sidebarState';
import { useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';
import { FaBars, FaBell, FaCheckCircle, FaExclamationCircle, FaInfoCircle, FaMoon, FaSearch, FaSun, FaTimes, FaUser } from 'react-icons/fa';

export default function Topbar() {
  const router = useRouter();
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const [notificationsOpen, setNotificationsOpen] = useState(false);
  const [mobileSearchOpen, setMobileSearchOpen] = useState(false);
  const [isDarkMode, setIsDarkMode] = useState(false);
  const [showLogoutModal, setShowLogoutModal] = useState(false);
  const [user, setUser] = useState<any>(null);
  const { isOpen, toggle } = useSidebarState();
  
  // Bildirim context'i kullan
  const { 
    notifications, 
    markAsRead, 
    markAllAsRead, 
    removeNotification,
    unreadCount
  } = useNotifications();

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

  // Bildirimi veya dropdown'ı kapatan fonksiyon
  const closeDropdowns = () => {
    if (notificationsOpen) setNotificationsOpen(false);
    if (dropdownOpen) setDropdownOpen(false);
  };

  // Çıkış yapma fonksiyonu
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

  // Sayfa document'ine click event listener ekleyen useEffect
  useEffect(() => {
    const handleOutsideClick = (event: MouseEvent) => {
      // Tıklanan elementin bildirim veya profil butonlarının içinde olup olmadığını kontrol et
      const notificationContainer = document.querySelector('.notification-container');
      const profileContainer = document.querySelector('.profile-container');
      const notificationButton = document.querySelector('.notification-container button');
      const profileButton = document.querySelector('.profile-container button');
      
      // Eğer bildirim veya profil konteynerlerinin dışına tıklanırsa ve butonlara tıklanmadıysa kapat
      if (notificationContainer && !notificationContainer.contains(event.target as Node) &&
          profileContainer && !profileContainer.contains(event.target as Node)) {
        closeDropdowns();
      }
    };

    // Event listener'ı ekle
    document.addEventListener('mousedown', handleOutsideClick);
    
    // Cleanup fonksiyonu
    return () => {
      document.removeEventListener('mousedown', handleOutsideClick);
    };
  }, [notificationsOpen, dropdownOpen]);

  // Tema değiştirme fonksiyonu - sadece butonu değiştirir
  const toggleThemeButton = () => {
    setIsDarkMode(!isDarkMode);
  };

  // Bildirim tipine göre ikon ve renk belirle
  const getNotificationIcon = (type: string) => {
    switch(type) {
      case 'success':
        return <FaCheckCircle className="text-green-500" />;
      case 'warning':
        return <FaExclamationCircle className="text-yellow-500" />;
      case 'danger':
        return <FaTimes className="text-red-500" />;
      case 'info':
      default:
        return <FaInfoCircle className="text-blue-500" />;
    }
  };

  // Bildirim tipi için arka plan rengi
  const getBackgroundColor = (type: string) => {
    switch(type) {
      case 'success':
        return 'bg-green-100';
      case 'warning':
        return 'bg-yellow-100';
      case 'danger':
        return 'bg-red-100';
      case 'info':
      default:
        return 'bg-blue-100';
    }
  };
  
  return (
    <div className="flex items-center justify-between h-16 px-4 md:px-6 bg-white shadow-sm sticky top-0 z-20">
      <div className="flex items-center">
        {/* Mobil menü toggle */}
        <button
          onClick={() => toggle(!isOpen)}
          className="p-2 mr-2 rounded-md md:hidden text-gray-600 hover:bg-gray-100"
          aria-label="Menüyü Aç/Kapat"
        >
          <FaBars />
        </button>

        {/* Mobil arama butonu */}
        <button
          className="p-2 rounded-full hover:bg-gray-100 md:hidden"
          onClick={() => setMobileSearchOpen(!mobileSearchOpen)}
          aria-label="Arama"
        >
          <FaSearch className="text-gray-600" />
        </button>

        {/* Büyük ekranlarda arama */}
        <div className="relative hidden md:block">
          <input
            type="text"
            placeholder="Ara..."
            className="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-primary focus:border-transparent"
            aria-label="Arama"
          />
          <FaSearch className="absolute left-3 top-3 text-gray-400" />
        </div>
      </div>

      {/* Mobil arama formu (açıldığında görünür) */}
      {mobileSearchOpen && (
        <div className="absolute top-16 left-0 right-0 p-4 bg-white shadow-md z-20 md:hidden">
          <div className="relative">
            <input
              type="text"
              placeholder="Ara..."
              className="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-1 focus:ring-primary focus:border-transparent"
              aria-label="Arama"
              autoFocus
            />
            <FaSearch className="absolute left-3 top-3 text-gray-400" />
            <button
              className="absolute right-3 top-3 text-gray-400"
              onClick={() => setMobileSearchOpen(false)}
              aria-label="Aramayı Kapat"
            >
              <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      )}

      <div className="flex items-center space-x-2 md:space-x-4">
        {/* Tema Değiştirme Butonu */}
        <div className="relative">
          <button
            onClick={toggleThemeButton}
            className="p-2 rounded-full hover:bg-gray-100"
            aria-label="Tema Değiştir"
          >
            {isDarkMode ? (
              <FaSun className="text-yellow-500" />
            ) : (
              <FaMoon className="text-gray-600" />
            )}
          </button>
        </div>

        {/* Bildirimler */}
        <div className="relative notification-container">
          <button
            onClick={(e) => {
              e.stopPropagation();
              setNotificationsOpen(!notificationsOpen);
              if (dropdownOpen) setDropdownOpen(false);
            }}
            className="p-2 rounded-full hover:bg-gray-100 relative cursor-pointer"
            aria-label="Bildirimler"
          >
            <FaBell className="text-gray-600" />
            {unreadCount > 0 && (
              <span className="notification-badge">
                {unreadCount}
              </span>
            )}
          </button>

          {notificationsOpen && (
            <div className="dropdown-menu w-80 md:w-96 right-0 mt-2 z-50">
              <div className="py-2 px-3 bg-gray-100 border-b border-gray-200">
                <div className="flex justify-between items-center">
                  <h3 className="text-sm font-semibold text-gray-700">Bildirimler</h3>
                  <button 
                    onClick={markAllAsRead}
                    className="text-xs text-primary cursor-pointer hover:underline"
                  >
                    Tümünü Okundu İşaretle
                  </button>
                </div>
              </div>
              <div className="max-h-96 overflow-y-auto">
                {notifications.length > 0 ? (
                  notifications.slice(0, 5).map((notification) => (
                    <div 
                      key={notification.id} 
                      className={`py-3 px-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer relative ${notification.read ? 'opacity-75' : ''}`}
                    >
                      <div className="flex items-start">
                        <div className="flex-shrink-0 mr-3">
                          <div className={`w-10 h-10 rounded-full ${getBackgroundColor(notification.type)} flex items-center justify-center`}>
                            {getNotificationIcon(notification.type)}
                          </div>
                        </div>
                        <div 
                          className="flex-1"
                          onClick={() => markAsRead(notification.id)}
                        >
                          <p className={`text-sm font-medium text-gray-900 ${notification.read ? '' : 'font-bold'}`}>
                            {notification.title}
                          </p>
                          <p className="text-xs text-gray-600 mt-1">{notification.message}</p>
                          <p className="text-xs text-gray-500 mt-1">{notification.time}</p>
                        </div>
                        <button 
                          onClick={(e) => {
                            e.stopPropagation();
                            removeNotification(notification.id);
                          }}
                          className="text-gray-400 hover:text-gray-600 ml-2"
                          aria-label="Bildirimi Sil"
                        >
                          <FaTimes size={14} />
                        </button>
                      </div>
                      {!notification.read && (
                        <div className="absolute top-1/2 transform -translate-y-1/2 left-0 w-1 h-8 bg-primary rounded-r-full"></div>
                      )}
                    </div>
                  ))
                ) : (
                  <div className="py-6 text-center text-gray-500">
                    <FaBell className="mx-auto text-gray-300 mb-2" size={24} />
                    <p>Bildiriminiz yok</p>
                  </div>
                )}
              </div>
              <div className="py-2 px-4 text-center text-sm border-t border-gray-100">
                <a href="/dashboard/notifications" className="text-primary hover:underline">
                  Tüm Bildirimleri Gör
                </a>
              </div>
            </div>
          )}
        </div>

        {/* Kullanıcı Profili */}
        <div className="relative profile-container">
          <button
            onClick={(e) => {
              e.stopPropagation();
              setDropdownOpen(!dropdownOpen);
              if (notificationsOpen) setNotificationsOpen(false);
            }}
            className="flex items-center space-x-2 cursor-pointer"
            aria-label="Kullanıcı Menüsü"
          >
            <span className="h-8 w-8 rounded-full bg-primary text-white flex items-center justify-center">
              <FaUser />
            </span>
            <span className="font-medium text-gray-700 hidden md:inline-block">
              {user?.name || 'Admin'}
            </span>
          </button>

          {dropdownOpen && (
            <div className="dropdown-menu z-50">
              <a
                href="/dashboard/profile"
                className="dropdown-item"
              >
                Profil
              </a>
              <a
                href="/dashboard/settings"
                className="dropdown-item"
              >
                Ayarlar
              </a>
              <button
                onClick={handleLogout}
                className="dropdown-item text-left w-full text-red-600"
              >
                Çıkış Yap
              </button>
            </div>
          )}
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
    </div>
  );
} 