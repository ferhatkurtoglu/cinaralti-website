'use client';

import { useEffect, useState } from 'react';
import { FaEdit, FaPlus, FaSearch, FaTrash } from 'react-icons/fa';

interface Category {
  id: number;
  name: string;
  slug: string;
}

interface DonationType {
  id: number;
  name: string;
  slug: string;
  image: string;
  description: string;
  categories: Category[];
  is_active: boolean;
}

export default function DonationTypes() {
  const [donationTypes, setDonationTypes] = useState<DonationType[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [showModal, setShowModal] = useState<boolean>(false);
  const [modalMode, setModalMode] = useState<'add' | 'edit'>('add');
  const [currentDonationType, setCurrentDonationType] = useState<DonationType | null>(null);
  const [search, setSearch] = useState<string>('');
  const [categories, setCategories] = useState<Category[]>([]);

  // Örnek kategoriler
  const exampleCategories: Category[] = [
    { id: 1, name: 'Acil Yardım', slug: 'acil-yardim' },
    { id: 2, name: 'Yetim', slug: 'yetim' },
    { id: 3, name: 'Genel', slug: 'genel' },
    { id: 4, name: 'Projeler', slug: 'projeler' },
    { id: 5, name: 'Eğitim', slug: 'egitim' },
    { id: 6, name: 'Kurban', slug: 'kurban' }
  ];

  // Örnek bağış seçenekleri
  const exampleDonationTypes: DonationType[] = [
    {
      id: 1,
      name: 'Genel Bağış',
      slug: 'genel-bagis',
      image: 'donate1.jpg',
      description: 'Genel amaçlı bağış',
      categories: [exampleCategories[2], exampleCategories[0]],
      is_active: true
    },
    {
      id: 2,
      name: 'Zekat',
      slug: 'zekat',
      image: 'donate1.jpg',
      description: 'Zekat bağışları',
      categories: [exampleCategories[2], exampleCategories[0]],
      is_active: true
    },
    {
      id: 3,
      name: 'Bina Satın Alma',
      slug: 'bina-satin-alma',
      image: 'donate1.jpg',
      description: 'Bina satın alma projesi için bağış',
      categories: [exampleCategories[3], exampleCategories[2]],
      is_active: true
    },
    {
      id: 4,
      name: 'Kuran Talebelerinin İhtiyaçları',
      slug: 'kuran-talebelerinin-ihtiyaclari',
      image: 'donate1.jpg',
      description: 'Kuran talebelerinin eğitim ihtiyaçları için bağış',
      categories: [exampleCategories[4], exampleCategories[2]],
      is_active: true
    },
    {
      id: 5,
      name: 'Afrika Bağışı',
      slug: 'afrika-bagisi',
      image: 'donate1.jpg',
      description: 'Afrika yardım projesi için bağış',
      categories: [exampleCategories[0], exampleCategories[2]],
      is_active: true
    },
    {
      id: 6,
      name: 'Filistin Yardımı',
      slug: 'filistin-yardimi',
      image: 'donate1.jpg',
      description: 'Filistin için acil yardım',
      categories: [exampleCategories[0], exampleCategories[2]],
      is_active: true
    },
    {
      id: 7,
      name: 'Yetim Projesi',
      slug: 'yetim-projesi',
      image: 'donate1.jpg',
      description: 'Yetim çocuklara destek projesi',
      categories: [exampleCategories[1], exampleCategories[2]],
      is_active: true
    },
    {
      id: 8,
      name: 'Kurban Bağışı',
      slug: 'kurban-bagisi',
      image: 'donate1.jpg',
      description: 'Kurban bağışı',
      categories: [exampleCategories[5], exampleCategories[2]],
      is_active: true
    }
  ];

  useEffect(() => {
    // Mock veri yükleme (gerçek uygulamada API'dan çekilecek)
    setTimeout(() => {
      setDonationTypes(exampleDonationTypes);
      setCategories(exampleCategories);
      setLoading(false);
    }, 500);
  }, []);

  const handleSearch = (e: React.ChangeEvent<HTMLInputElement>) => {
    setSearch(e.target.value);
  };

  const filteredDonationTypes = donationTypes.filter((type: DonationType) =>
    type.name.toLowerCase().includes(search.toLowerCase()) ||
    type.description.toLowerCase().includes(search.toLowerCase())
  );

  const handleEdit = (donationType: DonationType) => {
    setCurrentDonationType(donationType);
    setModalMode('edit');
    setShowModal(true);
  };

  const handleAdd = () => {
    setCurrentDonationType(null);
    setModalMode('add');
    setShowModal(true);
  };

  const handleDelete = (id: number) => {
    if (confirm('Bu bağış seçeneğini silmek istediğinizden emin misiniz?')) {
      setDonationTypes(donationTypes.filter((type: DonationType) => type.id !== id));
      alert('Bağış seçeneği silindi');
    }
  };

  const handleSave = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    
    const form = e.currentTarget;
    const name = (form.elements.namedItem('name') as HTMLInputElement).value;
    const description = (form.elements.namedItem('description') as HTMLTextAreaElement).value;
    const isActive = (form.elements.namedItem('is_active') as HTMLInputElement).checked;
    
    // Seçilen kategorileri al
    const selectedCategoryIds = Array.from(form.elements)
      .filter(element => element.nodeName === 'INPUT' && (element as HTMLInputElement).type === 'checkbox' && (element as HTMLInputElement).name.startsWith('category-') && (element as HTMLInputElement).checked)
      .map(element => parseInt((element as HTMLInputElement).value));

    const selectedCategories = categories.filter((category: Category) => selectedCategoryIds.includes(category.id));
    
    if (modalMode === 'add') {
      const newType: DonationType = {
        id: Math.max(...donationTypes.map((t: DonationType) => t.id), 0) + 1,
        name,
        slug: name.toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, ''),
        image: 'donate1.jpg',
        description,
        categories: selectedCategories,
        is_active: isActive
      };
      
      setDonationTypes([...donationTypes, newType]);
      alert('Yeni bağış seçeneği eklendi');
    } else if (modalMode === 'edit' && currentDonationType) {
      const updatedType: DonationType = {
        ...currentDonationType,
        name,
        description,
        categories: selectedCategories,
        is_active: isActive
      };
      
      setDonationTypes(donationTypes.map((type: DonationType) => type.id === updatedType.id ? updatedType : type));
      alert('Bağış seçeneği güncellendi');
    }
    
    setShowModal(false);
  };

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-bold text-gray-800">Bağış Seçenekleri</h1>
        <button className="btn btn-primary" onClick={handleAdd}>
          <FaPlus className="mr-2" /> Yeni Ekle
        </button>
      </div>

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
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adı</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategoriler</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Açıklama</th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                  <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {filteredDonationTypes.map(donationType => (
                  <tr key={donationType.id}>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-900">#{donationType.id}</div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="flex items-center">
                        <div className="flex-shrink-0 h-10 w-10">
                          <img className="h-10 w-10 rounded-md object-cover" src={`/assets/image/donate/${donationType.image}`} alt={donationType.name} />
                        </div>
                        <div className="ml-4">
                          <div className="text-sm font-medium text-gray-900">{donationType.name}</div>
                          <div className="text-sm text-gray-500">{donationType.slug}</div>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="flex flex-wrap gap-1">
                        {donationType.categories.map(category => (
                          <span
                            key={category.id}
                            className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                          >
                            {category.name}
                          </span>
                        ))}
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <div className="text-sm text-gray-900 line-clamp-2">{donationType.description}</div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        donationType.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                      }`}>
                        {donationType.is_active ? 'Aktif' : 'Pasif'}
                      </span>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button
                        onClick={() => handleEdit(donationType)}
                        className="text-indigo-600 hover:text-indigo-900 mr-3"
                        aria-label={`${donationType.name} düzenle`}
                      >
                        <FaEdit />
                      </button>
                      <button
                        onClick={() => handleDelete(donationType.id)}
                        className="text-red-600 hover:text-red-900"
                        aria-label={`${donationType.name} sil`}
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
          <div className="bg-white rounded-lg shadow-xl w-full max-w-2xl overflow-hidden">
            <div className="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
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
            
            <form onSubmit={handleSave}>
              <div className="p-6">
                <div className="mb-4">
                  <label className="block text-sm font-medium text-gray-700 mb-1">Adı</label>
                  <input
                    type="text"
                    name="name"
                    className="input-field"
                    placeholder="Bağış seçeneği adı"
                    defaultValue={currentDonationType?.name || ''}
                    required
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
                  <label className="block text-sm font-medium text-gray-700 mb-1">Kategoriler</label>
                  <div className="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    {categories.map(category => (
                      <div key={category.id} className="flex items-center">
                        <input
                          type="checkbox"
                          id={`category-${category.id}`}
                          name={`category-${category.id}`}
                          value={category.id}
                          defaultChecked={currentDonationType?.categories.some(c => c.id === category.id)}
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
                      onClick={() => {
                        const input = document.getElementById('new-category') as HTMLInputElement;
                        if (input.value.trim()) {
                          const newCategory: Category = {
                            id: Math.max(...categories.map((c: Category) => c.id), 0) + 1,
                            name: input.value.trim(),
                            slug: input.value.trim().toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '')
                          };
                          setCategories([...categories, newCategory]);
                          input.value = '';
                        }
                      }}
                      className="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                    >
                      Ekle
                    </button>
                  </div>
                </div>
                
                <div className="flex items-center">
                  <input
                    type="checkbox"
                    id="is_active"
                    name="is_active"
                    defaultChecked={currentDonationType?.is_active ?? true}
                    className="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                  />
                  <label htmlFor="is_active" className="ml-2 block text-sm text-gray-900">
                    Aktif
                  </label>
                </div>
              </div>
              
              <div className="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
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