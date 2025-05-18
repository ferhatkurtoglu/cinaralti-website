'use client';

import ImageUploader from '@/components/ImageUploader';
import { useEffect, useRef, useState } from 'react';
import { FaArrowDown, FaArrowUp, FaEdit, FaPlus, FaSearch, FaTrash } from 'react-icons/fa';

// Özel scrollbar stili
const scrollbarStyles = `
  .custom-scrollbar::-webkit-scrollbar {
    width: 8px;
  }
  
  .custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }
  
  .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #a0aec0;
    border-radius: 4px;
  }
  
  .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #718096;
  }
  
  @keyframes pulseScrollbar {
    0% { box-shadow: 0 0 0 0px rgba(59, 130, 246, 0.5); }
    100% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
  }
  
  .highlight-scrollbar::-webkit-scrollbar-thumb {
    background: #3b82f6;
    animation: pulseScrollbar 2s infinite;
  }
`;

interface Category {
  id: number;
  name: string;
  slug: string;
}

interface DonationType {
  id: number;
  title: string;
  slug: string;
  description: string;
  categories: Category[];
  active: boolean;
  category_id?: number;
  target_amount?: number;
  collected_amount?: number;
  position?: number;
  cover_image?: string;
  gallery_images?: string[];
}

export default function DonationOptions() {
  const [donationTypes, setDonationTypes] = useState<DonationType[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [showModal, setShowModal] = useState<boolean>(false);
  const [modalMode, setModalMode] = useState<'add' | 'edit'>('add');
  const [currentDonationType, setCurrentDonationType] = useState<DonationType | null>(null);
  const [search, setSearch] = useState<string>('');
  const [categories, setCategories] = useState<Category[]>([]);
  const [coverImage, setCoverImage] = useState<string>('');
  const [galleryImages, setGalleryImages] = useState<string[]>([]);
  const [highlightScrollbar, setHighlightScrollbar] = useState<boolean>(false);
  const [apiError, setApiError] = useState<string | null>(null);
  const formContentRef = useRef<HTMLDivElement>(null);
  const [submitting, setSubmitting] = useState<boolean>(false);

  useEffect(() => {
    // API'den bağış seçeneklerini ve kategorileri çek
    const fetchData = async () => {
      try {
        setLoading(true);
        
        // Bağış seçeneklerini çek
        const donationResponse = await fetch('/api/donation-types');
        
        if (!donationResponse.ok) {
          throw new Error('Bağış seçenekleri alınamadı');
        }
        
        const donationData = await donationResponse.json();
        // API'den gelen verilerde categories dizisi olmayabilir, kontrol edelim
        const processedData = donationData.map((item: any) => ({
          ...item,
          categories: item.categories || [] // Eğer categories yoksa boş dizi atayalım
        }));
        setDonationTypes(processedData);
        
        // Kategorileri çek
        const categoriesResponse = await fetch('/api/categories');
        
        if (!categoriesResponse.ok) {
          throw new Error('Kategoriler alınamadı');
        }
        
        const categoriesData = await categoriesResponse.json();
        setCategories(categoriesData);
        
        setApiError(null);
      } catch (error) {
        console.error('Veriler yüklenirken hata:', error);
        setApiError('Veriler yüklenirken bir hata oluştu. Lütfen sayfayı yenileyip tekrar deneyin.');
        // Hata durumunda boş dizi göster
        setDonationTypes([]);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  const handleSearch = (e: React.ChangeEvent<HTMLInputElement>) => {
    setSearch(e.target.value);
  };

  const filteredDonationTypes = donationTypes.filter((type: DonationType) =>
    type.title.toLowerCase().includes(search.toLowerCase()) ||
    type.description.toLowerCase().includes(search.toLowerCase())
  );

  const handleEdit = (donationType: DonationType) => {
    setCurrentDonationType(donationType);
    setModalMode('edit');
    setShowModal(true);
    setCoverImage(donationType.cover_image || '');
    setGalleryImages(Array.isArray(donationType.gallery_images) ? donationType.gallery_images : []);
    setHighlightScrollbar(true);
    
    // Scrollbar vurgulamasını 3 saniye sonra kapat
    setTimeout(() => {
      setHighlightScrollbar(false);
    }, 3000);
    
    // Modal açıldıktan sonra çok kısa bir süre bekleyip kaydırmayı göster
    setTimeout(() => {
      formContentRef.current?.scrollBy({
        top: 50,
        behavior: 'smooth'
      });
      // 1 saniye sonra tekrar yukarı kaydır
      setTimeout(() => {
        formContentRef.current?.scrollBy({
          top: -50,
          behavior: 'smooth'
        });
      }, 1000);
    }, 500);
  };

  const handleAdd = () => {
    setCurrentDonationType(null);
    setModalMode('add');
    setShowModal(true);
    setCoverImage('');
    setGalleryImages([]);
    setHighlightScrollbar(true);
    
    // Scrollbar vurgulamasını 3 saniye sonra kapat
    setTimeout(() => {
      setHighlightScrollbar(false);
    }, 3000);
    
    // Modal açıldıktan sonra çok kısa bir süre bekleyip kaydırmayı göster
    setTimeout(() => {
      formContentRef.current?.scrollBy({
        top: 50,
        behavior: 'smooth'
      });
      // 1 saniye sonra tekrar yukarı kaydır
      setTimeout(() => {
        formContentRef.current?.scrollBy({
          top: -50,
          behavior: 'smooth'
        });
      }, 1000);
    }, 500);
  };

  const handleDelete = async (id: number) => {
    if (confirm('Bu bağış seçeneğini silmek istediğinizden emin misiniz?')) {
      try {
        const response = await fetch(`/api/donation-types/${id}`, {
          method: 'DELETE',
        });
        
        if (!response.ok) {
          throw new Error('Silme işlemi başarısız oldu');
        }
        
        // UI'dan sil
        setDonationTypes(donationTypes.filter((option: DonationType) => option.id !== id));
        alert('Bağış seçeneği başarıyla silindi');
      } catch (error) {
        console.error('Bağış seçeneği silinirken hata:', error);
        alert('Bağış seçeneği silinirken bir hata oluştu. Lütfen tekrar deneyin.');
      }
    }
  };

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    
    try {
      setSubmitting(true);
      
      const form = e.currentTarget;
      const title = (form.elements.namedItem('title') as HTMLInputElement).value;
      const description = (form.elements.namedItem('description') as HTMLTextAreaElement).value;
      const active = (form.elements.namedItem('active') as HTMLInputElement).checked;
      const targetAmount = (form.elements.namedItem('target_amount') as HTMLInputElement).value;
      const collectedAmount = (form.elements.namedItem('collected_amount') as HTMLInputElement).value;
      const position = (form.elements.namedItem('position') as HTMLInputElement).value;
      
      // Seçilen kategorileri al
      const selectedCategoryIds: number[] = [];
      const selectedCategories: Category[] = [];
      
      document.querySelectorAll('input[name="categories"]:checked').forEach((checkbox) => {
        const id = parseInt((checkbox as HTMLInputElement).value);
        selectedCategoryIds.push(id);
        
        const category = categories.find(c => c.id === id);
        if (category) {
          selectedCategories.push(category);
        }
      });
      
      try {
        if (modalMode === 'add') {
          // Yeni bağış seçeneği için API isteği
          const newTypeData = {
            title,
            slug: title.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, ''),
            description,
            categories: selectedCategoryIds,
            active,
            category_id: selectedCategoryIds.length > 0 ? selectedCategoryIds[0] : null,
            target_amount: parseFloat(targetAmount) || 0,
            collected_amount: parseFloat(collectedAmount) || 0,
            position: parseInt(position) || 0,
            cover_image: coverImage,
            gallery_images: galleryImages
          };
          
          console.log('Gönderilen veri:', newTypeData);
          
          const response = await fetch('/api/donation-types', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(newTypeData),
          });
          
          if (!response.ok) {
            const errorData = await response.json().catch(() => null);
            console.error('API hata yanıtı:', errorData);
            throw new Error(`Bağış seçeneği eklenirken bir hata oluştu: ${errorData?.error || response.statusText}`);
          }
          
          const addedType = await response.json();
          
          // UI'ı güncelle
          setDonationTypes([...donationTypes, {
            ...addedType, 
            categories: selectedCategories
          }]);
          alert('Yeni bağış seçeneği başarıyla eklendi');
        } else if (modalMode === 'edit' && currentDonationType) {
          // Mevcut bağış seçeneğini güncelle
          const updatedTypeData = {
            title,
            slug: title.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, ''),
            description,
            categories: selectedCategoryIds,
            active,
            category_id: selectedCategoryIds.length > 0 ? selectedCategoryIds[0] : null,
            target_amount: parseFloat(targetAmount) || 0,
            collected_amount: parseFloat(collectedAmount) || 0,
            position: parseInt(position) || 0,
            cover_image: coverImage,
            gallery_images: galleryImages
          };
          
          console.log('Güncellenen veri:', updatedTypeData);
          
          const response = await fetch(`/api/donation-types/${currentDonationType.id}`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(updatedTypeData),
          });
          
          if (!response.ok) {
            const errorData = await response.json().catch(() => null);
            console.error('API hata yanıtı:', errorData);
            throw new Error(`Bağış seçeneği güncellenirken bir hata oluştu: ${errorData?.error || response.statusText}`);
          }
          
          const updatedType = await response.json();
          
          // UI'ı güncelle
          setDonationTypes(donationTypes.map((type: DonationType) => 
            type.id === currentDonationType.id ? {
              ...updatedType, 
              categories: selectedCategories
            } : type
          ));
          alert('Bağış seçeneği başarıyla güncellendi');
        }
        
        // Formu kapat
        setShowModal(false);
      } catch (error) {
        console.error('API hatası:', error);
        alert('İşlem sırasında bir hata oluştu. Lütfen tekrar deneyin.');
      }
    } catch (error) {
      console.error('Form gönderme hatası:', error);
      alert('Form gönderilirken bir hata oluştu. Lütfen tekrar deneyin.');
    } finally {
      setSubmitting(false);
    }
  };

  // Galeri görsellerini ekle/kaldır
  const handleAddGalleryImage = (imageUrl: string) => {
    if (imageUrl) {
      setGalleryImages([...galleryImages, imageUrl]);
    }
  };

  const handleRemoveGalleryImage = (index: number) => {
    setGalleryImages(galleryImages.filter((_, i) => i !== index));
  };

  // Benzersiz klasörleri çıkar
  const folders = Array.from(new Set(galleryImages.map(img => img.split('/').slice(0, -1).join('/'))));

  return (
    <div>
      <style jsx>{scrollbarStyles}</style>
      
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-bold text-gray-800">Bağış Seçenekleri</h1>
        <button className="btn btn-primary" onClick={handleAdd}>
          <FaPlus className="mr-2" /> Yeni Ekle
        </button>
      </div>

      {apiError && (
        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          <p>{apiError}</p>
        </div>
      )}

      <div className="card">
        <div className="p-4 border-b border-gray-200">
          <div className="flex justify-between items-center">
            <h2 className="text-lg font-semibold">Tüm Bağış Seçenekleri</h2>
            <div className="relative">
              <input
                type="text"
                className="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                placeholder="Ara..."
                value={search}
                onChange={handleSearch}
              />
              <FaSearch className="absolute left-3 top-3 text-gray-400" />
            </div>
          </div>
        </div>

        {loading ? (
          <div className="p-8 text-center">
            <div className="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-primary border-r-transparent"></div>
            <p className="mt-2 text-gray-600">Yükleniyor...</p>
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Bağış Seçeneği
                  </th>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Kategori
                  </th>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Hedef / Toplanan
                  </th>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Pozisyon
                  </th>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Durum
                  </th>
                  <th scope="col" className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    İşlemler
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {filteredDonationTypes.map((donationType) => (
                  <tr key={donationType.id} className="hover:bg-gray-50">
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="flex items-center">
                        <div className="ml-4">
                          <div className="text-sm font-medium text-gray-900">{donationType.title}</div>
                          <div className="text-sm text-gray-500">{donationType.slug}</div>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-900">
                        {categories.find(c => c.id === donationType.category_id)?.name || '-'}
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-900">
                        {donationType.target_amount ? (
                          <>
                            <span>{donationType.collected_amount || 0} / {donationType.target_amount}</span>
                            <div className="w-full bg-gray-200 rounded-full h-2.5 mt-1">
                              <div 
                                className="bg-blue-600 h-2.5 rounded-full" 
                                style={{ 
                                  width: `${Math.min(100, ((donationType.collected_amount || 0) / donationType.target_amount) * 100)}%` 
                                }}
                              ></div>
                            </div>
                          </>
                        ) : (
                          '-'
                        )}
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-900">
                        {donationType.position || 0}
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        donationType.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                      }`}>
                        {donationType.active ? 'Aktif' : 'Pasif'}
                      </span>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button
                        onClick={() => handleEdit(donationType)}
                        className="text-indigo-600 hover:text-indigo-900 mr-3"
                        aria-label={`${donationType.title} düzenle`}
                      >
                        <FaEdit />
                      </button>
                      <button
                        onClick={() => handleDelete(donationType.id)}
                        className="text-red-600 hover:text-red-900"
                        aria-label={`${donationType.title} sil`}
                      >
                        <FaTrash />
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        {filteredDonationTypes.length === 0 && !loading && (
          <div className="p-8 text-center">
            <p className="text-gray-500">Bağış seçeneği bulunamadı</p>
          </div>
        )}
      </div>

      {/* Bağış Seçeneği Ekleme/Düzenleme Modal */}
      {showModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-lg shadow-xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[80vh]">
            <div className="px-6 py-4 border-b border-gray-200 flex justify-between items-center shrink-0">
              <h3 className="text-lg font-semibold text-gray-900">
                {modalMode === 'add' ? 'Yeni Bağış Seçeneği Ekle' : 'Bağış Seçeneğini Düzenle'}
              </h3>
              <button 
                onClick={() => setShowModal(false)}
                className="text-gray-400 hover:text-gray-600 focus:outline-none"
                aria-label="Kapat"
              >
                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            
            <form onSubmit={handleSubmit} className="flex flex-col flex-1 overflow-hidden">
              <div 
                ref={formContentRef}
                className={`p-6 overflow-y-auto flex-1 custom-scrollbar ${highlightScrollbar ? 'highlight-scrollbar' : ''}`} 
                style={{ 
                  scrollbarWidth: 'thin', 
                  scrollbarColor: highlightScrollbar ? '#3b82f6 #edf2f7' : '#a0aec0 #edf2f7',
                  WebkitOverflowScrolling: 'touch'
                }}
              >
                <div className="mb-4 bg-blue-50 p-3 rounded-md text-sm text-blue-700 flex flex-col items-center">
                  <div className="flex items-center mb-2">
                    <FaArrowDown className="animate-bounce h-5 w-5 mr-2" />
                    <span className="font-medium">Aşağıda daha fazla alan var</span>
                  </div>
                  <p className="text-xs text-blue-600">Form ekrandan taşabilir, aşağı kaydırarak tüm alanları görebilirsiniz</p>
                </div>

                <div id="form-top"></div>

                <div className="mb-4">
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Bağış Seçeneği Adı
                  </label>
                  <input
                    type="text"
                    name="title"
                    className="input-field"
                    placeholder="Bağış seçeneği adı"
                    defaultValue={currentDonationType?.title || ''}
                    required
                  />
                </div>



                <div className="mb-4">
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Hedef Miktar
                  </label>
                  <input
                    type="number"
                    name="target_amount"
                    className="input-field"
                    placeholder="Hedef miktar"
                    defaultValue={currentDonationType?.target_amount || 0}
                    step="0.01"
                    min="0"
                  />
                </div>

                <div className="mb-4">
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Toplanan Miktar
                  </label>
                  <input
                    type="number"
                    name="collected_amount"
                    className="input-field"
                    placeholder="Toplanan miktar"
                    defaultValue={currentDonationType?.collected_amount || 0}
                    step="0.01"
                    min="0"
                  />
                </div>

                <div className="mb-4">
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Sıralama Pozisyonu
                  </label>
                  <input
                    type="number"
                    name="position"
                    className="input-field"
                    placeholder="Sıralama pozisyonu"
                    defaultValue={currentDonationType?.position || 0}
                    min="0"
                  />
                </div>
                
                <div className="mb-4">
                  <label className="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                  <textarea
                    name="description"
                    className="input-field"
                    placeholder="Bağış seçeneği açıklaması"
                    defaultValue={currentDonationType?.description || ''}
                    rows={3}
                    required
                  ></textarea>
                </div>
                
                <div className="mb-4">
                  <label className="block text-sm font-medium text-gray-700 mb-1">Kapak Görseli</label>
                  <ImageUploader 
                    onImageUpload={(url) => setCoverImage(url)}
                    defaultImage={currentDonationType?.cover_image || ''}
                    label="Kapak Görseli Seç"
                    isCoverImage={true}
                  />
                </div>
                
                <div className="mb-4">
                  <label className="block text-sm font-medium text-gray-700 mb-1">Bağış Görselleri</label>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 my-3">
                    {galleryImages.map((image, index) => (
                      <div key={index} className="relative border rounded-lg overflow-hidden">
                        <img 
                          src={image}
                          alt={`Bağış Görseli ${index + 1}`} 
                          className="w-full h-32 object-cover"
                        />
                        <button
                          type="button"
                          onClick={() => handleRemoveGalleryImage(index)}
                          className="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 focus:outline-none"
                          title="Görseli Kaldır"
                          aria-label="Görseli Kaldır"
                        >
                          <FaTrash />
                        </button>
                      </div>
                    ))}
                    
                    <div className="border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center p-4 hover:bg-gray-50">
                      <ImageUploader 
                        onImageUpload={handleAddGalleryImage}
                        label="Görsel Ekle"
                        isCoverImage={false}
                      />
                    </div>
                  </div>
                </div>
                
                <div className="mb-4">
                  <label className="block text-sm font-medium text-gray-700 mb-1">Kategoriler</label>
                  <div className="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    {categories.map(category => (
                      <div key={category.id} className="flex items-center">
                        <input
                          type="checkbox"
                          id={`category-${category.id}`}
                          name="categories"
                          value={category.id}
                          defaultChecked={currentDonationType?.categories && currentDonationType.categories.some(c => c.id === category.id)}
                          className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                        />
                        <label htmlFor={`category-${category.id}`} className="ml-2 block text-sm text-gray-900">
                          {category.name}
                        </label>
                      </div>
                    ))}
                  </div>
                  <div className="mt-3 flex items-center space-x-2">
                    <input
                      type="text"
                      id="new-category"
                      name="new-category"
                      placeholder="Yeni kategori adı"
                      className="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                    />
                    <button
                      type="button"
                      onClick={async () => {
                        const input = document.getElementById('new-category') as HTMLInputElement;
                        if (input.value.trim()) {
                          try {
                            const newCategoryName = input.value.trim();
                            const newCategorySlug = newCategoryName.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
                            
                            // API'ye yeni kategori ekle
                            const response = await fetch('/api/categories', {
                              method: 'POST',
                              headers: {
                                'Content-Type': 'application/json',
                              },
                              body: JSON.stringify({
                                name: newCategoryName,
                                slug: newCategorySlug
                              }),
                            });
                            
                            if (!response.ok) {
                              throw new Error('Kategori eklenirken bir hata oluştu');
                            }
                            
                            const newCategory = await response.json();
                            
                            // UI'ı güncelle
                            setCategories([...categories, {
                              id: newCategory.insertId || Math.max(...categories.map((c: Category) => c.id), 0) + 1,
                              name: newCategoryName,
                              slug: newCategorySlug
                            }]);
                            
                            input.value = '';
                          } catch (error) {
                            console.error('Kategori eklenirken hata:', error);
                            alert('Kategori eklenirken bir hata oluştu. Lütfen tekrar deneyin.');
                          }
                        }
                      }}
                      className="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                    >
                      Ekle
                    </button>
                  </div>
                </div>
                
                <div className="flex items-center mb-6">
                  <input
                    type="checkbox"
                    id="active"
                    name="active"
                    defaultChecked={currentDonationType?.active ?? true}
                    className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                  />
                  <label htmlFor="active" className="ml-2 block text-sm text-gray-900">
                    Aktif
                  </label>
                </div>

                <div className="flex justify-center mt-3 mb-2">
                  <button 
                    type="button" 
                    className="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none flex items-center"
                    onClick={() => {
                      document.getElementById('form-top')?.scrollIntoView({ behavior: 'smooth' });
                    }}
                  >
                    <FaArrowUp className="mr-2" />
                    Yukarı Çık
                  </button>
                </div>
              </div>
              
              <div className="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end shrink-0">
                <button
                  type="button"
                  className="px-4 py-2 text-sm font-medium text-white bg-black border border-black rounded-md shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 mr-2"
                  onClick={() => setShowModal(false)}
                >
                  İptal
                </button>
                <button
                  type="submit"
                  className="btn btn-primary"
                >
                  Kaydet
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
} 