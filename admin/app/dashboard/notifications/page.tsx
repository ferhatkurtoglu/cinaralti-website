'use client';

import { Notification, useNotifications } from '@/lib/notificationContext';
import { useEffect, useState } from 'react';
import { FaBell, FaCheckCircle, FaExclamationCircle, FaEye, FaInfoCircle, FaSortAmountDown, FaSortAmountUp, FaTimes, FaTrash } from 'react-icons/fa';

export default function NotificationsPage() {
  // Bildirim context'ini kullan
  const { 
    notifications, 
    markAsRead, 
    markAllAsRead, 
    removeNotification,
  } = useNotifications();

  // Filtreleme ve sıralama durumlarını tutacak state'ler
  const [filteredNotifications, setFilteredNotifications] = useState<Notification[]>(notifications);
  const [filterType, setFilterType] = useState<string>('all');
  const [filterRead, setFilterRead] = useState<string>('all');
  const [sortOrder, setSortOrder] = useState<'asc' | 'desc'>('desc');
  // Mobil için görünüm modu
  const [isMobileView, setIsMobileView] = useState(false);

  // Ekran boyutunu kontrol eden useEffect
  useEffect(() => {
    const checkScreenSize = () => {
      setIsMobileView(window.innerWidth < 768);
    };
    
    // İlk yükleme kontrolü
    checkScreenSize();
    
    // Event listener ekle
    window.addEventListener('resize', checkScreenSize);
    
    // Cleanup
    return () => window.removeEventListener('resize', checkScreenSize);
  }, []);

  // Bildirimi okundu/okunmadı olarak işaretle
  const toggleReadStatus = (id: number) => {
    if (notifications.find(n => n.id === id)?.read) {
      // Bildirim zaten okunmuşsa
      // Context'in markAsUnread metodu yoksa burada fonksiyonu tanımlayabiliriz
      // Not: Normalde context'e markAsUnread metodu da eklenmelidir
    } else {
      markAsRead(id);
    }
  };

  // Seçili bildirimleri sil
  const [selectedNotifications, setSelectedNotifications] = useState<number[]>([]);
  
  const toggleSelection = (id: number) => {
    if (selectedNotifications.includes(id)) {
      setSelectedNotifications(selectedNotifications.filter(notifId => notifId !== id));
    } else {
      setSelectedNotifications([...selectedNotifications, id]);
    }
  };

  const selectAll = () => {
    if (selectedNotifications.length === filteredNotifications.length) {
      setSelectedNotifications([]);
    } else {
      setSelectedNotifications(filteredNotifications.map(notif => notif.id));
    }
  };

  const deleteSelected = () => {
    selectedNotifications.forEach(id => {
      removeNotification(id);
    });
    setSelectedNotifications([]);
  };

  // Filtre ve sıralama değişikliklerini takip eden useEffect
  useEffect(() => {
    let result = [...notifications];
    
    // Bildiri tipine göre filtrele
    if (filterType !== 'all') {
      result = result.filter(n => n.type === filterType);
    }
    
    // Okunma durumuna göre filtrele
    if (filterRead === 'read') {
      result = result.filter(n => n.read);
    } else if (filterRead === 'unread') {
      result = result.filter(n => !n.read);
    }
    
    // Tarihe göre sırala
    result.sort((a, b) => {
      if (sortOrder === 'asc') {
        return a.date.getTime() - b.date.getTime();
      } else {
        return b.date.getTime() - a.date.getTime();
      }
    });
    
    setFilteredNotifications(result);
  }, [notifications, filterType, filterRead, sortOrder]);

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

  // Mobil için kart görünümü
  const renderMobileView = () => {
    return (
      <div className="space-y-4">
        {filteredNotifications.length > 0 ? (
          filteredNotifications.map((notification) => (
            <div 
              key={notification.id} 
              className={`bg-white p-4 rounded-lg shadow-sm border-l-4 ${
                notification.read 
                  ? 'border-gray-200' 
                  : 'border-primary'
              }`}
            >
              <div className="flex items-start mb-2">
                <div className="flex-shrink-0 mr-3">
                  <div className={`w-10 h-10 rounded-full ${getBackgroundColor(notification.type)} flex items-center justify-center`}>
                    {getNotificationIcon(notification.type)}
                  </div>
                </div>
                
                <div className="flex-1">
                  <div className="flex items-center justify-between">
                    <p className={`text-sm font-medium ${notification.read ? 'text-gray-700' : 'text-gray-900'}`}>
                      {notification.title}
                    </p>
                    <div className="flex items-center">
                      <input
                        type="checkbox"
                        checked={selectedNotifications.includes(notification.id)}
                        onChange={() => toggleSelection(notification.id)}
                        className="rounded mr-2"
                        aria-label={`Bildirim seç: ${notification.title}`}
                      />
                    </div>
                  </div>
                  <p className="text-xs text-gray-500 mt-1">
                    {notification.message}
                  </p>
                </div>
              </div>
              
              <div className="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
                <div className="flex items-center">
                  <span className="text-xs text-gray-500">{notification.time}</span>
                  <span className={`ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-xs ${
                    notification.read
                      ? 'bg-gray-100 text-gray-800'
                      : 'bg-blue-100 text-blue-800'
                  }`}>
                    {notification.read ? 'Okundu' : 'Okunmadı'}
                  </span>
                </div>
                
                <div className="flex space-x-2">
                  <button
                    onClick={() => toggleReadStatus(notification.id)}
                    className="p-1.5 rounded-md hover:bg-gray-100 text-gray-600"
                    aria-label={notification.read ? "Okunmadı olarak işaretle" : "Okundu olarak işaretle"}
                  >
                    <FaEye size={16} />
                  </button>
                  <button
                    onClick={() => removeNotification(notification.id)}
                    className="p-1.5 rounded-md hover:bg-gray-100 text-red-600"
                    aria-label="Sil"
                  >
                    <FaTrash size={16} />
                  </button>
                </div>
              </div>
            </div>
          ))
        ) : (
          <div className="py-8 text-center bg-white rounded-lg shadow-sm">
            <FaBell className="mx-auto text-gray-300 mb-2" size={24} />
            <p className="text-gray-500">Bildirim bulunamadı</p>
          </div>
        )}
      </div>
    );
  };

  // Masaüstü için tablo görünümü
  const renderDesktopView = () => {
    return (
      <div className="table-container mt-4 overflow-x-auto">
        {filteredNotifications.length > 0 ? (
          <table className="data-table w-full">
            <thead>
              <tr>
                <th style={{ width: '40px' }}>
                  <input
                    type="checkbox"
                    onChange={selectAll}
                    checked={selectedNotifications.length > 0 && selectedNotifications.length === filteredNotifications.length}
                    className="rounded"
                    aria-label="Tüm bildirimleri seç"
                  />
                </th>
                <th style={{ width: '60px' }}>Tür</th>
                <th>Bildirim</th>
                <th style={{ width: '120px' }}>Zaman</th>
                <th style={{ width: '100px' }}>Durum</th>
                <th style={{ width: '100px' }}>İşlemler</th>
              </tr>
            </thead>
            <tbody>
              {filteredNotifications.map((notification) => (
                <tr 
                  key={notification.id} 
                  className={`${notification.read ? '' : 'font-medium bg-gray-50'} hover:bg-gray-50`}
                >
                  <td className="text-center">
                    <input
                      type="checkbox"
                      checked={selectedNotifications.includes(notification.id)}
                      onChange={() => toggleSelection(notification.id)}
                      className="rounded"
                      aria-label={`Bildirim seç: ${notification.title}`}
                    />
                  </td>
                  <td className="text-center">
                    <div className={`w-8 h-8 rounded-full ${getBackgroundColor(notification.type)} flex items-center justify-center mx-auto`}>
                      {getNotificationIcon(notification.type)}
                    </div>
                  </td>
                  <td>
                    <div className="max-w-2xl">
                      <p className={`text-sm ${notification.read ? 'text-gray-700' : 'text-gray-900'}`}>
                        {notification.title}
                      </p>
                      <p className="text-xs text-gray-500 mt-1 line-clamp-2">
                        {notification.message}
                      </p>
                    </div>
                  </td>
                  <td className="text-sm text-gray-500 whitespace-nowrap">{notification.time}</td>
                  <td>
                    <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${
                      notification.read
                        ? 'bg-gray-100 text-gray-800'
                        : 'bg-blue-100 text-blue-800'
                    }`}>
                      {notification.read ? 'Okundu' : 'Okunmadı'}
                    </span>
                  </td>
                  <td>
                    <div className="flex items-center justify-center space-x-3">
                      <button
                        onClick={() => toggleReadStatus(notification.id)}
                        className="p-1.5 rounded-md hover:bg-gray-100 text-gray-600"
                        aria-label={notification.read ? "Okunmadı olarak işaretle" : "Okundu olarak işaretle"}
                      >
                        <FaEye />
                      </button>
                      <button
                        onClick={() => removeNotification(notification.id)}
                        className="p-1.5 rounded-md hover:bg-gray-100 text-gray-600"
                        aria-label="Sil"
                      >
                        <FaTrash />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        ) : (
          <div className="py-8 text-center">
            <FaBell className="mx-auto text-gray-300 mb-2" size={24} />
            <p className="text-gray-500">Bildirim bulunamadı</p>
          </div>
        )}
      </div>
    );
  };

  return (
    <div className="p-3 md:p-6">
      <div className="card">
        <div className="flex flex-col">
          <div className="flex items-center justify-between mb-4 border-b pb-3">
            <h1 className="card-title mb-0">Bildirimler</h1>
          </div>
          
          <div className="mb-6">
            <div className="flex flex-wrap gap-2 mb-3 w-full">
              <button
                onClick={markAllAsRead}
                className="btn btn-primary flex items-center gap-1 text-xs md:text-sm py-2 px-3"
                disabled={notifications.filter(n => !n.read).length === 0}
              >
                <FaCheckCircle size={14} />
                <span>Tümünü Okundu İşaretle</span>
              </button>
              <button
                onClick={deleteSelected}
                className="btn btn-danger flex items-center gap-1 text-xs md:text-sm py-2 px-3"
                disabled={selectedNotifications.length === 0}
              >
                <FaTrash size={14} />
                <span>Seçilenleri Sil {selectedNotifications.length > 0 && `(${selectedNotifications.length})`}</span>
              </button>
            </div>

            <div className="flex flex-wrap gap-2 items-center w-full">
              <select
                className="select-field text-xs md:text-sm py-2 px-3 rounded-md w-full sm:w-auto"
                value={filterType}
                onChange={(e) => setFilterType(e.target.value)}
                aria-label="Bildirim tipine göre filtrele"
              >
                <option value="all">Tüm Tipler</option>
                <option value="success">Başarılı</option>
                <option value="warning">Uyarı</option>
                <option value="danger">Hata</option>
                <option value="info">Bilgi</option>
              </select>
              
              <select
                className="select-field text-xs md:text-sm py-2 px-3 rounded-md w-full sm:w-auto"
                value={filterRead}
                onChange={(e) => setFilterRead(e.target.value)}
                aria-label="Okunma durumuna göre filtrele"
              >
                <option value="all">Tüm Bildirimler</option>
                <option value="read">Okunmuş</option>
                <option value="unread">Okunmamış</option>
              </select>
              
              <button
                onClick={() => setSortOrder(sortOrder === 'desc' ? 'asc' : 'desc')}
                className="p-2 rounded-md hover:bg-gray-100 text-gray-600 flex items-center"
                aria-label={sortOrder === 'desc' ? 'Eskiden Yeniye Sırala' : 'Yeniden Eskiye Sırala'}
              >
                {sortOrder === 'desc' ? <FaSortAmountDown size={16} /> : <FaSortAmountUp size={16} />}
              </button>
            </div>
          </div>
        </div>

        <div className="mt-2">
          {isMobileView ? renderMobileView() : renderDesktopView()}
        </div>
      </div>
    </div>
  );
} 