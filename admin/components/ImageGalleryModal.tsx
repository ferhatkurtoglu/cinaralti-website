'use client';

import { useEffect, useState } from 'react';
import { FaFolder, FaSearch, FaSpinner, FaTimes } from 'react-icons/fa';

interface ImageFile {
  path: string;
  name: string;
  url: string;
  folder: string;
  size: number;
  date: Date;
}

interface ImageGalleryModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSelectImage: (imageUrl: string) => void;
}

export default function ImageGalleryModal({
  isOpen,
  onClose,
  onSelectImage
}: ImageGalleryModalProps) {
  const [images, setImages] = useState<ImageFile[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);
  const [selectedFolder, setSelectedFolder] = useState<string | null>(null);
  const [searchQuery, setSearchQuery] = useState<string>('');

  useEffect(() => {
    if (!isOpen) return;

    const fetchImages = async () => {
      setLoading(true);
      setError(null);

      try {
        const response = await fetch('/api/donation-images');

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.error || 'Görseller yüklenirken bir hata oluştu');
        }

        const data = await response.json();
        if (data.success && data.images) {
          setImages(data.images);
        } else {
          throw new Error('Görseller alınamadı');
        }
      } catch (err) {
        setError(err instanceof Error ? err.message : 'Görseller yüklenirken bir hata oluştu');
        console.error('Görsel listeleme hatası:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchImages();
  }, [isOpen]);

  // Benzersiz klasörleri çıkar
  const folders = Array.from(new Set(images.map(img => img.folder)));

  // Filtrelenmiş görselleri al
  const filteredImages = images.filter(img => {
    const matchesFolder = selectedFolder ? img.folder === selectedFolder : true;
    const matchesSearch = searchQuery
      ? img.name.toLowerCase().includes(searchQuery.toLowerCase())
      : true;
    return matchesFolder && matchesSearch;
  });

  // Dosya boyutunu formatla
  const formatFileSize = (size: number): string => {
    if (size < 1024) return `${size} B`;
    if (size < 1024 * 1024) return `${(size / 1024).toFixed(1)} KB`;
    return `${(size / (1024 * 1024)).toFixed(1)} MB`;
  };

  // Görsel seçme işlemi
  const handleSelectImage = (imageUrl: string) => {
    onSelectImage(imageUrl);
    onClose();
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
      <div className="bg-white rounded-lg shadow-xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[80vh]">
        <div className="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
          <h3 className="text-lg font-semibold text-gray-900">
            Sistem Görsellerinden Seç
          </h3>
          <button
            onClick={onClose}
            className="text-gray-400 hover:text-gray-600 focus:outline-none"
            aria-label="Kapat"
          >
            <FaTimes />
          </button>
        </div>

        <div className="p-4 border-b border-gray-200 flex flex-wrap items-center gap-2">
          <div className="flex-1 min-w-[200px]">
            <div className="relative">
              <input
                type="text"
                placeholder="Görsel ara..."
                className="pl-10 w-full px-4 py-2 border border-gray-300 rounded-md"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
              />
              <FaSearch className="absolute left-3 top-3 text-gray-400" />
            </div>
          </div>

          <div className="flex items-center space-x-2">
            <span className="text-sm text-gray-600">Klasör:</span>
            <select
              className="px-3 py-2 border border-gray-300 rounded-md"
              value={selectedFolder || ''}
              onChange={(e) => setSelectedFolder(e.target.value || null)}
              aria-label="Klasör seçin"
            >
              <option value="">Tüm Klasörler</option>
              {folders.map((folder) => (
                <option key={folder} value={folder}>
                  {folder === 'donate' ? 'Bağış Görselleri' : folder === 'general' ? 'Genel Görseller' : folder}
                </option>
              ))}
            </select>
          </div>
        </div>

        <div className="p-4 overflow-y-auto flex-1">
          {loading ? (
            <div className="flex items-center justify-center h-64">
              <FaSpinner className="animate-spin text-primary text-2xl mr-2" />
              <p>Görseller yükleniyor...</p>
            </div>
          ) : error ? (
            <div className="text-center text-red-500 p-4">{error}</div>
          ) : filteredImages.length === 0 ? (
            <div className="text-center text-gray-500 p-4">
              {searchQuery || selectedFolder
                ? 'Arama kriterlerine uygun görsel bulunamadı.'
                : 'Henüz yüklenmiş görsel bulunmuyor.'}
            </div>
          ) : (
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
              {filteredImages.map((image) => (
                <div
                  key={image.url}
                  className="border rounded-lg overflow-hidden hover:shadow-md cursor-pointer transition-all"
                  onClick={() => handleSelectImage(image.url)}
                >
                  <div className="relative aspect-video">
                    <img
                      src={image.url}
                      alt={image.name}
                      className="w-full h-full object-cover"
                    />
                  </div>
                  <div className="p-2 text-xs">
                    <div className="truncate font-medium">{image.name}</div>
                    <div className="flex justify-between text-gray-500 mt-1">
                      <span className="flex items-center">
                        <FaFolder className="mr-1" /> {image.folder}
                      </span>
                      <span>{formatFileSize(image.size)}</span>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>

        <div className="px-6 py-4 border-t border-gray-200 flex justify-end">
          <button
            type="button"
            onClick={onClose}
            className="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
          >
            İptal
          </button>
        </div>
      </div>
    </div>
  );
} 