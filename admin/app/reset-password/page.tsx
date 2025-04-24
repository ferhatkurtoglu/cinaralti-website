'use client';

import Image from 'next/image';
import { useRouter, useSearchParams } from 'next/navigation';
import { useEffect, useState } from 'react';

export default function ResetPasswordPage() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const token = searchParams.get('token');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (!token) {
      setError('Geçersiz veya eksik token');
    }
  }, [token]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setSuccess('');

    // Şifre doğrulama kontrolleri
    if (newPassword.length < 6) {
      setError('Şifre en az 6 karakter olmalıdır');
      return;
    }

    if (newPassword !== confirmPassword) {
      setError('Şifreler eşleşmiyor');
      return;
    }

    setLoading(true);

    try {
      const response = await fetch('/api/auth/update-password', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ token, newPassword })
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.error || 'Şifre güncelleme işlemi başarısız oldu');
      }

      setSuccess('Şifreniz başarıyla güncellendi');
      
      // 3 saniye sonra giriş sayfasına yönlendir
      setTimeout(() => {
        router.push('/');
      }, 3000);
      
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
          <h1 className="text-2xl font-bold text-gray-800">Şifre Sıfırlama</h1>
          <p className="text-gray-600 mt-2">Yeni şifrenizi belirleyin</p>
        </div>

        {error && (
          <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span className="block sm:inline">{error}</span>
          </div>
        )}
        
        {success && (
          <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span className="block sm:inline">{success}</span>
            <p className="mt-2 text-sm">Giriş sayfasına yönlendiriliyorsunuz...</p>
          </div>
        )}

        {!token ? (
          <div className="text-center">
            <p className="text-red-500 mb-4">Geçersiz şifre sıfırlama bağlantısı</p>
            <button
              onClick={() => router.push('/')}
              className="btn btn-primary"
            >
              Giriş Sayfasına Dön
            </button>
          </div>
        ) : (
          <form onSubmit={handleSubmit}>
            <div className="mb-4">
              <label htmlFor="newPassword" className="block text-gray-700 text-sm font-bold mb-2">
                Yeni Şifre
              </label>
              <input
                id="newPassword"
                type="password"
                className="input-field"
                value={newPassword}
                onChange={(e) => setNewPassword(e.target.value)}
                required
                minLength={6}
              />
            </div>

            <div className="mb-6">
              <label htmlFor="confirmPassword" className="block text-gray-700 text-sm font-bold mb-2">
                Şifreyi Onayla
              </label>
              <input
                id="confirmPassword"
                type="password"
                className="input-field"
                value={confirmPassword}
                onChange={(e) => setConfirmPassword(e.target.value)}
                required
              />
            </div>

            <button
              type="submit"
              className="w-full btn btn-primary"
              disabled={loading}
            >
              {loading ? 'İşleniyor...' : 'Şifreyi Güncelle'}
            </button>

            <div className="text-center mt-4">
              <button
                type="button"
                className="text-sm text-primary hover:text-secondary"
                onClick={() => router.push('/')}
              >
                Giriş Sayfasına Dön
              </button>
            </div>
          </form>
        )}
      </div>
    </div>
  );
} 