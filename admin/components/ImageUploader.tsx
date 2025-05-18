'use client';

import { useRef, useState } from 'react';
import { FaImage, FaServer, FaSpinner, FaTimes, FaUpload } from 'react-icons/fa';
import ImageGalleryModal from './ImageGalleryModal';

interface ImageUploaderProps {
  onImageUpload: (imageUrl: string) => void;
  defaultImage?: string;
  className?: string;
  label?: string;
  isCoverImage?: boolean;
}

export default function ImageUploader({ 
  onImageUpload, 
  defaultImage, 
  className = '', 
  label = 'Görsel Seç',
  isCoverImage = false 
}: ImageUploaderProps) {
  const [uploading, setUploading] = useState(false);
  const [imageUrl, setImageUrl] = useState<string | null>(defaultImage || null);
  const [error, setError] = useState<string | null>(null);
  const [showGalleryModal, setShowGalleryModal] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);

  const handleFileChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    // Resim türü kontrolü
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
    if (!validTypes.includes(file.type)) {
      setError('Lütfen geçerli bir görsel dosyası seçin (JPEG, PNG, WEBP, GIF)');
      return;
    }

    // Dosya boyutu kontrolü (5MB)
    if (file.size > 5 * 1024 * 1024) {
      setError('Dosya boyutu çok büyük. En fazla 5MB olabilir.');
      return;
    }

    setError(null);
    setUploading(true);

    try {
      const formData = new FormData();
      formData.append('file', file);

      const response = await fetch('/api/donation-images', {
        method: 'POST',
        body: formData,
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.error || 'Yükleme sırasında bir hata oluştu');
      }

      const data = await response.json();
      onImageUpload(data.url);
      
      // Kapak görseli değilse state'i temizle
      if (!isCoverImage) {
        setImageUrl(null);
      } else {
        setImageUrl(data.url);
      }
      
      // Input alanını temizle
      if (fileInputRef.current) {
        fileInputRef.current.value = '';
      }
    } catch (error) {
      setError(error instanceof Error ? error.message : 'Görsel yüklenirken bir hata oluştu');
      console.error('Görsel yükleme hatası:', error);
    } finally {
      setUploading(false);
    }
  };

  const handleRemoveImage = async () => {
    if (!imageUrl) return;

    // Sadece API ile yüklenen görseller için silme işlemi yap
    if (imageUrl.startsWith('/assets/image/donate/')) {
      const fileName = imageUrl.split('/').pop();
      
      try {
        const response = await fetch('/api/donation-images', {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ fileName }),
        });

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.error || 'Silme sırasında bir hata oluştu');
        }
      } catch (error) {
        console.error('Görsel silme hatası:', error);
      }
    }

    setImageUrl(null);
    onImageUpload('');
    
    // Dosya seçiciyi temizle
    if (fileInputRef.current) {
      fileInputRef.current.value = '';
    }
  };

  const triggerFileInput = () => {
    if (fileInputRef.current) {
      fileInputRef.current.click();
    }
  };

  const handleSelectServerImage = (url: string) => {
    onImageUpload(url);
    // Kapak görseli değilse state'i temizle
    if (!isCoverImage) {
      setImageUrl(null);
    } else {
      setImageUrl(url);
    }
  };

  return (
    <div className={`image-uploader ${className}`}>
      <label htmlFor="file-upload" className="sr-only">{label}</label>
      <input
        id="file-upload"
        type="file"
        ref={fileInputRef}
        onChange={handleFileChange}
        accept="image/jpeg,image/png,image/webp,image/gif"
        className="hidden"
        aria-label={label}
      />

      {!imageUrl ? (
        <div className="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:bg-gray-50">
          {uploading ? (
            <div className="flex flex-col items-center justify-center py-4">
              <FaSpinner className="animate-spin text-primary text-2xl mb-2" />
              <p className="text-gray-500">Görsel yükleniyor...</p>
            </div>
          ) : (
            <div className="flex flex-col items-center justify-center py-4">
              <FaImage className="text-gray-400 text-4xl mb-2" />
              <p className="text-gray-500 mb-1">{label}</p>
              <p className="text-xs text-gray-400">JPEG, PNG, WEBP, GIF (max. 5MB)</p>
              <div className="flex mt-3 space-x-2">
                <button 
                  onClick={triggerFileInput}
                  className="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                  type="button"
                  aria-label="Bilgisayardan Görsel Yükle"
                >
                  <FaUpload className="mr-1" /> Bilgisayardan Seç
                </button>
                <button 
                  onClick={() => setShowGalleryModal(true)}
                  className="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                  type="button"
                  aria-label="Sistemden Görsel Seç"
                >
                  <FaServer className="mr-1" /> Sistemden Seç
                </button>
              </div>
            </div>
          )}
        </div>
      ) : (
        <div className="relative border rounded-lg overflow-hidden">
          <img 
            src={imageUrl}
            alt="Yüklenen Görsel" 
            className="w-full h-auto max-h-48 object-cover"
          />
          <button
            onClick={handleRemoveImage}
            className="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 focus:outline-none"
            title="Görseli Kaldır"
            type="button"
            aria-label="Görseli Kaldır"
          >
            <FaTimes />
          </button>
        </div>
      )}

      {error && (
        <div className="mt-2 text-sm text-red-500">{error}</div>
      )}
      
      {/* Sistem Görselleri Seçme Modalı */}
      <ImageGalleryModal 
        isOpen={showGalleryModal}
        onClose={() => setShowGalleryModal(false)}
        onSelectImage={handleSelectServerImage}
      />
    </div>
  );
}