'use client'

import CKEditorComponent from '@/components/editor/CKEditor'
import ImageUpload from '@/components/ImageUpload'
import { zodResolver } from '@hookform/resolvers/zod'
import { useSession } from 'next-auth/react'
import { useRouter } from 'next/navigation'
import { useEffect, useState } from 'react'
import { useForm } from 'react-hook-form'
import toast from 'react-hot-toast'
import { z } from 'zod'

const blogSchema = z.object({
  title: z.string().min(1, 'Başlık zorunludur').max(200, 'Başlık 200 karakterden fazla olamaz'),
  slug: z.string().min(1, 'Slug zorunludur').max(200, 'Slug 200 karakterden fazla olamaz'),
  content: z.string().min(1, 'İçerik zorunludur'),
  excerpt: z.string().max(500, 'Özet 500 karakterden fazla olamaz').optional(),
  status: z.enum(['draft', 'published']),
  featured: z.boolean().default(false),
  categoryId: z.string().optional(),
  tags: z.string().optional(),
  coverImage: z.string().optional(),
})

type FormData = z.infer<typeof blogSchema>

interface Category {
  id: string
  name: string
}

export default function NewBlogPostPage() {
  const { data: session, status } = useSession()
  const router = useRouter()
  const [isLoading, setIsLoading] = useState(false)
  const [categories, setCategories] = useState<Category[]>([])
  const [autoSaveEnabled, setAutoSaveEnabled] = useState(false)

  const {
    register,
    handleSubmit,
    setValue,
    watch,
    formState: { errors },
  } = useForm<FormData>({
    resolver: zodResolver(blogSchema),
    defaultValues: {
      status: 'draft',
      featured: false,
    }
  })

  const watchedTitle = watch('title')
  const watchedContent = watch('content')
  const watchedCoverImage = watch('coverImage')

  // Kategorileri yükle
  useEffect(() => {
    const fetchCategories = async () => {
      try {
        const response = await fetch('/api/categories')
        if (response.ok) {
          const data = await response.json()
          setCategories(data.filter((cat: any) => cat.type === 'blog'))
        }
      } catch (error) {
        console.error('Kategoriler yüklenirken hata:', error)
      }
    }

    fetchCategories()
  }, [])

  // Otomatik slug oluşturma
  useEffect(() => {
    if (watchedTitle) {
      const slug = watchedTitle
        .toLowerCase()
        .replace(/ğ/g, 'g')
        .replace(/ü/g, 'u')
        .replace(/ş/g, 's')
        .replace(/ı/g, 'i')
        .replace(/ö/g, 'o')
        .replace(/ç/g, 'c')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim()
      setValue('slug', slug)
    }
  }, [watchedTitle, setValue])

  // Otomatik kaydetme
  useEffect(() => {
    if (!autoSaveEnabled) return

    const interval = setInterval(() => {
      const formData = watch()
      if (formData.title && formData.content) {
        localStorage.setItem('blog-draft', JSON.stringify(formData))
        toast.success('Taslak otomatik kaydedildi', { duration: 2000 })
      }
    }, 30000) // 30 saniyede bir

    return () => clearInterval(interval)
  }, [watch, autoSaveEnabled])

  // Sayfa yüklendiğinde taslak kontrol et
  useEffect(() => {
    const savedDraft = localStorage.getItem('blog-draft')
    if (savedDraft) {
      const confirmed = window.confirm('Kaydedilmiş bir taslak bulundu. Yüklemek ister misiniz?')
      if (confirmed) {
        const draft = JSON.parse(savedDraft)
        Object.keys(draft).forEach(key => {
          setValue(key as keyof FormData, draft[key])
        })
        setAutoSaveEnabled(true)
      }
    }
  }, [setValue])

  const onSubmit = async (data: FormData) => {
    const loadingToast = toast.loading('Blog yazısı kaydediliyor...')
    
    try {
      setIsLoading(true)
      const response = await fetch('/api/blog', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
      })

      if (!response.ok) {
        const errorData = await response.json()
        throw new Error(errorData.error || 'Blog yazısı kaydedilirken bir hata oluştu')
      }

      toast.success('Blog yazısı başarıyla kaydedildi!', { id: loadingToast })
      localStorage.removeItem('blog-draft')
      router.push('/dashboard/blog')
    } catch (error) {
      toast.error(error instanceof Error ? error.message : 'Bir hata oluştu', { id: loadingToast })
    } finally {
      setIsLoading(false)
    }
  }

  const handleSaveDraft = () => {
    const formData = watch()
    localStorage.setItem('blog-draft', JSON.stringify(formData))
    toast.success('Taslak kaydedildi')
  }

  if (status === 'loading') {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
          <p className="mt-4 text-gray-600">Yükleniyor...</p>
        </div>
      </div>
    )
  }

  if (status === 'unauthenticated') {
    router.push('/login')
    return null
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Header */}
        <div className="bg-white shadow-sm rounded-lg p-6 mb-6">
          <div className="flex items-center justify-between">
            <div>
              <h1 className="text-3xl font-bold text-gray-900">Yeni Blog Yazısı</h1>
              <p className="mt-1 text-sm text-gray-500">
                Yeni bir blog yazısı oluşturun ve yayınlayın
              </p>
            </div>
            <div className="flex items-center space-x-4">
              <label className="flex items-center">
                <input
                  type="checkbox"
                  checked={autoSaveEnabled}
                  onChange={(e) => setAutoSaveEnabled(e.target.checked)}
                  className="mr-2"
                />
                <span className="text-sm text-gray-600">Otomatik kaydet</span>
              </label>
              <button
                type="button"
                onClick={handleSaveDraft}
                className="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Taslak Kaydet
              </button>
            </div>
          </div>
        </div>

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {/* Ana içerik */}
            <div className="lg:col-span-2 space-y-6">
              {/* Başlık */}
              <div className="bg-white shadow-sm rounded-lg p-6">
                <div className="space-y-4">
                  <div>
                    <label htmlFor="title" className="block text-sm font-medium text-gray-700 mb-2">
                      Başlık <span className="text-red-500">*</span>
                    </label>
                    <input
                      type="text"
                      id="title"
                      {...register('title')}
                      className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      placeholder="Blog yazınızın başlığını girin..."
                    />
                    {errors.title && (
                      <p className="mt-1 text-sm text-red-600">{errors.title.message}</p>
                    )}
                  </div>

                  <div>
                    <label htmlFor="slug" className="block text-sm font-medium text-gray-700 mb-2">
                      URL Slug <span className="text-red-500">*</span>
                    </label>
                    <input
                      type="text"
                      id="slug"
                      {...register('slug')}
                      className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      placeholder="url-slug-burada-olacak"
                    />
                    {errors.slug && (
                      <p className="mt-1 text-sm text-red-600">{errors.slug.message}</p>
                    )}
                    <p className="mt-1 text-xs text-gray-500">
                      Yazınızın URL'sinde görünecek olan slug. Başlık girildiğinde otomatik oluşturulur.
                    </p>
                  </div>
                </div>
              </div>

              {/* İçerik */}
              <div className="bg-white shadow-sm rounded-lg p-6">
                <label htmlFor="content" className="block text-sm font-medium text-gray-700 mb-4">
                  İçerik <span className="text-red-500">*</span>
                </label>
                <CKEditorComponent
                  content=""
                  onChange={(content) => setValue('content', content)}
                  placeholder="Blog yazınızın içeriğini buraya yazın..."
                />
                {errors.content && (
                  <p className="mt-2 text-sm text-red-600">{errors.content.message}</p>
                )}
              </div>

              {/* Özet */}
              <div className="bg-white shadow-sm rounded-lg p-6">
                <label htmlFor="excerpt" className="block text-sm font-medium text-gray-700 mb-2">
                  Özet
                </label>
                <textarea
                  id="excerpt"
                  {...register('excerpt')}
                  rows={4}
                  className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="Blog yazınızın kısa bir özetini yazın..."
                />
                {errors.excerpt && (
                  <p className="mt-1 text-sm text-red-600">{errors.excerpt.message}</p>
                )}
                <p className="mt-1 text-xs text-gray-500">
                  Blog listesinde ve sosyal medya paylaşımlarında görünecek kısa açıklama.
                </p>
              </div>
            </div>

            {/* Yan panel */}
            <div className="space-y-6">
              {/* Yayın Ayarları */}
              <div className="bg-white shadow-sm rounded-lg p-6">
                <h3 className="text-lg font-medium text-gray-900 mb-4">Yayın Ayarları</h3>
                
                <div className="space-y-4">
                  <div>
                    <label htmlFor="status" className="block text-sm font-medium text-gray-700 mb-2">
                      Durum <span className="text-red-500">*</span>
                    </label>
                    <select
                      id="status"
                      {...register('status')}
                      className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                      <option value="draft">Taslak</option>
                      <option value="published">Yayınla</option>
                    </select>
                    {errors.status && (
                      <p className="mt-1 text-sm text-red-600">{errors.status.message}</p>
                    )}
                  </div>

                  <div className="flex items-center">
                    <input
                      type="checkbox"
                      id="featured"
                      {...register('featured')}
                      className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    />
                    <label htmlFor="featured" className="ml-2 block text-sm text-gray-900">
                      Öne çıkan yazı
                    </label>
                  </div>
                </div>
              </div>

              {/* Kategori */}
              <div className="bg-white shadow-sm rounded-lg p-6">
                <h3 className="text-lg font-medium text-gray-900 mb-4">Kategori</h3>
                <select
                  {...register('categoryId')}
                  className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                  <option value="">Kategori seçin...</option>
                  {categories.map((category) => (
                    <option key={category.id} value={category.id}>
                      {category.name}
                    </option>
                  ))}
                </select>
              </div>

              {/* Etiketler */}
              <div className="bg-white shadow-sm rounded-lg p-6">
                <h3 className="text-lg font-medium text-gray-900 mb-4">Etiketler</h3>
                <input
                  type="text"
                  {...register('tags')}
                  className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="etiket1, etiket2, etiket3"
                />
                <p className="mt-1 text-xs text-gray-500">
                  Etiketleri virgülle ayırarak yazın.
                </p>
              </div>

              {/* Kapak Resmi */}
              <div className="bg-white shadow-sm rounded-lg p-6">
                <h3 className="text-lg font-medium text-gray-900 mb-4">Kapak Resmi</h3>
                <ImageUpload
                  value={watchedCoverImage || ""}
                  onChange={(url) => setValue('coverImage', url)}
                  label="Resim Yükle"
                />
              </div>
            </div>
          </div>

          {/* Alt butonlar */}
          <div className="bg-white shadow-sm rounded-lg p-6">
            <div className="flex justify-between items-center">
              <button
                type="button"
                onClick={() => router.push('/dashboard/blog')}
                className="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Geri Dön
              </button>
              
              <div className="flex space-x-4">
                <button
                  type="button"
                  onClick={() => setValue('status', 'draft')}
                  className="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  Taslak Olarak Kaydet
                </button>
                <button
                  type="submit"
                  disabled={isLoading}
                  className="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                >
                  {isLoading ? (
                    <>
                      <svg className="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Kaydediliyor...
                    </>
                  ) : (
                    <>
                      <svg className="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                      </svg>
                      Yayınla
                    </>
                  )}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  )
} 