'use client';

import Image from 'next/image';
import { useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';

export default function LoginPage() {
  const router = useRouter();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);
  const [showForgotPassword, setShowForgotPassword] = useState(false);
  const [resetEmail, setResetEmail] = useState('');
  const [rememberMe, setRememberMe] = useState(false);
  
  // Sayfa yüklendiğinde çalışır
  useEffect(() => {
    // Konsolu temizle
    console.clear();
    console.log('Giriş sayfası yüklendi, token kontrolü yapılıyor...');
    
    // Kullanıcı zaten giriş yapmışsa dashboard'a yönlendir
    const token = localStorage.getItem('adminToken') || sessionStorage.getItem('adminToken');
    if (token) {
      console.log('Kullanıcı zaten giriş yapmış, dashboard\'a yönlendiriliyor');
      // Router kullanarak yönlendirme yap - window.location yerine
      router.push('/dashboard');
    }
  }, []); // Sadece bir kez çalışması için boş dependency array

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    try {
      console.log('Giriş isteği gönderiliyor:', { email, password: '***' });
      
      const response = await fetch('/api/auth', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email, password })
      });

      console.log('Giriş yanıtı alındı, durum:', response.status);
      
      const data = await response.json();
      console.log('Giriş yanıtı:', data);

      if (!response.ok) {
        throw new Error(data.error || 'Giriş yapılamadı');
      }

      // Beni hatırla seçeneğine göre token saklama
      if (rememberMe) {
        // Kalıcı olarak localStorage'a kaydet
        localStorage.setItem('adminToken', data.token);
        localStorage.setItem('adminUser', JSON.stringify(data.user));
        console.log('LocalStorage\'a kaydedildi (Beni hatırla aktif)');
      } else {
        // Sadece oturum süresince sessionStorage'a kaydet
        sessionStorage.setItem('adminToken', data.token);
        sessionStorage.setItem('adminUser', JSON.stringify(data.user));
        console.log('SessionStorage\'a kaydedildi');
      }
      
      // Dashboard'a yönlendir - router.push yerine window.location kullanarak
      console.log('Dashboard\'a yönlendiriliyor');
      
      // Router kullanarak yönlendirme yap
      router.push('/dashboard');
      
    } catch (err: any) {
      console.error('Giriş hatası:', err);
      setError(err.message || 'Giriş sırasında bir hata oluştu');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleResetPassword = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setSuccess('');
    setLoading(true);

    try {
      const response = await fetch('/api/auth/reset-password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: resetEmail })
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.error || 'Şifre sıfırlama isteği başarısız oldu');
      }
      
      setSuccess('Şifre sıfırlama talimatları e-posta adresinize gönderildi');
      setResetEmail('');
      
    } catch (err: any) {
      setError(err.message || 'İşlem sırasında bir hata oluştu');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100">
      <div className="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div className="text-center mb-8">
          <div className="flex justify-center mb-4">
            <Image 
              src="/assets/images/logo.png" 
              alt="Çınaraltı Logo" 
              width={120} 
              height={120}
              className="rounded-full"
            />
          </div>
          <h1 className="text-2xl font-bold text-gray-800">Çınaraltı Admin Panel</h1>
          <p className="text-gray-600 mt-2">Bağış yönetim sistemi</p>
        </div>

        {error && (
          <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span className="block sm:inline">{error}</span>
          </div>
        )}
        
        {success && (
          <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span className="block sm:inline">{success}</span>
          </div>
        )}

        {!showForgotPassword ? (
          // Giriş Formu
          <form onSubmit={handleSubmit}>
            <div className="mb-4">
              <label htmlFor="email" className="block text-gray-700 text-sm font-bold mb-2">
                E-posta
              </label>
              <input
                id="email"
                type="email"
                className="input-field"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
              />
            </div>

            <div className="mb-6">
              <label htmlFor="password" className="block text-gray-700 text-sm font-bold mb-2">
                Şifre
              </label>
              <input
                id="password"
                type="password"
                className="input-field"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
              />
            </div>

            <div className="flex items-center justify-between mb-6">
              <div className="flex items-center">
                <input
                  id="remember"
                  type="checkbox"
                  className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                  checked={rememberMe}
                  onChange={(e) => setRememberMe(e.target.checked)}
                />
                <label htmlFor="remember" className="ml-2 block text-sm text-gray-700">
                  Beni hatırla
                </label>
              </div>

              <button 
                type="button" 
                className="text-sm text-primary hover:text-secondary cursor-pointer"
                onClick={() => setShowForgotPassword(true)}
              >
                Şifremi unuttum
              </button>
            </div>

            <button
              type="submit"
              className="w-full btn btn-primary"
              disabled={loading}
            >
              {loading ? 'Giriş yapılıyor...' : 'Giriş Yap'}
            </button>
          </form>
        ) : (
          // Şifre Sıfırlama Formu
          <form onSubmit={handleResetPassword}>
            <div className="mb-6">
              <label htmlFor="resetEmail" className="block text-gray-700 text-sm font-bold mb-2">
                E-posta
              </label>
              <input
                id="resetEmail"
                type="email"
                className="input-field"
                value={resetEmail}
                onChange={(e) => setResetEmail(e.target.value)}
                required
                placeholder="Kayıtlı e-posta adresinizi girin"
              />
            </div>

            <div className="flex space-x-2">
              <button
                type="button"
                className="w-1/2 btn btn-secondary"
                onClick={() => {
                  setShowForgotPassword(false);
                  setError('');
                  setSuccess('');
                }}
              >
                Geri Dön
              </button>
              <button
                type="submit"
                className="w-1/2 btn btn-primary"
                disabled={loading}
              >
                {loading ? 'Gönderiliyor...' : 'Şifremi Sıfırla'}
              </button>
            </div>
          </form>
        )}
      </div>
    </div>
  );
} 