'use client';

import { useEffect, useState } from 'react';
import { FaCamera, FaEye, FaEyeSlash, FaLock, FaSave, FaSpinner, FaUser } from 'react-icons/fa';

export default function ProfilePage() {
  const [isLoading, setIsLoading] = useState(true);
  const [isSaving, setIsSaving] = useState(false);
  const [isChangingPassword, setIsChangingPassword] = useState(false);
  const [successMessage, setSuccessMessage] = useState('');
  const [errorMessage, setErrorMessage] = useState('');
  const [passwordSuccess, setPasswordSuccess] = useState('');
  const [passwordError, setPasswordError] = useState('');
  const [showPasswords, setShowPasswords] = useState({
    current: false,
    new: false,
    confirm: false
  });
  
  const [profileData, setProfileData] = useState({
    name: '',
    email: '',
    role: '',
    phone: '',
    imageUrl: '',
    createdAt: '',
    lastLogin: ''
  });

  const [passwordData, setPasswordData] = useState({
    currentPassword: '',
    newPassword: '',
    confirmPassword: ''
  });

  useEffect(() => {
    // Normalde API'den veri çekilir, burada simüle ediyoruz
    setTimeout(() => {
      setProfileData({
        name: 'Admin Kullanıcı',
        email: 'admin@cinaralti.org',
        role: 'Yönetici',
        phone: '+90 555 123 4567',
        imageUrl: '',
        createdAt: '01.01.2023',
        lastLogin: '01.06.2024 13:45'
      });
      setIsLoading(false);
    }, 1000);
  }, []);

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setProfileData({
      ...profileData,
      [name]: value
    });
  };

  const handlePasswordChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setPasswordData({
      ...passwordData,
      [name]: value
    });
  };

  const togglePasswordVisibility = (field: 'current' | 'new' | 'confirm') => {
    setShowPasswords({
      ...showPasswords,
      [field]: !showPasswords[field]
    });
  };

  const handleSaveProfile = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSaving(true);
    setSuccessMessage('');
    setErrorMessage('');

    try {
      // Burada normalde API çağrısı yapılır
      await new Promise(resolve => setTimeout(resolve, 1500));
      
      // Başarılı kaydetme mesajı
      setSuccessMessage('Profil bilgileri başarıyla güncellendi.');
    } catch (error) {
      // Hata durumunda
      setErrorMessage('Profil bilgileri güncellenirken bir hata oluştu.');
      console.error('Profil güncelleme hatası:', error);
    } finally {
      setIsSaving(false);
    }
  };

  const handleChangePassword = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsChangingPassword(true);
    setPasswordSuccess('');
    setPasswordError('');

    // Şifre doğrulama kontrolü
    if (passwordData.newPassword.length < 8) {
      setPasswordError('Yeni şifre en az 8 karakter olmalıdır.');
      setIsChangingPassword(false);
      return;
    }

    if (passwordData.newPassword !== passwordData.confirmPassword) {
      setPasswordError('Yeni şifre ve şifre tekrarı eşleşmiyor.');
      setIsChangingPassword(false);
      return;
    }

    try {
      // Burada normalde API çağrısı yapılır
      await new Promise(resolve => setTimeout(resolve, 1500));
      
      // Başarılı şifre değiştirme mesajı
      setPasswordSuccess('Şifreniz başarıyla değiştirildi.');
      
      // Formları temizle
      setPasswordData({
        currentPassword: '',
        newPassword: '',
        confirmPassword: ''
      });
    } catch (error) {
      // Hata durumunda
      setPasswordError('Şifre değiştirilirken bir hata oluştu.');
      console.error('Şifre değiştirme hatası:', error);
    } finally {
      setIsChangingPassword(false);
    }
  };

  const handleImageUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      // Gerçek uygulamada burada resim yükleme API çağrısı yapılır
      const reader = new FileReader();
      reader.onloadend = () => {
        setProfileData({
          ...profileData,
          imageUrl: reader.result as string
        });
      };
      reader.readAsDataURL(file);
    }
  };

  if (isLoading) {
    return (
      <div className="flex justify-center items-center h-[calc(100vh-200px)]">
        <FaSpinner className="animate-spin h-8 w-8 text-primary" />
      </div>
    );
  }

  return (
    <div className="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
      <div className="bg-white rounded-lg shadow-md overflow-hidden mb-4 sm:mb-8">
        <div className="p-4 sm:p-6 bg-primary text-white">
          <h1 className="text-xl sm:text-2xl font-bold">Profil Bilgileri</h1>
          <p className="text-xs sm:text-sm opacity-80">Kişisel bilgilerinizi görüntüleyin ve düzenleyin</p>
        </div>

        {successMessage && (
          <div className="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 sm:p-4 mb-4 mx-3 sm:mx-6 mt-3 sm:mt-6">
            <p className="text-sm">{successMessage}</p>
          </div>
        )}

        {errorMessage && (
          <div className="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 sm:p-4 mb-4 mx-3 sm:mx-6 mt-3 sm:mt-6">
            <p className="text-sm">{errorMessage}</p>
          </div>
        )}

        <form onSubmit={handleSaveProfile} className="p-4 sm:p-6">
          <div className="flex flex-col md:flex-row gap-6 md:gap-8">
            {/* Profil Resmi */}
            <div className="flex flex-col items-center mx-auto md:mx-0">
              <div className="relative h-32 w-32 sm:h-40 sm:w-40 mb-4">
                {profileData.imageUrl ? (
                  <img 
                    src={profileData.imageUrl} 
                    alt="Profil Resmi" 
                    className="h-full w-full object-cover rounded-full"
                  />
                ) : (
                  <div className="h-full w-full bg-gray-200 rounded-full flex items-center justify-center">
                    <FaUser className="h-16 w-16 sm:h-20 sm:w-20 text-gray-400" />
                  </div>
                )}
                <label 
                  htmlFor="profile-image" 
                  className="absolute bottom-0 right-0 bg-primary text-white p-2 rounded-full cursor-pointer"
                >
                  <FaCamera className="h-4 w-4" />
                  <span className="sr-only">Profil Resmi Yükle</span>
                </label>
                <input 
                  id="profile-image" 
                  type="file" 
                  className="hidden" 
                  accept="image/*"
                  onChange={handleImageUpload}
                  aria-label="Profil Resmi Yükle"
                />
              </div>
              <div className="text-center">
                <h3 className="text-lg font-semibold">{profileData.name}</h3>
                <p className="text-gray-500 text-sm">{profileData.role}</p>
              </div>
            </div>

            {/* Form Alanları */}
            <div className="flex-1 form-grid">
              <div className="form-group">
                <label htmlFor="input-name" className="form-label">Ad Soyad</label>
                <input
                  id="input-name"
                  type="text"
                  name="name"
                  value={profileData.name}
                  onChange={handleInputChange}
                  className="input-field"
                  aria-label="Ad Soyad"
                />
              </div>

              <div className="form-group">
                <label htmlFor="input-email" className="form-label">E-posta</label>
                <input
                  id="input-email"
                  type="email"
                  name="email"
                  value={profileData.email}
                  onChange={handleInputChange}
                  className="input-field"
                  aria-label="E-posta"
                />
              </div>

              <div className="form-group">
                <label htmlFor="input-phone" className="form-label">Telefon</label>
                <input
                  id="input-phone"
                  type="tel"
                  name="phone"
                  value={profileData.phone}
                  onChange={handleInputChange}
                  className="input-field"
                  aria-label="Telefon"
                />
              </div>

              <div className="form-group">
                <label htmlFor="input-role" className="form-label">Yetki</label>
                <input
                  id="input-role"
                  type="text"
                  name="role"
                  value={profileData.role}
                  readOnly
                  className="input-field bg-gray-50"
                  aria-label="Yetki"
                />
              </div>

              <div className="form-group">
                <label htmlFor="input-created-at" className="form-label">Hesap Oluşturulma Tarihi</label>
                <input
                  id="input-created-at"
                  type="text"
                  value={profileData.createdAt}
                  readOnly
                  className="input-field bg-gray-50"
                  aria-label="Hesap Oluşturulma Tarihi"
                />
              </div>

              <div className="form-group">
                <label htmlFor="input-last-login" className="form-label">Son Giriş</label>
                <input
                  id="input-last-login"
                  type="text"
                  value={profileData.lastLogin}
                  readOnly
                  className="input-field bg-gray-50"
                  aria-label="Son Giriş"
                />
              </div>
            </div>
          </div>

          <hr className="my-6" />

          <div className="mt-4 flex justify-end">
            <button
              type="submit"
              disabled={isSaving}
              className="btn btn-primary flex items-center"
              aria-label="Değişiklikleri Kaydet"
            >
              {isSaving ? (
                <>
                  <FaSpinner className="animate-spin mr-2" />
                  <span>Kaydediliyor...</span>
                </>
              ) : (
                <>
                  <FaSave className="mr-2" />
                  <span>Değişiklikleri Kaydet</span>
                </>
              )}
            </button>
          </div>
        </form>
      </div>

      {/* Şifre Değiştirme Bölümü */}
      <div className="bg-white rounded-lg shadow-md overflow-hidden">
        <div className="p-4 sm:p-6 bg-primary text-white">
          <h2 className="text-xl sm:text-2xl font-bold">Şifre Değiştir</h2>
          <p className="text-xs sm:text-sm opacity-80">Hesap güvenliğiniz için şifrenizi düzenli olarak değiştirin</p>
        </div>

        {passwordSuccess && (
          <div className="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 sm:p-4 mb-4 mx-3 sm:mx-6 mt-3 sm:mt-6">
            <p className="text-sm">{passwordSuccess}</p>
          </div>
        )}

        {passwordError && (
          <div className="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 sm:p-4 mb-4 mx-3 sm:mx-6 mt-3 sm:mt-6">
            <p className="text-sm">{passwordError}</p>
          </div>
        )}

        <form onSubmit={handleChangePassword} className="p-4 sm:p-6">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <div className="md:col-span-2">
              <label htmlFor="input-current-password" className="form-label">Mevcut Şifre</label>
              <div className="relative">
                <input
                  id="input-current-password"
                  type={showPasswords.current ? "text" : "password"}
                  name="currentPassword"
                  value={passwordData.currentPassword}
                  onChange={handlePasswordChange}
                  className="input-field pr-10"
                  aria-label="Mevcut Şifre"
                />
                <button
                  type="button"
                  className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                  onClick={() => togglePasswordVisibility('current')}
                  aria-label={showPasswords.current ? "Şifreyi Gizle" : "Şifreyi Göster"}
                >
                  {showPasswords.current ? <FaEyeSlash /> : <FaEye />}
                </button>
              </div>
            </div>

            <div className="form-group">
              <label htmlFor="input-new-password" className="form-label">Yeni Şifre</label>
              <div className="relative">
                <input
                  id="input-new-password"
                  type={showPasswords.new ? "text" : "password"}
                  name="newPassword"
                  value={passwordData.newPassword}
                  onChange={handlePasswordChange}
                  className="input-field pr-10"
                  aria-label="Yeni Şifre"
                />
                <button
                  type="button"
                  className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                  onClick={() => togglePasswordVisibility('new')}
                  aria-label={showPasswords.new ? "Şifreyi Gizle" : "Şifreyi Göster"}
                >
                  {showPasswords.new ? <FaEyeSlash /> : <FaEye />}
                </button>
              </div>
              <p className="text-xs text-gray-500 mt-1">Şifreniz en az 8 karakter uzunluğunda olmalıdır.</p>
            </div>

            <div className="form-group">
              <label htmlFor="input-confirm-password" className="form-label">Yeni Şifre (Tekrar)</label>
              <div className="relative">
                <input
                  id="input-confirm-password"
                  type={showPasswords.confirm ? "text" : "password"}
                  name="confirmPassword"
                  value={passwordData.confirmPassword}
                  onChange={handlePasswordChange}
                  className="input-field pr-10"
                  aria-label="Yeni Şifre (Tekrar)"
                />
                <button
                  type="button"
                  className="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                  onClick={() => togglePasswordVisibility('confirm')}
                  aria-label={showPasswords.confirm ? "Şifreyi Gizle" : "Şifreyi Göster"}
                >
                  {showPasswords.confirm ? <FaEyeSlash /> : <FaEye />}
                </button>
              </div>
            </div>
          </div>

          <div className="mt-6 flex justify-end">
            <button
              type="submit"
              disabled={isChangingPassword}
              className="btn btn-primary flex items-center"
              aria-label="Şifreyi Değiştir"
            >
              {isChangingPassword ? (
                <>
                  <FaSpinner className="animate-spin mr-2" />
                  <span>İşleniyor...</span>
                </>
              ) : (
                <>
                  <FaLock className="mr-2" />
                  <span>Şifreyi Değiştir</span>
                </>
              )}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
} 