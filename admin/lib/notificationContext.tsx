'use client';

import { createContext, ReactNode, useContext, useEffect, useState } from 'react';

// Bildirim tipi tanımlaması
export interface Notification {
  id: number;
  title: string;
  message: string;
  time: string;
  read: boolean;
  type: 'success' | 'warning' | 'danger' | 'info';
  date: Date;
}

// Context için tip tanımlaması
interface NotificationContextType {
  notifications: Notification[];
  addNotification: (notification: Omit<Notification, 'id' | 'time' | 'date'>) => void;
  removeNotification: (id: number) => void;
  markAsRead: (id: number) => void;
  markAllAsRead: () => void;
  unreadCount: number;
}

// Context oluşturma
const NotificationContext = createContext<NotificationContextType | undefined>(undefined);

// Context provider bileşeni
export function NotificationProvider({ children }: { children: ReactNode }) {
  // Örnek bildirimler dizisi
  const [notifications, setNotifications] = useState<Notification[]>([
    {
      id: 1,
      title: 'Yeni bağış yapıldı',
      message: 'Ahmet Yılmaz 250TL bağış yaptı.',
      time: '10 dakika önce',
      read: false,
      type: 'success',
      date: new Date(Date.now() - 10 * 60 * 1000) // 10 dakika önce
    },
    {
      id: 2,
      title: 'Sistem güncellemesi',
      message: 'Sistem bakımı tamamlandı.',
      time: '1 saat önce',
      read: false,
      type: 'info',
      date: new Date(Date.now() - 60 * 60 * 1000) // 1 saat önce
    },
    {
      id: 3,
      title: 'Bağış hedefi tamamlandı',
      message: '5000TL bağış hedefine ulaşıldı!',
      time: 'Dün',
      read: true,
      type: 'warning',
      date: new Date(Date.now() - 24 * 60 * 60 * 1000) // 1 gün önce
    },
  ]);

  // Okunmamış bildirimleri hesapla
  const unreadCount = notifications.filter(notif => !notif.read).length;

  // Yeni bildirim ekle
  const addNotification = (notification: Omit<Notification, 'id' | 'time' | 'date'>) => {
    const now = new Date();
    const newNotification: Notification = {
      id: Date.now(), // Benzersiz ID oluştur
      ...notification,
      time: getFormattedTime(now),
      date: now,
    };
    
    setNotifications(prev => [newNotification, ...prev]);
  };

  // Bildirimi okundu olarak işaretle
  const markAsRead = (id: number) => {
    setNotifications(notifications.map(notif => 
      notif.id === id ? {...notif, read: true} : notif
    ));
  };

  // Tüm bildirimleri okundu olarak işaretle
  const markAllAsRead = () => {
    setNotifications(notifications.map(notif => ({...notif, read: true})));
  };

  // Bir bildirimi sil
  const removeNotification = (id: number) => {
    setNotifications(notifications.filter(notif => notif.id !== id));
  };

  // Bildirim zamanını formatla
  const getFormattedTime = (date: Date): string => {
    const now = new Date();
    const diffMinutes = Math.floor((now.getTime() - date.getTime()) / (1000 * 60));
    
    if (diffMinutes < 1) return 'Şimdi';
    if (diffMinutes < 60) return `${diffMinutes} dakika önce`;
    
    const diffHours = Math.floor(diffMinutes / 60);
    if (diffHours < 24) return `${diffHours} saat önce`;
    
    const diffDays = Math.floor(diffHours / 24);
    if (diffDays === 1) return 'Dün';
    if (diffDays < 7) return `${diffDays} gün önce`;
    
    return `${date.getDate()}.${date.getMonth() + 1}.${date.getFullYear()}`;
  };

  // Bildirimler Local Storage'da saklanabilir (opsiyonel)
  useEffect(() => {
    // Local Storage'dan bildirimleri yükle
    const savedNotifications = localStorage.getItem('adminNotifications');
    if (savedNotifications) {
      try {
        const parsed = JSON.parse(savedNotifications);
        // Date nesnelerini yeniden oluştur
        const notificationsWithDates = parsed.map((n: any) => ({
          ...n,
          date: new Date(n.date)
        }));
        setNotifications(notificationsWithDates);
      } catch (error) {
        console.error('Bildirimler yüklenirken hata oluştu:', error);
      }
    }
  }, []);

  // Bildirimler değiştiğinde Local Storage'a kaydet
  useEffect(() => {
    localStorage.setItem('adminNotifications', JSON.stringify(notifications));
  }, [notifications]);

  return (
    <NotificationContext.Provider value={{ 
      notifications, 
      addNotification, 
      removeNotification, 
      markAsRead, 
      markAllAsRead,
      unreadCount 
    }}>
      {children}
    </NotificationContext.Provider>
  );
}

// Context hook'u
export function useNotifications() {
  const context = useContext(NotificationContext);
  if (context === undefined) {
    throw new Error('useNotifications hook must be used within a NotificationProvider');
  }
  return context;
} 