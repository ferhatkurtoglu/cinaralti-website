'use client'

import DashboardLayout from '@/components/layout/DashboardLayout'
import { PencilIcon, PlusIcon, TrashIcon } from '@heroicons/react/24/outline'
import { useSession } from 'next-auth/react'
import Link from 'next/link'
import { useRouter } from 'next/navigation'
import { useEffect, useState } from 'react'

type Video = {
  id: string
  title: string
  description: string
  url: string
  thumbnail: string
  status: 'draft' | 'published'
  featured: boolean
  category: {
    name: string
  } | null
  tags: string
  author: {
    name: string
  }
  createdAt: string
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

export default function VideosPage() {
  const { data: session, status } = useSession()
  const router = useRouter()
  const [videos, setVideos] = useState<Video[]>([])
  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    if (status === 'unauthenticated') {
      router.push('/login')
    }
  }, [status, router])

  useEffect(() => {
    const fetchVideos = async () => {
      try {
        const response = await fetch('/api/videos')
        const data = await response.json()
        setVideos(data)
      } catch (error) {
        console.error('Videolar yüklenirken hata oluştu:', error)
      } finally {
        setIsLoading(false)
      }
    }

    if (status === 'authenticated') {
      fetchVideos()
    }
  }, [status])

  const handleDelete = async (id: string) => {
    if (!confirm('Bu videoyu silmek istediğinizden emin misiniz?')) {
      return
    }

    try {
      const response = await fetch(`/api/videos/${id}`, {
        method: 'DELETE',
      })

      if (response.ok) {
        setVideos(videos.filter((video) => video.id !== id))
      } else {
        console.error('Video silinirken hata oluştu')
      }
    } catch (error) {
      console.error('Video silinirken hata oluştu:', error)
    }
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
              <h1 className="text-2xl font-semibold text-gray-900">Videolar</h1>
              <p className="mt-2 text-sm text-gray-700">
                Tüm videoların listesi
              </p>
            </div>
            <div className="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
              <Link
                href="/dashboard/videos/new"
                className="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
              >
                <div className="flex items-center">
                  <PlusIcon className="h-5 w-5 mr-2" />
                  Yeni Video
                </div>
              </Link>
            </div>
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
                          className="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 w-1/3"
                        >
                          Video
                        </th>
                        <th
                          scope="col"
                          className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-24"
                        >
                          Kategori
                        </th>
                        <th
                          scope="col"
                          className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-20"
                        >
                          Durum
                        </th>
                        <th
                          scope="col"
                          className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-20"
                        >
                          Öne Çıkan
                        </th>
                        <th
                          scope="col"
                          className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-24"
                        >
                          Yazar
                        </th>
                        <th
                          scope="col"
                          className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 w-24"
                        >
                          Tarih
                        </th>
                        <th
                          scope="col"
                          className="relative py-3.5 pl-3 pr-4 sm:pr-6 w-20"
                        >
                          <span className="sr-only">İşlemler</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-200 bg-white">
                      {videos.length === 0 ? (
                        <tr>
                          <td
                            colSpan={7}
                            className="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 text-center"
                          >
                            Henüz video yok
                          </td>
                        </tr>
                      ) : (
                        videos.map((video) => {
                          const thumbnailUrl = getYouTubeThumbnail(video.url) || getYouTubeThumbnail(video.thumbnail)
                          
                          return (
                            <tr key={video.id}>
                              <td className="py-4 pl-4 pr-3 text-sm text-gray-900 sm:pl-6">
                                <div className="flex items-center space-x-3">
                                  {thumbnailUrl && (
                                    <div className="flex-shrink-0">
                                      <img
                                        src={thumbnailUrl}
                                        alt={video.title}
                                        className="h-8 w-12 object-cover rounded"
                                        onError={(e) => {
                                          // Hata durumunda placeholder göster
                                          e.currentTarget.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCA0OCAzMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQ4IiBoZWlnaHQ9IjMyIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xOCAxMlYyMEwyMyAxNkwxOCAxMloiIGZpbGw9IiM5Q0EzQUYiLz4KPC9zdmc+Cg=='
                                        }}
                                      />
                                    </div>
                                  )}
                                  <div className="min-w-0 flex-1">
                                    <div className="font-medium text-gray-900 truncate" title={video.title}>
                                      {video.title.length > 40 ? `${video.title.substring(0, 40)}...` : video.title}
                                    </div>
                                    {video.description && (
                                      <div className="text-gray-500 text-xs mt-1 truncate" title={video.description}>
                                        {video.description.length > 60 ? `${video.description.substring(0, 60)}...` : video.description}
                                      </div>
                                    )}
                                  </div>
                                </div>
                              </td>
                              <td className="px-3 py-4 text-sm text-gray-500">
                                <div className="truncate" title={video.category?.name || '-'}>
                                  {video.category?.name || '-'}
                                </div>
                              </td>
                              <td className="px-3 py-4 text-sm text-gray-500">
                                <span
                                  className={`inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ${
                                    video.status === 'published'
                                      ? 'bg-green-50 text-green-700'
                                      : 'bg-yellow-50 text-yellow-700'
                                  }`}
                                >
                                  {video.status === 'published' ? 'Yayında' : 'Taslak'}
                                </span>
                              </td>
                              <td className="px-3 py-4 text-sm text-gray-500">
                                {video.featured ? (
                                  <span className="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                    Evet
                                  </span>
                                ) : (
                                  <span className="inline-flex items-center rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700">
                                    Hayır
                                  </span>
                                )}
                              </td>
                              <td className="px-3 py-4 text-sm text-gray-500">
                                <div className="truncate" title={video.author.name}>
                                  {video.author.name}
                                </div>
                              </td>
                              <td className="px-3 py-4 text-sm text-gray-500">
                                {new Date(video.createdAt).toLocaleDateString('tr-TR')}
                              </td>
                              <td className="relative py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <div className="flex justify-end gap-x-2">
                                  <Link
                                    href={`/dashboard/videos/${video.id}`}
                                    className="text-indigo-600 hover:text-indigo-900 p-1"
                                    title="Düzenle"
                                  >
                                    <PencilIcon className="h-4 w-4" />
                                  </Link>
                                  <button
                                    onClick={() => handleDelete(video.id)}
                                    className="text-red-600 hover:text-red-900 p-1"
                                    title="Sil"
                                  >
                                    <TrashIcon className="h-4 w-4" />
                                  </button>
                                </div>
                              </td>
                            </tr>
                          )
                        })
                      )}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  )
} 