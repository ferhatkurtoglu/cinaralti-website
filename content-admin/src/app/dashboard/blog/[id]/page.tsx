'use client'

import RichTextEditor from '@/components/editor/RichTextEditor'
import { useSession } from 'next-auth/react'
import { useRouter } from 'next/navigation'
import { useEffect, useState } from 'react'
import { useForm } from 'react-hook-form'

interface FormData {
  title: string
  content: string
  status: 'draft' | 'published'
}

export default function BlogPostPage({ params }: { params: { id: string } }) {
  const { data: session, status } = useSession()
  const router = useRouter()
  const [isLoading, setIsLoading] = useState(false)
  const [post, setPost] = useState<any>(null)

  const {
    register,
    handleSubmit,
    setValue,
    formState: { errors },
  } = useForm<FormData>()

  useEffect(() => {
    if (status === 'unauthenticated') {
      router.push('/login')
    }
  }, [status, router])

  useEffect(() => {
    if (params.id !== 'new') {
      fetch(`/api/blog/${params.id}`)
        .then((res) => res.json())
        .then((data) => {
          setPost(data)
          setValue('title', data.title)
          setValue('content', data.content)
          setValue('status', data.status)
        })
        .catch((error) => {
          console.error('Blog yazısı getirilirken hata oluştu:', error)
        })
    }
  }, [params.id, setValue])

  const onSubmit = async (data: FormData) => {
    try {
      setIsLoading(true)
      const response = await fetch(`/api/blog${params.id === 'new' ? '' : `/${params.id}`}`, {
        method: params.id === 'new' ? 'POST' : 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
      })

      if (!response.ok) {
        throw new Error('Blog yazısı kaydedilirken bir hata oluştu')
      }

      router.push('/dashboard/blog')
    } catch (error) {
      console.error('Blog yazısı kaydedilirken hata oluştu:', error)
    } finally {
      setIsLoading(false)
    }
  }

  if (status === 'loading' || (params.id !== 'new' && !post)) {
    return (
      <div className="flex items-center justify-center min-h-screen">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
      </div>
    )
  }

  return (
    <div className="max-w-4xl mx-auto p-6">
      <h1 className="text-2xl font-bold mb-6">
        {params.id === 'new' ? 'Yeni Blog Yazısı' : 'Blog Yazısını Düzenle'}
      </h1>

      <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
        <div>
          <label htmlFor="title" className="block text-sm font-medium text-gray-700">
            Başlık
          </label>
          <input
            type="text"
            id="title"
            {...register('title', { required: 'Başlık zorunludur' })}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          />
          {errors.title && (
            <p className="mt-1 text-sm text-red-600">{errors.title.message}</p>
          )}
        </div>

        <div>
          <label htmlFor="content" className="block text-sm font-medium text-gray-700">
            İçerik
          </label>
          <RichTextEditor
            content={post?.content || ''}
            onChange={(content) => setValue('content', content)}
          />
          {errors.content && (
            <p className="mt-1 text-sm text-red-600">{errors.content.message}</p>
          )}
        </div>

        <div>
          <label htmlFor="status" className="block text-sm font-medium text-gray-700">
            Durum
          </label>
          <select
            id="status"
            {...register('status', { required: 'Durum zorunludur' })}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
          >
            <option value="draft">Taslak</option>
            <option value="published">Yayınlandı</option>
          </select>
          {errors.status && (
            <p className="mt-1 text-sm text-red-600">{errors.status.message}</p>
          )}
        </div>

        <div className="flex justify-end space-x-4">
          <button
            type="button"
            onClick={() => router.push('/dashboard/blog')}
            className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            İptal
          </button>
          <button
            type="submit"
            disabled={isLoading}
            className="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
          >
            {isLoading ? 'Kaydediliyor...' : 'Kaydet'}
          </button>
        </div>
      </form>
    </div>
  )
} 