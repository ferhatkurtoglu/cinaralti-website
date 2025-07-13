'use client'

import DashboardLayout from '@/components/layout/DashboardLayout'
import { MagnifyingGlassIcon, PencilIcon, PlusIcon, TrashIcon } from '@heroicons/react/24/outline'
import { useSession } from 'next-auth/react'
import Link from 'next/link'
import { useRouter } from 'next/navigation'
import { useEffect, useState } from 'react'
import toast from 'react-hot-toast'

type BlogPost = {
  id: string
  title: string
  slug: string
  excerpt: string
  status: 'draft' | 'published'
  featured: boolean
  coverImage: string
  author: {
    name: string
  }
  category: {
    name: string
  } | null
  tags: string
  createdAt: string
  updatedAt: string
}

export default function BlogPostsPage() {
  const { data: session, status } = useSession()
  const router = useRouter()
  const [posts, setPosts] = useState<BlogPost[]>([])
  const [filteredPosts, setFilteredPosts] = useState<BlogPost[]>([])
  const [isLoading, setIsLoading] = useState(true)
  const [searchTerm, setSearchTerm] = useState('')
  const [statusFilter, setStatusFilter] = useState<'all' | 'draft' | 'published'>('all')
  const [sortBy, setSortBy] = useState<'newest' | 'oldest' | 'title'>('newest')

  useEffect(() => {
    if (status === 'unauthenticated') {
      router.push('/login')
    }
  }, [status, router])

  useEffect(() => {
    const fetchPosts = async () => {
      try {
        setIsLoading(true)
        const response = await fetch('/api/blog')
        if (response.ok) {
          const data = await response.json()
          setPosts(data)
        } else {
          toast.error('Blog yazıları yüklenirken hata oluştu')
        }
      } catch (error) {
        console.error('Blog yazıları yüklenirken hata oluştu:', error)
        toast.error('Blog yazıları yüklenirken hata oluştu')
      } finally {
        setIsLoading(false)
      }
    }

    if (status === 'authenticated') {
      fetchPosts()
    }
  }, [status])

  // Filtreleme ve sıralama
  useEffect(() => {
    let filtered = posts

    // Arama filtresi
    if (searchTerm) {
      filtered = filtered.filter(post =>
        post.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
        post.excerpt?.toLowerCase().includes(searchTerm.toLowerCase()) ||
        post.tags?.toLowerCase().includes(searchTerm.toLowerCase())
      )
    }

    // Durum filtresi
    if (statusFilter !== 'all') {
      filtered = filtered.filter(post => post.status === statusFilter)
    }

    // Sıralama
    filtered.sort((a, b) => {
      switch (sortBy) {
        case 'newest':
          return new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()
        case 'oldest':
          return new Date(a.createdAt).getTime() - new Date(b.createdAt).getTime()
        case 'title':
          return a.title.localeCompare(b.title)
        default:
          return 0
      }
    })

    setFilteredPosts(filtered)
  }, [posts, searchTerm, statusFilter, sortBy])

  const handleDelete = async (id: string, title: string) => {
    if (!confirm(`"${title}" adlı blog yazısını silmek istediğinizden emin misiniz?`)) {
      return
    }

    const loadingToast = toast.loading('Blog yazısı siliniyor...')

    try {
      const response = await fetch(`/api/blog/${id}`, {
        method: 'DELETE',
      })

      if (response.ok) {
        setPosts(posts.filter((post) => post.id !== id))
        toast.success('Blog yazısı başarıyla silindi', { id: loadingToast })
      } else {
        toast.error('Blog yazısı silinirken hata oluştu', { id: loadingToast })
      }
    } catch (error) {
      console.error('Blog yazısı silinirken hata oluştu:', error)
      toast.error('Blog yazısı silinirken hata oluştu', { id: loadingToast })
    }
  }

  const getStatusBadge = (status: string, featured: boolean) => {
    if (featured) {
      return (
        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
          <svg className="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
          </svg>
          Öne Çıkan
        </span>
      )
    }

    return status === 'published' ? (
      <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
        Yayınlandı
      </span>
    ) : (
      <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
        Taslak
      </span>
    )
  }

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('tr-TR', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    })
  }

  if (status === 'loading') {
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
      <div className="py-8">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          {/* Header */}
          <div className="mb-8">
            <div className="sm:flex sm:items-center sm:justify-between">
              <div>
                <h1 className="text-3xl font-bold text-gray-900">Blog Yazıları</h1>
                <p className="mt-2 text-sm text-gray-600">
                  Tüm blog yazılarınızı yönetin ve düzenleyin
                </p>
              </div>
              <div className="mt-4 sm:mt-0">
                <Link
                  href="/dashboard/blog/new"
                  className="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  <PlusIcon className="h-5 w-5 mr-2" />
                  Yeni Yazı Ekle
                </Link>
              </div>
            </div>
          </div>

          {/* Filtreler */}
          <div className="bg-white shadow-sm rounded-lg p-6 mb-8">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
              {/* Arama */}
              <div>
                <label htmlFor="search" className="block text-sm font-medium text-gray-700 mb-2">
                  Arama
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <MagnifyingGlassIcon className="h-5 w-5 text-gray-400" />
                  </div>
                  <input
                    type="text"
                    id="search"
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="Başlık, içerik veya etiket ara..."
                  />
                </div>
              </div>

              {/* Durum filtresi */}
              <div>
                <label htmlFor="status" className="block text-sm font-medium text-gray-700 mb-2">
                  Durum
                </label>
                <select
                  id="status"
                  value={statusFilter}
                  onChange={(e) => setStatusFilter(e.target.value as 'all' | 'draft' | 'published')}
                  className="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                >
                  <option value="all">Tümü</option>
                  <option value="draft">Taslak</option>
                  <option value="published">Yayınlandı</option>
                </select>
              </div>

              {/* Sıralama */}
              <div>
                <label htmlFor="sort" className="block text-sm font-medium text-gray-700 mb-2">
                  Sırala
                </label>
                <select
                  id="sort"
                  value={sortBy}
                  onChange={(e) => setSortBy(e.target.value as 'newest' | 'oldest' | 'title')}
                  className="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                >
                  <option value="newest">En Yeni</option>
                  <option value="oldest">En Eski</option>
                  <option value="title">Alfabetik</option>
                </select>
              </div>
            </div>
          </div>

          {/* Blog listesi */}
          {isLoading ? (
            <div className="bg-white shadow-sm rounded-lg p-8">
              <div className="text-center">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
                <p className="mt-4 text-gray-600">Blog yazıları yükleniyor...</p>
              </div>
            </div>
          ) : filteredPosts.length === 0 ? (
            <div className="bg-white shadow-sm rounded-lg p-8">
              <div className="text-center">
                <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 className="mt-2 text-sm font-medium text-gray-900">Blog yazısı bulunamadı</h3>
                <p className="mt-1 text-sm text-gray-500">
                  {searchTerm || statusFilter !== 'all' ? 'Arama kriterlerinize uygun blog yazısı bulunamadı.' : 'Henüz hiç blog yazısı yok.'}
                </p>
                <div className="mt-6">
                  <Link
                    href="/dashboard/blog/new"
                    className="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    <PlusIcon className="h-5 w-5 mr-2" />
                    İlk Blog Yazınızı Ekleyin
                  </Link>
                </div>
              </div>
            </div>
          ) : (
            <div className="bg-white shadow-sm rounded-lg overflow-hidden">
              <div className="grid grid-cols-1 gap-6 p-6">
                {filteredPosts.map((post) => (
                  <div key={post.id} className="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                    <div className="flex items-start justify-between">
                      <div className="flex-1">
                        <div className="flex items-center space-x-3 mb-2">
                          <h3 className="text-lg font-semibold text-gray-900 truncate">
                            {post.title}
                          </h3>
                          {getStatusBadge(post.status, post.featured)}
                        </div>
                        
                        {post.excerpt && (
                          <p className="text-sm text-gray-600 mb-3 line-clamp-2">
                            {post.excerpt}
                          </p>
                        )}

                        <div className="flex items-center space-x-4 text-sm text-gray-500">
                          <span>Yazar: {post.author.name}</span>
                          {post.category && (
                            <span>Kategori: {post.category.name}</span>
                          )}
                          <span>Oluşturulma: {formatDate(post.createdAt)}</span>
                          {post.tags && (
                            <span>Etiketler: {post.tags}</span>
                          )}
                        </div>
                      </div>

                      {post.coverImage && (
                        <div className="flex-shrink-0 ml-4">
                          <img
                            src={post.coverImage}
                            alt={post.title}
                            className="w-20 h-20 rounded-lg object-cover"
                          />
                        </div>
                      )}
                    </div>

                    <div className="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                      <div className="flex items-center space-x-2">
                        <Link
                          href={`/dashboard/blog/${post.id}`}
                          className="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                          <PencilIcon className="h-4 w-4 mr-1" />
                          Düzenle
                        </Link>
                        <button
                          onClick={() => handleDelete(post.id, post.title)}
                          className="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        >
                          <TrashIcon className="h-4 w-4 mr-1" />
                          Sil
                        </button>
                      </div>
                      
                      <div className="text-sm text-gray-500">
                        Slug: /{post.slug}
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}

          {/* İstatistikler */}
          <div className="mt-8 bg-white shadow-sm rounded-lg p-6">
            <h3 className="text-lg font-medium text-gray-900 mb-4">İstatistikler</h3>
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div className="bg-gray-50 p-4 rounded-lg">
                <div className="text-2xl font-bold text-gray-900">{posts.length}</div>
                <div className="text-sm text-gray-600">Toplam Yazı</div>
              </div>
              <div className="bg-green-50 p-4 rounded-lg">
                <div className="text-2xl font-bold text-green-900">
                  {posts.filter(p => p.status === 'published').length}
                </div>
                <div className="text-sm text-green-600">Yayınlandı</div>
              </div>
              <div className="bg-yellow-50 p-4 rounded-lg">
                <div className="text-2xl font-bold text-yellow-900">
                  {posts.filter(p => p.status === 'draft').length}
                </div>
                <div className="text-sm text-yellow-600">Taslak</div>
              </div>
              <div className="bg-indigo-50 p-4 rounded-lg">
                <div className="text-2xl font-bold text-indigo-900">
                  {posts.filter(p => p.featured).length}
                </div>
                <div className="text-sm text-indigo-600">Öne Çıkan</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  )
} 