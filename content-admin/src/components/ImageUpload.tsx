'use client'

import { ArrowUpTrayIcon, PhotoIcon, XMarkIcon } from '@heroicons/react/24/outline'
import Image from 'next/image'
import { useRef, useState } from 'react'
import toast from 'react-hot-toast'

interface ImageUploadProps {
  value?: string
  onChange: (url: string) => void
  label?: string
  className?: string
}

export default function ImageUpload({ value, onChange, label = 'Resim Yükle', className = '' }: ImageUploadProps) {
  const [isUploading, setIsUploading] = useState(false)
  const [isDragOver, setIsDragOver] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const fileInputRef = useRef<HTMLInputElement>(null)

  const handleFileUpload = async (file: File) => {
    if (!file) return

    // Dosya türü kontrolü
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
    if (!allowedTypes.includes(file.type)) {
      const errorMsg = 'Geçersiz dosya türü. Sadece JPG, PNG, GIF ve WebP dosyaları desteklenir.'
      setError(errorMsg)
      toast.error(errorMsg)
      return
    }

    // Dosya boyutu kontrolü (5MB max)
    if (file.size > 5 * 1024 * 1024) {
      const errorMsg = 'Dosya boyutu 5MB\'dan büyük olamaz.'
      setError(errorMsg)
      toast.error(errorMsg)
      return
    }

    setIsUploading(true)
    setError(null)
    const loadingToast = toast.loading('Resim yükleniyor...')

    try {
      const formData = new FormData()
      formData.append('file', file)

      const response = await fetch('/api/upload', {
        method: 'POST',
        body: formData,
      })

      const data = await response.json()

      if (!response.ok) {
        throw new Error(data.error || 'Resim yüklenirken bir hata oluştu')
      }

      onChange(data.url)
      toast.success('Resim başarıyla yüklendi!', { id: loadingToast })
      setError(null)
    } catch (err) {
      const errorMsg = err instanceof Error ? err.message : 'Bir hata oluştu'
      setError(errorMsg)
      toast.error(errorMsg, { id: loadingToast })
      console.error('Upload error:', err)
    } finally {
      setIsUploading(false)
    }
  }

  const handleFileChange = (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0]
    if (file) {
      handleFileUpload(file)
    }
  }

  const handleDrop = (event: React.DragEvent<HTMLDivElement>) => {
    event.preventDefault()
    setIsDragOver(false)
    
    const files = event.dataTransfer.files
    if (files.length > 0) {
      handleFileUpload(files[0])
    }
  }

  const handleDragOver = (event: React.DragEvent<HTMLDivElement>) => {
    event.preventDefault()
    setIsDragOver(true)
  }

  const handleDragLeave = (event: React.DragEvent<HTMLDivElement>) => {
    event.preventDefault()
    setIsDragOver(false)
  }

  const handleRemove = () => {
    onChange('')
    setError(null)
    if (fileInputRef.current) {
      fileInputRef.current.value = ''
    }
    toast.success('Resim kaldırıldı')
  }

  const openFileDialog = () => {
    fileInputRef.current?.click()
  }

  return (
    <div className={`space-y-3 ${className}`}>
      <label className="block text-sm font-medium text-gray-700">
        {label}
      </label>
      
      {value ? (
        <div className="space-y-3">
          <div className="relative inline-block group">
            <Image
              src={value}
              alt="Yüklenen resim"
              width={300}
              height={200}
              className="rounded-lg object-cover border border-gray-300 shadow-sm"
            />
            <div className="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
              <button
                type="button"
                onClick={handleRemove}
                className="bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors"
                title="Resmi kaldır"
              >
                <XMarkIcon className="h-5 w-5" />
              </button>
            </div>
          </div>
          <div className="flex items-center space-x-2">
            <button
              type="button"
              onClick={openFileDialog}
              className="text-sm text-indigo-600 hover:text-indigo-700 font-medium"
            >
              Resmi Değiştir
            </button>
            <span className="text-gray-300">|</span>
            <button
              type="button"
              onClick={handleRemove}
              className="text-sm text-red-600 hover:text-red-700 font-medium"
            >
              Kaldır
            </button>
          </div>
        </div>
      ) : (
        <div
          className={`border-2 border-dashed rounded-lg p-8 text-center transition-all cursor-pointer ${
            isDragOver
              ? 'border-indigo-400 bg-indigo-50'
              : isUploading
              ? 'border-gray-300 bg-gray-50'
              : 'border-gray-300 hover:border-gray-400 hover:bg-gray-50'
          }`}
          onDrop={handleDrop}
          onDragOver={handleDragOver}
          onDragLeave={handleDragLeave}
          onClick={openFileDialog}
        >
          {isUploading ? (
            <div className="flex flex-col items-center">
              <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
              <p className="mt-3 text-sm font-medium text-gray-900">Yükleniyor...</p>
              <p className="text-xs text-gray-500">Lütfen bekleyin</p>
            </div>
          ) : (
            <div className="flex flex-col items-center">
              {isDragOver ? (
                <ArrowUpTrayIcon className="h-12 w-12 text-indigo-400" />
              ) : (
                <PhotoIcon className="h-12 w-12 text-gray-400" />
              )}
              <div className="mt-4">
                <p className="text-sm font-medium text-gray-900">
                  {isDragOver ? 'Dosyayı bırakın' : 'Resim seçin veya sürükleyin'}
                </p>
                <p className="mt-1 text-xs text-gray-500">
                  PNG, JPG, GIF, WebP - Max 5MB
                </p>
              </div>
            </div>
          )}
        </div>
      )}

      {/* Gizli file input */}
      <input
        ref={fileInputRef}
        type="file"
        className="sr-only"
        accept="image/*"
        onChange={handleFileChange}
        disabled={isUploading}
        aria-label="Resim dosyası seç"
        title="Resim dosyası seç"
      />

      {/* Error message */}
      {error && (
        <div className="p-3 bg-red-50 border border-red-200 rounded-md">
          <div className="flex">
            <div className="flex-shrink-0">
              <XMarkIcon className="h-5 w-5 text-red-400" />
            </div>
            <div className="ml-3">
              <p className="text-sm text-red-600">{error}</p>
            </div>
          </div>
        </div>
      )}

      {/* Yükleme ipuçları */}
      {!value && !isUploading && (
        <div className="text-xs text-gray-500 space-y-1">
          <p>💡 <strong>İpuçları:</strong></p>
          <ul className="list-disc list-inside ml-4 space-y-1">
            <li>En iyi sonuç için 16:9 oranında resimler kullanın</li>
            <li>Minimum 800x450 piksel boyutunda resim önerilir</li>
            <li>Dosya boyutu ne kadar küçükse o kadar hızlı yüklenir</li>
          </ul>
        </div>
      )}
    </div>
  )
} 