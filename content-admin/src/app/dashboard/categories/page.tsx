'use client'

import DashboardLayout from '@/components/layout/DashboardLayout'
import { PencilIcon, PlusIcon, TrashIcon } from '@heroicons/react/24/outline'
import { useSession } from 'next-auth/react'
import { useRouter } from 'next/navigation'
import { useEffect, useState } from 'react'

type Category = {
  id: string
  name: string
  slug: string
  description: string
  type: string
  createdAt: string
  updatedAt: string
}

export default function CategoriesPage() {
  const { data: session, status } = useSession()
  const router = useRouter()
  const [categories, setCategories] = useState<Category[]>([])
  const [isLoading, setIsLoading] = useState(true)
  const [isModalOpen, setIsModalOpen] = useState(false)
  const [editingCategory, setEditingCategory] = useState<Category | null>(null)
  const [categoryName, setCategoryName] = useState('')
  const [categoryDescription, setCategoryDescription] = useState('')
  const [categoryType, setCategoryType] = useState('blog')
  const [filterType, setFilterType] = useState<'all' | 'blog' | 'video'>('all')

  // Kategorileri yeniden yükle fonksiyonu
  const refreshCategories = async () => {
    try {
      setIsLoading(true)
      console.log('Categories yükleniyor...')
      
      // Tüm kategorileri getir
      const [blogResponse, videoResponse] = await Promise.all([
        fetch('/api/categories?type=blog'),
        fetch('/api/categories?type=video')
      ])
      
      console.log('API yanıtları alındı:', {
        blogStatus: blogResponse.status,
        videoStatus: videoResponse.status
      })
      
      if (!blogResponse.ok) {
        throw new Error(`Blog kategorileri yüklenemedi: ${blogResponse.status}`)
      }
      
      if (!videoResponse.ok) {
        throw new Error(`Video kategorileri yüklenemedi: ${videoResponse.status}`)
      }
      
      const blogData = await blogResponse.json()
      const videoData = await videoResponse.json()
      
      console.log('API verileri:', {
        blogCount: Array.isArray(blogData) ? blogData.length : 'Array değil',
        videoCount: Array.isArray(videoData) ? videoData.length : 'Array değil',
        blogData: blogData,
        videoData: videoData
      })
      
      const allCategories = [
        ...(Array.isArray(blogData) ? blogData : []),
        ...(Array.isArray(videoData) ? videoData : [])
      ]
      
      console.log('Toplam kategori sayısı:', allCategories.length)
      setCategories(allCategories)
    } catch (error) {
      console.error('Kategoriler yüklenirken hata oluştu:', error)
      alert('Kategoriler yüklenirken hata oluştu: ' + error.message)
    } finally {
      setIsLoading(false)
    }
  }

  useEffect(() => {
    if (status === 'unauthenticated') {
      router.push('/login')
    }
  }, [status, router])

  useEffect(() => {
    if (status === 'authenticated') {
      console.log('Kullanıcı giriş yapmış, kategoriler yükleniyor...')
      refreshCategories()
    }
  }, [status])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()

    if (!categoryName.trim()) {
      return
    }

    try {
      const response = await fetch(
        editingCategory
          ? `/api/categories/${editingCategory.id}`
          : '/api/categories',
        {
          method: editingCategory ? 'PUT' : 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ 
            name: categoryName,
            description: categoryDescription,
            type: categoryType
          }),
        }
      )

      if (response.ok) {
        // Modal'ı kapat ve formu temizle
        setIsModalOpen(false)
        setEditingCategory(null)
        setCategoryName('')
        setCategoryDescription('')
        setCategoryType('blog')
        
        // Kategori listesini yeniden yükle
        await refreshCategories()
      } else {
        const error = await response.text()
        alert(error)
      }
    } catch (error) {
      console.error('Kategori kaydedilirken hata oluştu:', error)
    }
  }

  const handleDelete = async (id: string) => {
    if (!confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')) {
      return
    }

    try {
      const response = await fetch(`/api/categories/${id}`, {
        method: 'DELETE',
      })

      if (response.ok) {
        // Kategori listesini yeniden yükle
        await refreshCategories()
      } else {
        const error = await response.text()
        alert(error)
      }
    } catch (error) {
      console.error('Kategori silinirken hata oluştu:', error)
    }
  }

  const handleEdit = (category: Category) => {
    setEditingCategory(category)
    setCategoryName(category.name)
    setCategoryDescription(category.description || '')
    setCategoryType(category.type)
    setIsModalOpen(true)
  }

  const handleNewCategory = () => {
    setEditingCategory(null)
    setCategoryName('')
    setCategoryDescription('')
    setCategoryType('blog')
    setIsModalOpen(true)
  }

  // Filtrelenmiş kategoriler
  const filteredCategories = categories.filter(category => {
    if (filterType === 'all') return true
    return category.type === filterType
  })

  const categoryStats = {
    total: categories.length,
    blog: categories.filter(cat => cat.type === 'blog').length,
    video: categories.filter(cat => cat.type === 'video').length,
  }

  if (status === 'loading' || isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
          <p className="mt-4 text-gray-600">Yükleniyor...</p>
        </div>
      </div>
    )
  }

  return (
    <DashboardLayout>
      <div className="py-6">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="sm:flex sm:items-center">
            <div className="sm:flex-auto">
              <h1 className="text-2xl font-semibold text-gray-900">
                Kategoriler
              </h1>
              <p className="mt-2 text-sm text-gray-700">
                Blog yazıları ve videolar için kategoriler
              </p>
            </div>
            <div className="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
              <button
                onClick={handleNewCategory}
                className="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
              >
                <div className="flex items-center">
                  <PlusIcon className="h-5 w-5 mr-2" />
                  Yeni Kategori
                </div>
              </button>
            </div>
          </div>

          {/* İstatistikler */}
          <div className="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div className="bg-white overflow-hidden shadow rounded-lg">
              <div className="p-5">
                <div className="flex items-center">
                  <div className="flex-shrink-0">
                    <div className="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                      <span className="text-white text-sm font-medium">{categoryStats.total}</span>
                    </div>
                  </div>
                  <div className="ml-5 w-0 flex-1">
                    <dl>
                      <dt className="text-sm font-medium text-gray-500 truncate">
                        Toplam Kategori
                      </dt>
                    </dl>
                  </div>
                </div>
              </div>
            </div>

            <div className="bg-white overflow-hidden shadow rounded-lg">
              <div className="p-5">
                <div className="flex items-center">
                  <div className="flex-shrink-0">
                    <div className="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                      <span className="text-white text-sm font-medium">{categoryStats.blog}</span>
                    </div>
                  </div>
                  <div className="ml-5 w-0 flex-1">
                    <dl>
                      <dt className="text-sm font-medium text-gray-500 truncate">
                        Blog Kategorileri
                      </dt>
                    </dl>
                  </div>
                </div>
              </div>
            </div>

            <div className="bg-white overflow-hidden shadow rounded-lg">
              <div className="p-5">
                <div className="flex items-center">
                  <div className="flex-shrink-0">
                    <div className="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                      <span className="text-white text-sm font-medium">{categoryStats.video}</span>
                    </div>
                  </div>
                  <div className="ml-5 w-0 flex-1">
                    <dl>
                      <dt className="text-sm font-medium text-gray-500 truncate">
                        Video Kategorileri
                      </dt>
                    </dl>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Filtre Butonları */}
          <div className="mt-6 flex space-x-1 rounded-lg bg-gray-100 p-1">
            <button
              onClick={() => setFilterType('all')}
              className={`flex-1 rounded-md py-2 px-3 text-sm font-medium ${
                filterType === 'all'
                  ? 'bg-white text-gray-900 shadow'
                  : 'text-gray-500 hover:text-gray-700'
              }`}
            >
              Tümü ({categoryStats.total})
            </button>
            <button
              onClick={() => setFilterType('blog')}
              className={`flex-1 rounded-md py-2 px-3 text-sm font-medium ${
                filterType === 'blog'
                  ? 'bg-white text-gray-900 shadow'
                  : 'text-gray-500 hover:text-gray-700'
              }`}
            >
              Blog ({categoryStats.blog})
            </button>
            <button
              onClick={() => setFilterType('video')}
              className={`flex-1 rounded-md py-2 px-3 text-sm font-medium ${
                filterType === 'video'
                  ? 'bg-white text-gray-900 shadow'
                  : 'text-gray-500 hover:text-gray-700'
              }`}
            >
              Video ({categoryStats.video})
            </button>
          </div>

          <div className="mt-8 flow-root">
            <div className="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div className="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div className="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                  <table className="min-w-full divide-y divide-gray-300">
                    <thead className="bg-gray-50">
                      <tr>
                        <th
                          scope="col"
                          className="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                        >
                          Kategori Adı
                        </th>
                        <th
                          scope="col"
                          className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                        >
                          Tür
                        </th>
                        <th
                          scope="col"
                          className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                        >
                          Açıklama
                        </th>
                        <th
                          scope="col"
                          className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                        >
                          Oluşturulma
                        </th>
                        <th
                          scope="col"
                          className="relative py-3.5 pl-3 pr-4 sm:pr-6"
                        >
                          <span className="sr-only">İşlemler</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-200 bg-white">
                      {filteredCategories.length === 0 ? (
                        <tr>
                          <td
                            colSpan={5}
                            className="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 text-center"
                          >
                            {filterType === 'all' ? 'Henüz kategori yok' : `Henüz ${filterType} kategorisi yok`}
                          </td>
                        </tr>
                      ) : (
                        filteredCategories.map((category) => (
                          <tr key={category.id}>
                            <td className="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                              {category.name}
                            </td>
                            <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                              <span
                                className={`inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ${
                                  category.type === 'blog'
                                    ? 'bg-green-50 text-green-700'
                                    : 'bg-purple-50 text-purple-700'
                                }`}
                              >
                                {category.type === 'blog' ? 'Blog' : 'Video'}
                              </span>
                            </td>
                            <td className="px-3 py-4 text-sm text-gray-500 max-w-xs truncate">
                              {category.description || '-'}
                            </td>
                            <td className="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                              {new Date(category.createdAt).toLocaleDateString('tr-TR')}
                            </td>
                            <td className="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                              <div className="flex justify-end gap-x-3">
                                <button
                                  onClick={() => handleEdit(category)}
                                  className="text-indigo-600 hover:text-indigo-900"
                                  title="Düzenle"
                                >
                                  <PencilIcon className="h-5 w-5" />
                                </button>
                                <button
                                  onClick={() => handleDelete(category.id)}
                                  className="text-red-600 hover:text-red-900"
                                  title="Sil"
                                >
                                  <TrashIcon className="h-5 w-5" />
                                </button>
                              </div>
                            </td>
                          </tr>
                        ))
                      )}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {isModalOpen && (
        <div className="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
          <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 className="text-lg font-medium text-gray-900 mb-4">
              {editingCategory ? 'Kategori Düzenle' : 'Yeni Kategori'}
            </h3>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label
                  htmlFor="categoryName"
                  className="block text-sm font-medium text-gray-700"
                >
                  Kategori Adı *
                </label>
                <input
                  type="text"
                  id="categoryName"
                  value={categoryName}
                  onChange={(e) => setCategoryName(e.target.value)}
                  className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border"
                  required
                />
              </div>
              
              <div>
                <label
                  htmlFor="categoryDescription"
                  className="block text-sm font-medium text-gray-700"
                >
                  Açıklama
                </label>
                <textarea
                  id="categoryDescription"
                  value={categoryDescription}
                  onChange={(e) => setCategoryDescription(e.target.value)}
                  rows={3}
                  className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border"
                  placeholder="Kategori açıklaması (isteğe bağlı)"
                />
              </div>

              <div>
                <label
                  htmlFor="categoryType"
                  className="block text-sm font-medium text-gray-700"
                >
                  Kategori Türü *
                </label>
                <select
                  id="categoryType"
                  value={categoryType}
                  onChange={(e) => setCategoryType(e.target.value)}
                  className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border"
                  required
                >
                  <option value="blog">Blog Kategorisi</option>
                  <option value="video">Video Kategorisi</option>
                </select>
                <p className="mt-1 text-xs text-gray-500">
                  Blog kategorileri blog yazılarında, video kategorileri videolarda kullanılır.
                </p>
              </div>

              <div className="flex justify-end gap-x-3 pt-2">
                <button
                  type="button"
                  onClick={() => {
                    setIsModalOpen(false)
                    setEditingCategory(null)
                    setCategoryName('')
                    setCategoryDescription('')
                    setCategoryType('blog')
                  }}
                  className="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                >
                  İptal
                </button>
                <button
                  type="submit"
                  className="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                  {editingCategory ? 'Güncelle' : 'Oluştur'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </DashboardLayout>
  )
} 