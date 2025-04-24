'use client';

import { useState } from 'react';
import {
    FaBell,
    FaCheck,
    FaCreditCard,
    FaGlobe,
    FaMoon,
    FaPalette,
    FaSave,
    FaSun
} from 'react-icons/fa';

export default function Settings() {
  const [activeTab, setActiveTab] = useState('general');
  const [isDarkMode, setIsDarkMode] = useState(false);
  const [accentColor, setAccentColor] = useState('#4CAF50');
  const [isLoading, setIsLoading] = useState(false);
  
  const handleSave = (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    
    // Demo için gecikme ekliyoruz
    setTimeout(() => {
      setIsLoading(false);
      alert('Ayarlar kaydedildi!');
    }, 800);
  };
  
  const handleThemeChange = () => {
    setIsDarkMode(!isDarkMode);
    // Gerçek uygulamada temayı kaydetme fonksiyonu eklenecek
  };
  
  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-800">Ayarlar</h1>
        <p className="text-gray-600 mt-1">
          Sistem ve panel ayarlarını buradan yönetebilirsiniz
        </p>
      </div>
      
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        {/* Ayar Sekmeleri */}
        <div className="col-span-1">
          <div className="card">
            <div className="p-1">
              <button
                className={`w-full text-left px-4 py-2 rounded-md flex items-center ${activeTab === 'general' ? 'bg-primary text-white' : 'hover:bg-gray-100'}`}
                onClick={() => setActiveTab('general')}
              >
                <FaGlobe className="mr-2" />
                <span>Genel Ayarlar</span>
              </button>
              
              <button
                className={`w-full text-left px-4 py-2 rounded-md flex items-center ${activeTab === 'donation' ? 'bg-primary text-white' : 'hover:bg-gray-100'}`}
                onClick={() => setActiveTab('donation')}
              >
                <FaCreditCard className="mr-2" />
                <span>Bağış Ayarları</span>
              </button>
              
              <button
                className={`w-full text-left px-4 py-2 rounded-md flex items-center ${activeTab === 'notification' ? 'bg-primary text-white' : 'hover:bg-gray-100'}`}
                onClick={() => setActiveTab('notification')}
              >
                <FaBell className="mr-2" />
                <span>Bildirim Ayarları</span>
              </button>
              
              <button
                className={`w-full text-left px-4 py-2 rounded-md flex items-center ${activeTab === 'appearance' ? 'bg-primary text-white' : 'hover:bg-gray-100'}`}
                onClick={() => setActiveTab('appearance')}
              >
                <FaPalette className="mr-2" />
                <span>Görünüm Ayarları</span>
              </button>
            </div>
          </div>
        </div>
        
        {/* Ayar İçeriği */}
        <div className="col-span-1 md:col-span-3">
          <div className="card">
            <div className="card-header">
              <h2 className="card-title">
                {activeTab === 'general' && 'Genel Ayarlar'}
                {activeTab === 'donation' && 'Bağış Ayarları'}
                {activeTab === 'notification' && 'Bildirim Ayarları'}
                {activeTab === 'appearance' && 'Görünüm Ayarları'}
              </h2>
            </div>
            
            <form onSubmit={handleSave}>
              <div className="p-6">
                {/* Genel Ayarlar */}
                {activeTab === 'general' && (
                  <div>
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-1">
                        Site Başlığı
                      </label>
                      <input
                        type="text"
                        className="input-field"
                        defaultValue="Çınaraltı Vakfı"
                        aria-label="Site Başlığı"
                      />
                    </div>
                    
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-1">
                        Site Açıklaması
                      </label>
                      <textarea
                        className="input-field"
                        rows={3}
                        defaultValue="Çınaraltı Vakfı resmi web sitesi"
                        aria-label="Site Açıklaması"
                      />
                    </div>
                    
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-1">
                        İletişim E-posta
                      </label>
                      <input
                        type="email"
                        className="input-field"
                        defaultValue="info@cinaralti.org"
                        aria-label="İletişim E-posta"
                      />
                    </div>
                    
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-1">
                        Telefon Numarası
                      </label>
                      <input
                        type="text"
                        className="input-field"
                        defaultValue="+90 212 123 4567"
                        aria-label="Telefon Numarası"
                      />
                    </div>
                    
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-1">
                        Adres
                      </label>
                      <textarea
                        className="input-field"
                        rows={2}
                        defaultValue="Örnek Mah. Çınaraltı Cad. No:123 İstanbul/Türkiye"
                        aria-label="Adres"
                      />
                    </div>
                  </div>
                )}
                
                {/* Bağış Ayarları */}
                {activeTab === 'donation' && (
                  <div>
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-1">
                        Varsayılan Para Birimi
                      </label>
                      <select
                        className="select-field"
                        defaultValue="TRY"
                        aria-label="Varsayılan Para Birimi"
                      >
                        <option value="TRY">Türk Lirası (₺)</option>
                        <option value="USD">Amerikan Doları ($)</option>
                        <option value="EUR">Euro (€)</option>
                      </select>
                    </div>
                    
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-1">
                        Minimum Bağış Miktarı
                      </label>
                      <input
                        type="number"
                        className="input-field"
                        defaultValue={10}
                        min={1}
                        aria-label="Minimum Bağış Miktarı"
                      />
                    </div>
                    
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-1">
                        Önerilen Bağış Miktarları (virgülle ayırın)
                      </label>
                      <input
                        type="text"
                        className="input-field"
                        defaultValue="50, 100, 250, 500, 1000"
                        aria-label="Önerilen Bağış Miktarları"
                      />
                      <p className="text-xs text-gray-500 mt-1">
                        Bu değerler bağış formunda hızlı seçim olarak görüntülenecek
                      </p>
                    </div>
                    
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-1">
                        Ödeme Yöntemleri
                      </label>
                      <div className="space-y-2">
                        <div className="flex items-center">
                          <input
                            type="checkbox"
                            id="payment_credit_card"
                            defaultChecked
                            className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                          />
                          <label htmlFor="payment_credit_card" className="ml-2 block text-sm text-gray-900">
                            Kredi Kartı
                          </label>
                        </div>
                        <div className="flex items-center">
                          <input
                            type="checkbox"
                            id="payment_bank_transfer"
                            defaultChecked
                            className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                          />
                          <label htmlFor="payment_bank_transfer" className="ml-2 block text-sm text-gray-900">
                            Banka Havalesi
                          </label>
                        </div>
                        <div className="flex items-center">
                          <input
                            type="checkbox"
                            id="payment_paypal"
                            className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                          />
                          <label htmlFor="payment_paypal" className="ml-2 block text-sm text-gray-900">
                            PayPal
                          </label>
                        </div>
                      </div>
                    </div>
                    
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-1">
                        Bağış Makbuzu Gönderimi
                      </label>
                      <div className="flex items-center">
                        <input
                          type="checkbox"
                          id="send_receipt"
                          defaultChecked
                          className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                        />
                        <label htmlFor="send_receipt" className="ml-2 block text-sm text-gray-900">
                          Bağış sonrası otomatik makbuz gönder
                        </label>
                      </div>
                    </div>
                  </div>
                )}
                
                {/* Bildirim Ayarları */}
                {activeTab === 'notification' && (
                  <div>
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-2">
                        E-posta Bildirimleri
                      </label>
                      <div className="space-y-2">
                        <div className="flex items-center">
                          <input
                            type="checkbox"
                            id="notify_new_donation"
                            defaultChecked
                            className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                          />
                          <label htmlFor="notify_new_donation" className="ml-2 block text-sm text-gray-900">
                            Yeni bağış alındığında bildirim gönder
                          </label>
                        </div>
                        <div className="flex items-center">
                          <input
                            type="checkbox"
                            id="notify_donation_problem"
                            defaultChecked
                            className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                          />
                          <label htmlFor="notify_donation_problem" className="ml-2 block text-sm text-gray-900">
                            Ödeme sorunu olduğunda bildirim gönder
                          </label>
                        </div>
                        <div className="flex items-center">
                          <input
                            type="checkbox"
                            id="notify_daily_summary"
                            defaultChecked
                            className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                          />
                          <label htmlFor="notify_daily_summary" className="ml-2 block text-sm text-gray-900">
                            Günlük bağış özeti gönder
                          </label>
                        </div>
                      </div>
                    </div>
                    
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-1">
                        Bildirim Alıcıları (virgülle ayırın)
                      </label>
                      <input
                        type="text"
                        className="input-field"
                        defaultValue="admin@cinaralti.org, yonetim@cinaralti.org"
                        aria-label="Bildirim Alıcıları"
                      />
                      <p className="text-xs text-gray-500 mt-1">
                        Bu e-posta adreslerine bildirimleri gönderilecek
                      </p>
                    </div>
                    
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-1">
                        Bildirim Sıklığı
                      </label>
                      <select 
                        className="select-field"
                        defaultValue="instant"
                        aria-label="Bildirim Sıklığı"
                      >
                        <option value="instant">Anında</option>
                        <option value="hourly">Saatlik</option>
                        <option value="daily">Günlük Özet</option>
                      </select>
                    </div>
                  </div>
                )}
                
                {/* Görünüm Ayarları */}
                {activeTab === 'appearance' && (
                  <div>
                    <div className="mb-6">
                      <label className="block text-sm font-medium text-gray-700 mb-2">
                        Tema Modu
                      </label>
                      <div className="flex items-center space-x-4">
                        <button
                          type="button"
                          className={`flex items-center px-4 py-2 rounded-md ${
                            !isDarkMode ? 'bg-primary text-white' : 'bg-gray-100'
                          }`}
                          onClick={handleThemeChange}
                          aria-pressed={!isDarkMode ? "true" : "false"}
                        >
                          <FaSun className="mr-2" />
                          <span>Açık Tema</span>
                          {!isDarkMode && <FaCheck className="ml-2" />}
                        </button>
                        
                        <button
                          type="button"
                          className={`flex items-center px-4 py-2 rounded-md ${
                            isDarkMode ? 'bg-primary text-white' : 'bg-gray-100'
                          }`}
                          onClick={handleThemeChange}
                          aria-pressed={isDarkMode ? "true" : "false"}
                        >
                          <FaMoon className="mr-2" />
                          <span>Koyu Tema</span>
                          {isDarkMode && <FaCheck className="ml-2" />}
                        </button>
                      </div>
                    </div>
                    
                    <div className="mb-6">
                      <label className="block text-sm font-medium text-gray-700 mb-2">
                        Vurgu Rengi
                      </label>
                      <div className="flex items-center">
                        <input
                          type="color"
                          value={accentColor}
                          onChange={(e) => setAccentColor(e.target.value)}
                          className="h-10 w-16 rounded border-0"
                          aria-label="Vurgu Rengi"
                        />
                        <span className="ml-2 text-sm text-gray-600">{accentColor}</span>
                      </div>
                      <p className="text-xs text-gray-500 mt-1">
                        Bu renk butonlar, bağlantılar ve vurgulanan öğeler için kullanılır
                      </p>
                    </div>
                    
                    <div className="mb-4">
                      <label className="block text-sm font-medium text-gray-700 mb-1">
                        Dashboard'da Gösterilecek Öğeler
                      </label>
                      <div className="space-y-2">
                        <div className="flex items-center">
                          <input
                            type="checkbox"
                            id="show_recent_donations"
                            defaultChecked
                            className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                          />
                          <label htmlFor="show_recent_donations" className="ml-2 block text-sm text-gray-900">
                            Son Bağışlar
                          </label>
                        </div>
                        <div className="flex items-center">
                          <input
                            type="checkbox"
                            id="show_donation_stats"
                            defaultChecked
                            className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                          />
                          <label htmlFor="show_donation_stats" className="ml-2 block text-sm text-gray-900">
                            Bağış İstatistikleri
                          </label>
                        </div>
                        <div className="flex items-center">
                          <input
                            type="checkbox"
                            id="show_donation_chart"
                            defaultChecked
                            className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                          />
                          <label htmlFor="show_donation_chart" className="ml-2 block text-sm text-gray-900">
                            Bağış Grafiği
                          </label>
                        </div>
                        <div className="flex items-center">
                          <input
                            type="checkbox"
                            id="show_system_status"
                            defaultChecked
                            className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                          />
                          <label htmlFor="show_system_status" className="ml-2 block text-sm text-gray-900">
                            Sistem Durumu
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                )}
              </div>
              
              <div className="px-6 py-4 border-t border-gray-200">
                <button
                  type="submit"
                  className="btn btn-primary flex items-center"
                  disabled={isLoading}
                >
                  {isLoading ? (
                    <>
                      <div className="h-4 w-4 mr-2 rounded-full border-2 border-white border-t-transparent animate-spin"></div>
                      <span>Kaydediliyor...</span>
                    </>
                  ) : (
                    <>
                      <FaSave className="mr-2" />
                      <span>Ayarları Kaydet</span>
                    </>
                  )}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  );
} 