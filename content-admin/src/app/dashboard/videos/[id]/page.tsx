'use client'

import DashboardLayout from '@/components/layout/DashboardLayout'
import { useSession } from 'next-auth/react'
import { useRouter } from 'next/navigation'
import { useEffect, useState } from 'react'
import { useForm } from 'react-hook-form'

type FormData = {
  title: string
  description: string
  url: string
  thumbnail: string
  status: 'draft' | 'published'
  featured: boolean
  categoryId: string
  tags: string
}

// YouTube URL'sinden video ID'sini çıkaran fonksiyon
function getYouTubeVideoId(url: string): string | null {
  const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/
  const match = url.match(regExp)
  return (match && match[2].length === 11) ? match[2] : null
}

// YouTube thumbnail URL'si oluşturan fonksiyon
function getYouTubeThumbnail(url: string): string | null {
  const videoId = getYouTubeVideoId(url)
  if (!videoId) return null
  return `https://img.youtube.com/vi/${videoId}/mqdefault.jpg`
}

export default function VideoEditPage({
  params,
}: {
  params: { id: string }
}) {
  const { data: session, status } = useSession()
  const router = useRouter()
  const [isLoading, setIsLoading] = useState(false)
  const [categories, setCategories] = useState([])
  const [previewThumbnail, setPreviewThumbnail] = useState<string | null>(null)

  const {
    register,
    handleSubmit,
    setValue,
    watch,
    formState: { errors },
  } = useForm<FormData>()

  const watchedUrl = watch('url')
  const watchedThumbnail = watch('thumbnail')

  // URL değiştiğinde otomatik thumbnail oluştur
  useEffect(() => {
    if (watchedUrl) {
      const autoThumbnail = getYouTubeThumbnail(watchedUrl)
      if (autoThumbnail) {
        setValue('thumbnail', autoThumbnail)
        setPreviewThumbnail(autoThumbnail)
      }
    }
  }, [watchedUrl, setValue])

  // Thumbnail değiştiğinde preview'u güncelle
  useEffect(() => {
    if (watchedThumbnail) {
      const thumbnailUrl = getYouTubeThumbnail(watchedThumbnail) || watchedThumbnail
      setPreviewThumbnail(thumbnailUrl)
    }
  }, [watchedThumbnail])

  useEffect(() => {
    if (status === 'unauthenticated') {
      router.push('/login')
    }
  }, [status, router])

  useEffect(() => {
    // Kategorileri yükle
    const fetchCategories = async () => {
      try {
        const response = await fetch('/api/categories?type=video')
        const data = await response.json()
        setCategories(data)
      } catch (error) {
        console.error('Kategoriler yüklenirken hata oluştu:', error)
      }
    }

    fetchCategories()

    // Video verilerini yükle
    if (params.id !== 'new') {
      fetch(`/api/videos/${params.id}`)
        .then((res) => res.json())
        .then((data) => {
          setValue('title', data.title)
          setValue('description', data.description || '')
          setValue('url', data.url)
          setValue('thumbnail', data.thumbnail || '')
          setValue('status', data.status)
          setValue('featured', data.featured)
          setValue('categoryId', data.categoryId || '')
          setValue('tags', data.tags || '')
          
          // Thumbnail preview'unu ayarla
          const thumbnailUrl = getYouTubeThumbnail(data.url) || getYouTubeThumbnail(data.thumbnail)
          if (thumbnailUrl) {
            setPreviewThumbnail(thumbnailUrl)
          }
        })
        .catch((error) => {
          console.error('Video getirilirken hata oluştu:', error)
        })
    }
  }, [params.id, setValue])

  const onSubmit = async (data: FormData) => {
    try {
      setIsLoading(true)
      const response = await fetch(`/api/videos${params.id === 'new' ? '' : `/${params.id}`}`, {
        method: params.id === 'new' ? 'POST' : 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
      })

      if (!response.ok) {
        throw new Error('Video kaydedilirken bir hata oluştu')
      }

      router.push('/dashboard/videos')
    } catch (error) {
      console.error('Video kaydedilirken hata oluştu:', error)
    } finally {
      setIsLoading(false)
    }
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
      <div className="py-6">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="md:flex md:items-center md:justify-between">
            <div className="min-w-0 flex-1">
              <h2 className="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                {params.id === 'new' ? 'Yeni Video' : 'Video Düzenle'}
              </h2>
            </div>
          </div>

          <form onSubmit={handleSubmit(onSubmit)} className="mt-8 space-y-8">
            <div className="space-y-8 divide-y divide-gray-200">
              <div className="space-y-6">
                <div>
                  <label
                    htmlFor="title"
                    className="block text-sm font-medium leading-6 text-gray-900"
                  >
                    Başlık
                  </label>
                  <div className="mt-2">
                    <input
                      type="text"
                      {...register('title', { required: 'Başlık zorunludur' })}
                      className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                    {errors.title && (
                      <p className="mt-2 text-sm text-red-600">
                        {errors.title.message}
                      </p>
                    )}
                  </div>
                </div>

                <div>
                  <label
                    htmlFor="description"
                    className="block text-sm font-medium leading-6 text-gray-900"
                  >
                    Açıklama
                  </label>
                  <div className="mt-2">
                    <textarea
                      rows={3}
                      {...register('description')}
                      className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                  </div>
                </div>

                <div>
                  <label
                    htmlFor="url"
                    className="block text-sm font-medium leading-6 text-gray-900"
                  >
                    Video URL
                  </label>
                  <div className="mt-2">
                    <input
                      type="url"
                      {...register('url', { required: 'Video URL zorunludur' })}
                      className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                    {errors.url && (
                      <p className="mt-2 text-sm text-red-600">
                        {errors.url.message}
                      </p>
                    )}
                  </div>
                  <p className="mt-1 text-sm text-gray-500">
                    YouTube URL'si girildiğinde thumbnail otomatik olarak oluşturulur.
                  </p>
                </div>

                <div>
                  <label
                    htmlFor="thumbnail"
                    className="block text-sm font-medium leading-6 text-gray-900"
                  >
                    Önizleme Resmi URL
                  </label>
                  <div className="mt-2">
                    <input
                      type="url"
                      {...register('thumbnail')}
                      className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                  </div>
                  {previewThumbnail && (
                    <div className="mt-3">
                      <p className="text-sm font-medium text-gray-700 mb-2">Önizleme:</p>
                      <img
                        src={previewThumbnail}
                        alt="Thumbnail Önizleme"
                        className="h-32 w-48 object-cover rounded-lg border border-gray-200"
                        onError={(e) => {
                          e.currentTarget.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTkyIiBoZWlnaHQ9IjEyOCIgdmlld0JveD0iMCAwIDE5MiAxMjgiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxOTIiIGhlaWdodD0iMTI4IiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik03MiA0OFY4MEw5NiA2NEw3MiA0OFoiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+Cg=='
                        }}
                      />
                    </div>
                  )}
                </div>

                <div>
                  <label
                    htmlFor="category"
                    className="block text-sm font-medium leading-6 text-gray-900"
                  >
                    Kategori
                  </label>
                  <div className="mt-2">
                    <select
                      {...register('categoryId')}
                      className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    >
                      <option value="">Kategori seçin</option>
                      {categories.map((category: any) => (
                        <option key={category.id} value={category.id}>
                          {category.name}
                        </option>
                      ))}
                    </select>
                  </div>
                </div>

                <div>
                  <label
                    htmlFor="tags"
                    className="block text-sm font-medium leading-6 text-gray-900"
                  >
                    Etiketler
                  </label>
                  <div className="mt-2">
                    <input
                      type="text"
                      {...register('tags')}
                      placeholder="Etiketleri virgülle ayırın"
                      className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    />
                  </div>
                </div>

                <div className="flex items-center gap-x-3">
                  <input
                    type="checkbox"
                    {...register('featured')}
                    className="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                  />
                  <label
                    htmlFor="featured"
                    className="block text-sm font-medium leading-6 text-gray-900"
                  >
                    Öne Çıkan Video
                  </label>
                </div>

                <div>
                  <label
                    htmlFor="status"
                    className="block text-sm font-medium leading-6 text-gray-900"
                  >
                    Durum
                  </label>
                  <div className="mt-2">
                    <select
                      {...register('status', {
                        required: 'Durum seçimi zorunludur',
                      })}
                      className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    >
                      <option value="draft">Taslak</option>
                      <option value="published">Yayınla</option>
                    </select>
                    {errors.status && (
                      <p className="mt-2 text-sm text-red-600">
                        {errors.status.message}
                      </p>
                    )}
                  </div>
                </div>
              </div>
            </div>

            <div className="pt-5">
              <div className="flex justify-end gap-x-3">
                <button
                  type="button"
                  onClick={() => router.push('/dashboard/videos')}
                  className="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                >
                  İptal
                </button>
                <button
                  type="submit"
                  disabled={isLoading}
                  className="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                  {isLoading ? 'Kaydediliyor...' : 'Kaydet'}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </DashboardLayout>
  )
} 